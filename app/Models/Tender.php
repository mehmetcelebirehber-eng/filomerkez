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
        'vehicle_details',
    ];

    public function records()
    {
        return $this->hasMany(TenderRecord::class, 'tender_id');
    }
}
