<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar',
        'name_en',
        'icon',
        'type',
        'description_ar',
        'description_en',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function getNameAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->name_ar : $this->name_en;
    }

    public function getDescriptionAttribute(): ?string
    {
        return app()->getLocale() === 'ar' ? $this->description_ar : $this->description_en;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function isPubliclyAvailable(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->type === 'card' && ! config('mall.payments.card_enabled', false)) {
            return false;
        }

        return true;
    }

    public function scopePublicAvailable($query)
    {
        $query->active();

        if (! config('mall.payments.card_enabled', false)) {
            $query->where('type', '!=', 'card');
        }

        return $query;
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}
