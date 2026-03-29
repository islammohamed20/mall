<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar',
        'name_en',
        'governorate_ar',
        'governorate_en',
        'shipping_cost',
        'estimated_days',
        'is_active',
        'order',
    ];

    protected $casts = [
        'shipping_cost' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function getNameAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->name_ar : $this->name_en;
    }

    public function getGovernorateAttribute(): ?string
    {
        return app()->getLocale() === 'ar' ? $this->governorate_ar : $this->governorate_en;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('name_ar');
    }
}
