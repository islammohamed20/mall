<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name_ar',
        'name_en',
        'slug',
        'icon',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get localized name based on current locale
     */
    public function getNameAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->name_ar : $this->name_en;
    }

    /**
     * Scope for active categories
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered categories
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name_ar');
    }

    /**
     * Get all shops in this category
     */
    public function shops(): HasMany
    {
        return $this->hasMany(Shop::class, 'category_id');
    }

    /**
     * Get active shops count
     */
    public function getActiveShopsCountAttribute(): int
    {
        return $this->shops()->active()->count();
    }

    public function productAttributes(): BelongsToMany
    {
        return $this->belongsToMany(ProductAttribute::class, 'category_product_attribute')
            ->withPivot(['is_required', 'sort_order'])
            ->orderBy('category_product_attribute.sort_order');
    }

    public function getIconSymbolAttribute(): string
    {
        return match ($this->icon) {
            'fashion' => '👗',
            'restaurants' => '🍽️',
            'cafes' => '☕',
            'electronics' => '📱',
            'kids' => '🎈',
            default => '🏬',
        };
    }
}
