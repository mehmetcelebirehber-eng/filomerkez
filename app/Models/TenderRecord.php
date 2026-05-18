<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\LogsActivity;

class TenderRecord extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'tender_id',
        'tender_date',
        'tender_registration_number',
        'duration_days',
        
        'total_vehicles',
        'minibus_count',
        'midibus_count',
        'bus_count',
        'taxi_count',
        'vehicle_model_requirement',
        
        'approximate_cost',
        'our_bid',
        'winning_company',
        'winning_amount',
        'winning_unit_price',
        'status',
        'bids',
        'document_path',
        'notes',
    ];

    protected $casts = [
        'tender_date' => 'date',
        'approximate_cost' => 'decimal:2',
        'our_bid' => 'decimal:2',
        'winning_amount' => 'decimal:2',
        'winning_unit_price' => 'decimal:2',
        'bids' => 'array',
    ];

    public function tender()
    {
        return $this->belongsTo(Tender::class);
    }
}
