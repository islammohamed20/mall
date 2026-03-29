<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'shop_id',
        'title_ar',
        'title_en',
        'slug',
        'short_ar',
        'short_en',
        'content_ar',
        'content_en',
        'banner_image',
        'start_date',
        'end_date',
        'discount_text_ar',
        'discount_text_en',
        'is_featured',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get localized title
     */
    public function getTitleAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->title_ar : $this->title_en;
    }

    /**
     * Get localized short description
     */
    public function getShortAttribute(): ?string
    {
        return app()->getLocale() === 'ar' ? $this->short_ar : $this->short_en;
    }

    /**
     * Get localized content
     */
    public function getContentAttribute(): ?string
    {
        return app()->getLocale() === 'ar' ? $this->content_ar : $this->content_en;
    }

    /**
     * Get localized discount text
     */
    public function getDiscountTextAttribute(): ?string
    {
        return app()->getLocale() === 'ar' ? $this->discount_text_ar : $this->discount_text_en;
    }

    /**
     * Scope for active offers
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for featured offers
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for current offers (within date range)
     */
    public function scopeCurrent($query)
    {
        $today = Carbon::today();

        return $query->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today);
    }

    /**
     * Scope for upcoming offers
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', Carbon::today());
    }

    /**
     * Scope ordered
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('start_date');
    }

    /**
     * Get the shop
     */
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Check if offer is currently active
     */
    public function getIsCurrentAttribute(): bool
    {
        $today = Carbon::today();

        return $this->is_active &&
               $this->start_date <= $today &&
               $this->end_date >= $today;
    }

    /**
     * Get banner URL
     */
    public function getBannerUrlAttribute(): ?string
    {
        return $this->banner_image ? asset('storage/'.$this->banner_image) : null;
    }

    /**
     * Get days remaining
     */
    public function getDaysRemainingAttribute(): int
    {
        return max(0, Carbon::today()->diffInDays($this->end_date, false));
    }
}
