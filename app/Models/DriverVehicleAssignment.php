<?php

namespace App\Models;

use App\Models\Company;
use App\Models\Fleet\Driver;
use App\Models\Fleet\Vehicle;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToCompany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class DriverVehicleAssignment extends Model
{
    use BelongsToCompany;

    protected $fillable = [
        'company_id',
        'driver_id',
        'vehicle_id',
        'assigned_at',
        'assigned_shift',
        'unassigned_at',
        'unassigned_shift',
        'notes',
    ];

    protected $casts = [
        'assigned_at' => 'date',
        'unassigned_at' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /**
     * Belirli bir tarihte aktif olan zimmetleri filtreler.
     * assigned_at <= $date AND (unassigned_at IS NULL OR unassigned_at >= $date)
     */
    public function scopeActiveOn(Builder $query, $date): Builder
    {
        $dateStr = $date instanceof Carbon ? $date->toDateString() : $date;

        return $query->where('assigned_at', '<=', $dateStr)
                     ->where(function ($q) use ($dateStr) {
                         $q->whereNull('unassigned_at')
                           ->orWhere('unassigned_at', '>=', $dateStr);
                     });
    }

    /**
     * Belirli bir tarih aralığında en az bir gün aktif olan zimmetleri filtreler.
     * assigned_at <= $endDate AND (unassigned_at IS NULL OR unassigned_at >= $startDate)
     */
    public function scopeActiveInRange(Builder $query, $startDate, $endDate): Builder
    {
        $start = $startDate instanceof Carbon ? $startDate->toDateString() : $startDate;
        $end = $endDate instanceof Carbon ? $endDate->toDateString() : $endDate;

        return $query->where('assigned_at', '<=', $end)
                     ->where(function ($q) use ($start) {
                         $q->whereNull('unassigned_at')
                           ->orWhere('unassigned_at', '>=', $start);
                     });
    }

    /**
     * Şu an aktif olan zimmetleri filtreler (unassigned_at IS NULL).
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNull('unassigned_at');
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    /**
     * Bu zimmet kaydı hala aktif mi?
     */
    public function isActive(): bool
    {
        return is_null($this->unassigned_at);
    }

    /**
     * Bu zimmet kaydını kapat (şoför araçtan ayrıldı).
     */
    public function close($date, $shift = 'full_day'): void
    {
        $this->update([
            'unassigned_at' => $date,
            'unassigned_shift' => $shift,
        ]);
    }
}
