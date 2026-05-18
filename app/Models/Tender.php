<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\BelongsToCompany;
use App\Models\Concerns\LogsActivity;

class Tender extends Model
{
    use HasFactory, SoftDeletes, BelongsToCompany, LogsActivity;

    protected $fillable = [
        'company_id',
        'institution_name',
        'tender_date',
        'tender_registration_number',
        'vehicle_details',
        'duration_days',
        'approximate_cost',
        'our_bid',
        'winning_company',
        'winning_amount',
        'status',
        'document_path',
        'notes',
    ];

    protected $casts = [
        'tender_date' => 'date',
        'approximate_cost' => 'decimal:2',
        'our_bid' => 'decimal:2',
        'winning_amount' => 'decimal:2',
    ];
}
