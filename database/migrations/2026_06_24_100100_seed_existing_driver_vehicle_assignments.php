<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Mevcut drivers tablosundan geriye dönük zimmet geçmişi oluşturur.
     * Her aktif vehicle_id'si olan şoför için bir kayıt oluşturulur.
     * Bu, en azından mevcut durumun korunmasını sağlar.
     */
    public function up(): void
    {
        // Sadece vehicle_id'si olan şoförler için zimmet geçmişi oluştur
        $drivers = DB::table('drivers')
            ->whereNotNull('vehicle_id')
            ->get();

        foreach ($drivers as $driver) {
            // Bu şoför için zaten bir kayıt var mı kontrol et
            $existingAssignment = DB::table('driver_vehicle_assignments')
                ->where('driver_id', $driver->id)
                ->where('vehicle_id', $driver->vehicle_id)
                ->first();

            if ($existingAssignment) {
                continue; // Zaten kayıt var, atla
            }

            // Şoförün bu araçtaki en eski trip tarihini bul (gerçek veri)
            $oldestTrip = DB::table('trips')
                ->where(function ($q) use ($driver) {
                    $q->where('driver_id', $driver->id)
                      ->orWhere('morning_driver_id', $driver->id)
                      ->orWhere('evening_driver_id', $driver->id)
                      ->orWhere(function ($sq) use ($driver) {
                          $sq->where('vehicle_id', $driver->vehicle_id)
                             ->orWhere('morning_vehicle_id', $driver->vehicle_id)
                             ->orWhere('evening_vehicle_id', $driver->vehicle_id);
                      });
                })
                ->orderBy('trip_date', 'asc')
                ->first();

            // Zimmet başlangıç tarihi: start_date > oldest trip date > created_at
            $assignedAt = $driver->start_date
                ?? ($oldestTrip ? $oldestTrip->trip_date : null)
                ?? ($driver->created_at ? Carbon::parse($driver->created_at)->toDateString() : now()->toDateString());

            DB::table('driver_vehicle_assignments')->insert([
                'company_id' => $driver->company_id,
                'driver_id' => $driver->id,
                'vehicle_id' => $driver->vehicle_id,
                'assigned_at' => $assignedAt,
                'assigned_shift' => $driver->start_shift ?? 'morning',
                'unassigned_at' => $driver->is_active ? null : $driver->leave_date,
                'unassigned_shift' => $driver->is_active ? null : ($driver->leave_shift ?? 'full_day'),
                'notes' => 'Otomatik göç: Mevcut araç atamasından oluşturuldu.',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        // Otomatik göç ile oluşturulan kayıtları sil
        DB::table('driver_vehicle_assignments')
            ->where('notes', 'like', 'Otomatik göç%')
            ->delete();
    }
};
