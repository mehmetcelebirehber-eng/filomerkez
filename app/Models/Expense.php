<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\BelongsToCompany;

class Expense extends Model
{
    use HasFactory, SoftDeletes, BelongsToCompany;

    protected $fillable = [
        'company_id',
        'vehicle_id',
        'type',
        'amount',
        'date',
        'description',
        'receipt_path',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Fleet\Vehicle::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Gider türleri ve Türkçeleri
    public static function getTypes()
    {
        return [
            'insurance' => 'Sigorta / Kasko',
            'maintenance' => 'Bakım / Sanayi',
            'tires' => 'Lastik',
            'electric' => 'Elektrikçi',
            'bodywork' => 'Kaporta / Boya',
            'fuel' => 'Yakıt',
            'tax' => 'Vergi / Ceza',
            'other' => 'Diğer',
        ];
    }

    public function getTypeNameAttribute()
    {
        return self::getTypes()[$this->type] ?? 'Bilinmeyen';
    }
}
