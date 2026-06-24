<?php

namespace App\Services;

use App\Models\Trip;
use App\Models\Fleet\Driver;
use App\Models\DriverVehicleAssignment;
use Carbon\Carbon;

class PayrollService
{
    /**
     * Aylık maaş hesaplama.
     * Zimmet geçmişi tablosundan şoförün o aydaki tüm araçlarını bulur.
     */
    public function calculateMonthlyPayroll(Driver $driver, $month, $year)
    {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        $driverStart = $driver->start_date ? Carbon::parse($driver->start_date)->startOfDay() : null;
        $driverLeave = $driver->leave_date ? Carbon::parse($driver->leave_date)->endOfDay() : null;

        // --- ARAÇ ZİMMET GEÇMİŞİNDEN O AYDAKİ TÜM ARAÇLARI BUL ---
        $vehicleIdsInMonth = $this->resolveVehicleIdsForPeriod($driver, $startDate, $endDate);

        $trips = Trip::with(['serviceRoute', 'morningDriver', 'eveningDriver'])
            ->where(function ($q) use ($driver, $vehicleIdsInMonth) {
                // 1. Şoför ID'si doğrudan eşleşenler
                $q->where('driver_id', $driver->id);
                
                // 2. Yeni yapıdaki manuel şoför atamaları
                $q->orWhere('morning_driver_id', $driver->id)
                  ->orWhere('evening_driver_id', $driver->id);

                // 3. Şoför ID'si eşleşmese bile araç şoförün o aydaki araçlarından biriyse
                if (!empty($vehicleIdsInMonth)) {
                    $q->orWhere(function ($sq) use ($vehicleIdsInMonth) {
                        $sq->whereIn('vehicle_id', $vehicleIdsInMonth)
                           ->orWhereIn('morning_vehicle_id', $vehicleIdsInMonth)
                           ->orWhereIn('evening_vehicle_id', $vehicleIdsInMonth);
                    });
                }
            })
            // GÜVENLİK FİLTRESİ: Sadece şoförün çalıştığı tarih aralığındaki seferleri getir
            ->when($driverStart, function($q) use ($driverStart) {
                return $q->where('trip_date', '>=', $driverStart->toDateString());
            })
            ->when($driverLeave, function($q) use ($driverLeave) {
                return $q->where('trip_date', '<=', $driverLeave->toDateString());
            })
            ->whereBetween('trip_date', [$startDate, $endDate])
            ->get();

        // --- 1. ANA MAAŞ HESAPLAMA (TARİH BAZLI) ---
        // Kullanıcı isteği: Ana maaş puantaja göre değil, işe giriş/çıkış tarihlerine göre hesaplanır.
        
        $monthStart = $startDate->copy()->startOfDay();
        $monthEnd = $endDate->copy()->endOfDay();

        // Ay içindeki fiili çalışma başlangıç ve bitişini belirle
        $periodStart = $driverStart ? ($driverStart->gt($monthStart) ? $driverStart->copy() : $monthStart->copy()) : $monthStart->copy();
        $periodEnd = $driverLeave ? ($driverLeave->lt($monthEnd) ? $driverLeave->copy() : $monthEnd->copy()) : $monthEnd->copy();

        $workDays = 0;
        
        if ($periodStart->lte($periodEnd)) {
            // Takvim günü farkı (+1 ekleyerek kapsayıcı yapıyoruz)
            $workDays = (float) $periodStart->diffInDays($periodEnd->startOfDay()) + 1;

            // Vardiya düzeltmeleri (Yarım gün hakedişler)
            
            // Eğer bu ay işe başladıysa ve "Akşam" başladıysa sabahı düş
            if ($driverStart && $periodStart->equalTo($driverStart) && $driver->start_shift === 'evening') {
                $workDays -= 0.5;
            }

            // Eğer bu ay işten ayrıldıysa ve sadece "Sabah" veya "Akşam" yapıp bıraktıysa düzelt
            if ($driverLeave && $periodEnd->equalTo($driverLeave->startOfDay())) {
                if ($driver->leave_shift === 'morning') {
                    // Sadece sabah çalıştı, akşamı düş
                    $workDays -= 0.5;
                }
                // Not: 'evening' veya 'full_day' ise tam gün sayılır (düşüş yapılmaz)
            }
            
            // Ayın tamamını çalıştıysa (Ay başından ay sonuna kadar aktifse) 30 güne sabitle
            $isFullMonth = true;
            if ($driverStart && $driverStart->gt($monthStart)) $isFullMonth = false;
            if ($driverLeave && $driverLeave->lt($monthEnd)) $isFullMonth = false;
            
            if ($isFullMonth) {
                $workDays = 30.0;
            }
            
            // Hiçbir durumda 30 günü aşamaz (Kullanıcı 30 gün üzerinden baz alınacak dedi)
            if ($workDays > 30) {
                $workDays = 30.0;
            }
        }

        // --- 2. EKSTRA HAKEDİŞ HESAPLAMA (PUANTAJ BAZLI) ---
        $groupedDetails = [];
        $totalExtraEarnings = 0;
        
        // Zimmet geçmişi kayıtlarını önbelleğe al (her trip için tek tek sorgu atmamak için)
        $assignmentHistory = $driver->vehicleAssignmentsInRange($startDate, $endDate);
        
        foreach ($trips as $trip) {
            $route = $trip->serviceRoute;
            if (!$route) continue;

            // --- VARDİYA BAZLI HAKEDİŞ FİLTRESİ ---
            $tripDate = $trip->trip_date->startOfDay();
            $canDoMorning = true;
            $canDoEvening = true;

            // İşe giriş günü kontrolü
            if ($driverStart && $tripDate->equalTo($driverStart)) {
                if ($driver->start_shift === 'evening') {
                    $canDoMorning = false;
                }
            }

            // İşten ayrılma günü kontrolü
            if ($driverLeave && $tripDate->equalTo($driverLeave->startOfDay())) {
                if ($driver->leave_shift === 'morning') {
                    $canDoEvening = false;
                }
            }

            // Route service_type kontrolü
            if ($route->service_type === 'morning') {
                $canDoEvening = false;
            }
            if ($route->service_type === 'evening') {
                $canDoMorning = false;
            }

            // --- BACAĞI KİM SÜRDÜ KONTROLÜ ---
            $driverDroveMorning = false;
            $driverDroveEvening = false;

            // Trip'te araç ID'si yoksa (eski kayıtlar), güzergahın varsayılan aracını kullan
            $effectiveMorningVehicleOnTrip = $trip->morning_vehicle_id ?: $route->morning_vehicle_id;
            $effectiveEveningVehicleOnTrip = $trip->evening_vehicle_id ?: $route->evening_vehicle_id;

            // O gün şoförün hangi araçta olduğunu zimmet geçmişinden bul
            $vehicleIdOnTripDate = $this->resolveVehicleIdOnDate($driver, $tripDate, $assignmentHistory);

            // Yeni Yapı: Farklı Şoför (Sabah/Akşam) manuel seçildiyse:
            // Bu en öncelikli kontroldür, araç eşleşmesinin önüne geçer.
            if ($trip->morning_driver_id) {
                // Manuel sabah şoförü atanmış: sadece O şoför sürmüş sayılır
                $driverDroveMorning = ((int)$trip->morning_driver_id === (int)$driver->id);
            } elseif ($vehicleIdOnTripDate && $effectiveMorningVehicleOnTrip) {
                // Manuel atama yoksa, o güne ait araç eşleşmesine bak
                $driverDroveMorning = ((int)$effectiveMorningVehicleOnTrip === (int)$vehicleIdOnTripDate);
            }
            
            if ($trip->evening_driver_id) {
                // Manuel akşam şoförü atanmış: sadece O şoför sürmüş sayılır
                $driverDroveEvening = ((int)$trip->evening_driver_id === (int)$driver->id);
            } elseif ($vehicleIdOnTripDate && $effectiveEveningVehicleOnTrip) {
                // Manuel atama yoksa, o güne ait araç eşleşmesine bak
                $driverDroveEvening = ((int)$effectiveEveningVehicleOnTrip === (int)$vehicleIdOnTripDate);
            }

            if (!$driverDroveMorning) $canDoMorning = false;
            if (!$driverDroveEvening) $canDoEvening = false;

            $morningEarning = 0;
            $eveningEarning = 0;

            // Ücret Hesaplama Mantığı (Ek Hakedişler)
            if ($route->fee_type === 'paid') {
                $morningEarning = $canDoMorning ? ($route->morning_fee ?? 0) : 0;
                $eveningEarning = $canDoEvening ? ($route->evening_fee ?? 0) : 0;
            } else {
                // Sabah için ekstra ücret durumu: Farklı bir araç gittiyse VEYA farklı bir şoför manuel seçildiyse
                $isMorningDiff = ($effectiveMorningVehicleOnTrip && (int)$effectiveMorningVehicleOnTrip !== (int)($route->morning_vehicle_id ?? 0)) || !empty($trip->morning_driver_id);
                // Akşam için ekstra ücret durumu
                $isEveningDiff = ($effectiveEveningVehicleOnTrip && (int)$effectiveEveningVehicleOnTrip !== (int)($route->evening_vehicle_id ?? 0)) || !empty($trip->evening_driver_id);

                if ($canDoMorning && $isMorningDiff) {
                    $morningEarning = $route->fallback_morning_fee ?? 0;
                }
                if ($canDoEvening && $isEveningDiff) {
                    $eveningEarning = $route->fallback_evening_fee ?? 0;
                }
            }

            // Hafta sonu kontrolleri...
            if ($trip->trip_date->isSaturday()) {
                if ($route->saturday_pricing) {
                    if ($canDoMorning && $morningEarning == 0) $morningEarning = $route->fallback_morning_fee ?? 0;
                    if ($canDoEvening && $eveningEarning == 0) $eveningEarning = $route->fallback_evening_fee ?? 0;
                } else {
                    $morningEarning = 0;
                    $eveningEarning = 0;
                }
            }
            if ($trip->trip_date->isSunday()) {
                if ($route->sunday_pricing) {
                    if ($canDoMorning && $morningEarning == 0) $morningEarning = $route->fallback_morning_fee ?? 0;
                    if ($canDoEvening && $eveningEarning == 0) $eveningEarning = $route->fallback_evening_fee ?? 0;
                } else {
                    $morningEarning = 0;
                    $eveningEarning = 0;
                }
            }

            // KABALA (Sabit Maaş) Kontrolü: Şoför sabit maaşlıysa ek hakedişleri sıfırla
            if ($driver->is_fixed_salary) {
                $morningEarning = 0;
                $eveningEarning = 0;
            }

            $tripTotal = round($morningEarning + $eveningEarning, 2);
            
            // Eğer hakediş varsa VEYA şoför sabit maaşlı olup sefere gerçekten çıktıysa (canDoMorning/Evening true ise) rapora ekle
            if ($tripTotal > 0 || ($driver->is_fixed_salary && ($canDoMorning || $canDoEvening))) {
                $routeKey = $route->id;
                if (!isset($groupedDetails[$routeKey])) {
                    $groupedDetails[$routeKey] = [
                        'customer_name' => $route->customer?->company_name ?? 'Bilinmeyen Müşteri',
                        'route_name' => $route->route_name,
                        'morning_count' => 0,
                        'evening_count' => 0,
                        'total_fee' => 0,
                        'dates' => []
                    ];
                }

                if ($morningEarning > 0) $groupedDetails[$routeKey]['morning_count']++;
                if ($eveningEarning > 0) $groupedDetails[$routeKey]['evening_count']++;
                
                $groupedDetails[$routeKey]['total_fee'] = round($groupedDetails[$routeKey]['total_fee'] + $tripTotal, 2);
                $groupedDetails[$routeKey]['dates'][] = [
                    'date' => $trip->trip_date->translatedFormat('d.m.Y l'),
                    'morning' => $morningEarning,
                    'evening' => $eveningEarning,
                    'total' => $tripTotal
                ];
                
                $totalExtraEarnings = round($totalExtraEarnings + $tripTotal, 2);
            }
        }

        $baseSalary = (float)($driver->base_salary ?? 0);
        $actualBaseSalary = round(($baseSalary / 30) * $workDays, 2);

        // Araç geçmişi bilgisini rapora ekle
        $vehicleHistory = $this->buildVehicleHistoryForReport($driver, $startDate, $endDate, $assignmentHistory);

        return [
            'base_salary' => $actualBaseSalary,
            'original_base_salary' => $baseSalary,
            'work_days' => $workDays,
            'extra_earnings' => $totalExtraEarnings,
            'net_salary' => round($actualBaseSalary + $totalExtraEarnings, 2),
            'details' => array_values($groupedDetails),
            'vehicle_history' => $vehicleHistory,
        ];
    }

    /**
     * Şoförün bir dönemdeki tüm araç ID'lerini zimmet geçmişinden çöz.
     * Zimmet geçmişi yoksa mevcut vehicle_id'ye ve son trip'e fall back eder.
     *
     * @return array<int>
     */
    private function resolveVehicleIdsForPeriod(Driver $driver, Carbon $startDate, Carbon $endDate): array
    {
        // Zimmet geçmişi tablosundan o dönemdeki tüm araçları bul
        $assignments = DriverVehicleAssignment::withoutGlobalScopes()
            ->where('driver_id', $driver->id)
            ->activeInRange($startDate, $endDate)
            ->pluck('vehicle_id')
            ->unique()
            ->all();

        if (!empty($assignments)) {
            return $assignments;
        }

        // Fallback: Zimmet geçmişi yoksa mevcut vehicle_id
        $vehicleIds = [];
        if ($driver->vehicle_id) {
            $vehicleIds[] = $driver->vehicle_id;
        }

        // Hala yoksa son trip'ten bulmaya çalış
        if (empty($vehicleIds)) {
            $lastTrip = Trip::where('driver_id', $driver->id)
                ->whereNotNull('vehicle_id')
                ->orderBy('trip_date', 'desc')
                ->first();
            if ($lastTrip) {
                $vehicleIds[] = $lastTrip->vehicle_id;
            }
        }

        return $vehicleIds;
    }

    /**
     * Belirli bir tarihte şoförün hangi araçta olduğunu zimmet geçmişinden çöz.
     * Performans için önceden yüklenmiş assignment collection'ı kullanır.
     */
    private function resolveVehicleIdOnDate(Driver $driver, Carbon $date, $assignmentHistory): ?int
    {
        $dateStr = $date->toDateString();

        // Önbelleğe alınmış zimmet geçmişinden bul
        foreach ($assignmentHistory as $assignment) {
            $assignedStr = $assignment->assigned_at->toDateString();
            $unassignedStr = $assignment->unassigned_at ? $assignment->unassigned_at->toDateString() : null;

            if ($assignedStr <= $dateStr && ($unassignedStr === null || $unassignedStr >= $dateStr)) {
                return $assignment->vehicle_id;
            }
        }

        // Fallback: Zimmet geçmişi yoksa mevcut vehicle_id
        return $driver->vehicle_id;
    }

    /**
     * Maaş raporu için araç geçmişi bilgisi oluştur.
     * "42 C 0123 → 01.06-15.06 (15 gün)" formatında.
     */
    private function buildVehicleHistoryForReport(Driver $driver, Carbon $startDate, Carbon $endDate, $assignmentHistory): array
    {
        $history = [];

        if ($assignmentHistory->isEmpty()) {
            // Zimmet geçmişi yoksa mevcut aracı göster
            if ($driver->vehicle_id) {
                $vehicle = \App\Models\Fleet\Vehicle::withoutGlobalScopes()->find($driver->vehicle_id);
                if ($vehicle) {
                    $history[] = [
                        'vehicle_plate' => $vehicle->plate,
                        'vehicle_id' => $vehicle->id,
                        'start' => $startDate->format('d.m.Y'),
                        'end' => $endDate->format('d.m.Y'),
                        'days' => 'Tüm ay',
                    ];
                }
            }
            return $history;
        }

        foreach ($assignmentHistory as $assignment) {
            $vehicle = \App\Models\Fleet\Vehicle::withoutGlobalScopes()->find($assignment->vehicle_id);
            if (!$vehicle) continue;

            $aStart = $assignment->assigned_at->gt($startDate) ? $assignment->assigned_at : $startDate;
            $aEnd = $assignment->unassigned_at
                ? ($assignment->unassigned_at->lt($endDate) ? $assignment->unassigned_at : $endDate)
                : $endDate;

            $dayCount = $aStart->diffInDays($aEnd) + 1;

            $history[] = [
                'vehicle_plate' => $vehicle->plate,
                'vehicle_id' => $vehicle->id,
                'start' => $aStart->format('d.m.Y'),
                'end' => $aEnd->format('d.m.Y'),
                'days' => $dayCount . ' gün',
            ];
        }

        return $history;
    }
}
