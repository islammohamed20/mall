<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class MallShop extends Model
{
    use SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'floor',
        'number',
        'owner_name',
        'owner_phone',
        'owner_id_number',
        'tenant_name',
        'tenant_phone',
        'tenant_id_number',
        'sale_value',
        'rent_value',
        'lease_years',
        'lease_start_date',
        'lease_end_date',
        'notes',
    ];

    protected $casts = [
        'id' => 'string',
        'number' => 'integer',
        'sale_value' => 'decimal:2',
        'rent_value' => 'decimal:2',
        'lease_years' => 'integer',
        'lease_start_date' => 'date',
        'lease_end_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }
}
