<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerRouteStop extends Model
{
    protected $fillable = [
        'customer_service_route_id',
        'stop_name',
        'stop_time',
        'stop_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function serviceRoute(): BelongsTo
    {
        return $this->belongsTo(CustomerServiceRoute::class, 'customer_service_route_id');
    }
}
