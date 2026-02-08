<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shop extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'floor_id',
        'name_ar',
        'name_en',
        'slug',
        'description_ar',
        'description_en',
        'floor',
        'unit_number',
        'logo',
        'cover_image',
        'phone',
        'whatsapp',
        'email',
        'owner_email',
        'website',
        'instagram',
        'facebook',
        'facebook_page_id',
        'facebook_page_access_token',
        'twitter',
        'tiktok',
        'snapchat',
        'opening_hours_ar',
        'opening_hours_en',
        'is_open_now',
        'is_featured',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'is_open_now' => 'boolean',
        'sort_order' => 'integer',
        'facebook_page_access_token' => 'encrypted',
    ];

    /**
     * Get localized name
     */
    public function getNameAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->name_ar : $this->name_en;
    }

    /**
     * Get localized description
     */
    public function getDescriptionAttribute(): ?string
    {
        return app()->getLocale() === 'ar' ? $this->description_ar : $this->description_en;
    }

    /**
     * Get localized opening hours
     */
    public function getOpeningHoursAttribute(): ?string
    {
        return app()->getLocale() === 'ar' ? $this->opening_hours_ar : $this->opening_hours_en;
    }

    /**
     * Scope for active shops
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for featured shops
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for ordered shops
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name_ar');
    }

    /**
     * Get the category of the shop
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ShopCategory::class, 'category_id');
    }

    /**
     * Get the floor of the shop
     */
    public function floorRelation(): BelongsTo
    {
        return $this->belongsTo(Floor::class, 'floor_id');
    }

    /**
     * Get all images for the shop
     */
    public function images(): HasMany
    {
        return $this->hasMany(ShopImage::class)->orderBy('sort_order');
    }

    /**
     * Get all offers for the shop
     */
    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class);
    }

    /**
     * Get all events for the shop
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class)->orderBy('sort_order')->orderByDesc('id');
    }

    public function facebookPosts(): HasMany
    {
        return $this->hasMany(FacebookPost::class)->orderByDesc('posted_at')->orderByDesc('id');
    }

    /**
     * Get WhatsApp link
     */
    public function getWhatsappLinkAttribute(): ?string
    {
        if (! $this->whatsapp) {
            return null;
        }
        $number = Setting::normalizeWhatsappPhone($this->whatsapp);
        if (! $number) {
            return null;
        }

        return "https://wa.me/{$number}";
    }

    /**
     * Get logo URL
     */
    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo ? asset('storage/'.$this->logo) : null;
    }

    /**
     * Get cover image URL
     */
    public function getCoverUrlAttribute(): ?string
    {
        return $this->cover_image ? asset('storage/'.$this->cover_image) : null;
    }
}
