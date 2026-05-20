<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Concerns\BelongsToCompany;

class CustomerJointVenture extends Model
{
    use BelongsToCompany;

    protected $fillable = [
        'company_id',
        'customer_id',
        'company_name',
        'tax_number',
        'phone',
        'address',
        'access_token',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->access_token)) {
                $model->access_token = \Illuminate\Support\Str::random(40);
            }
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function serviceRoutes()
    {
        return $this->hasMany(CustomerServiceRoute::class, 'joint_venture_id');
    }
}
