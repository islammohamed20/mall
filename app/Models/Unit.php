<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_ar', 'title_en', 'slug', 'description_ar', 'description_en',
        'unit_number', 'floor_id', 'area', 'price', 'price_type', 'currency',
        'status', 'type', 'image', 'features_ar', 'features_en',
        'contact_phone', 'contact_email', 'contact_whatsapp',
        'is_active', 'sort_order',
    ];

    protected $casts = [
        'area' => 'decimal:2',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Accessors
    public function getTitleAttribute(): string
    {
        return app()->getLocale() === 'ar' ? ($this->title_ar ?: $this->title_en) : ($this->title_en ?: $this->title_ar);
    }

    public function getDescriptionAttribute(): ?string
    {
        return app()->getLocale() === 'ar' ? ($this->description_ar ?: $this->description_en) : ($this->description_en ?: $this->description_ar);
    }

    public function getFeaturesAttribute(): ?string
    {
        return app()->getLocale() === 'ar' ? ($this->features_ar ?: $this->features_en) : ($this->features_en ?: $this->features_ar);
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    public function getStatusLabelAttribute(): string
    {
        $labels = [
            'available' => app()->getLocale() === 'ar' ? 'متاح' : 'Available',
            'reserved'  => app()->getLocale() === 'ar' ? 'محجوز' : 'Reserved',
            'sold'      => app()->getLocale() === 'ar' ? 'مُباع' : 'Sold',
            'rented'    => app()->getLocale() === 'ar' ? 'مؤجّر' : 'Rented',
        ];
        return $labels[$this->status] ?? $this->status;
    }

    public function getTypeLabelAttribute(): string
    {
        $labels = [
            'shop'    => app()->getLocale() === 'ar' ? 'محل تجاري' : 'Shop',
            'office'  => app()->getLocale() === 'ar' ? 'مكتب' : 'Office',
            'kiosk'   => app()->getLocale() === 'ar' ? 'كشك' : 'Kiosk',
            'storage' => app()->getLocale() === 'ar' ? 'مخزن' : 'Storage',
        ];
        return $labels[$this->type] ?? $this->type;
    }

    public function getPriceTypeLabelAttribute(): string
    {
        return $this->price_type === 'rent'
            ? (app()->getLocale() === 'ar' ? 'للإيجار' : 'For Rent')
            : (app()->getLocale() === 'ar' ? 'للبيع' : 'For Sale');
    }

    // Relationships
    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }

    // Scopes
    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    public function scopeAvailable($q)
    {
        return $q->where('status', 'available');
    }

    // Boot
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($unit) {
            if (empty($unit->slug)) {
                $unit->slug = Str::slug($unit->title_en ?: $unit->title_ar);
                $count = static::where('slug', $unit->slug)->count();
                if ($count) {
                    $unit->slug .= '-' . ($count + 1);
                }
            }
        });
    }
}
