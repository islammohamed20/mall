<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShopImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'image',
        'alt_ar',
        'alt_en',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    /**
     * Get localized alt text
     */
    public function getAltAttribute(): ?string
    {
        return app()->getLocale() === 'ar' ? $this->alt_ar : $this->alt_en;
    }

    /**
     * Get the shop
     */
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Get image URL
     */
    public function getImageUrlAttribute(): string
    {
        return asset('storage/'.$this->image);
    }
}
