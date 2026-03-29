<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slider extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title_ar',
        'title_en',
        'subtitle_ar',
        'subtitle_en',
        'cta_text_ar',
        'cta_text_en',
        'cta_link',
        'cta_text_2_ar',
        'cta_text_2_en',
        'cta_link_2',
        'image',
        'image_mobile',
        'sort_order',
        'is_active',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get localized title
     */
    public function getTitleAttribute(): ?string
    {
        return app()->getLocale() === 'ar' ? $this->title_ar : $this->title_en;
    }

    /**
     * Get localized subtitle
     */
    public function getSubtitleAttribute(): ?string
    {
        return app()->getLocale() === 'ar' ? $this->subtitle_ar : $this->subtitle_en;
    }

    /**
     * Get localized CTA text
     */
    public function getCtaTextAttribute(): ?string
    {
        return app()->getLocale() === 'ar' ? $this->cta_text_ar : $this->cta_text_en;
    }

    /**
     * Get localized CTA 2 text
     */
    public function getCtaText2Attribute(): ?string
    {
        return app()->getLocale() === 'ar' ? $this->cta_text_2_ar : $this->cta_text_2_en;
    }

    /**
     * Scope for active sliders
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for current sliders (within date range or no date set)
     */
    public function scopeCurrent($query)
    {
        $today = Carbon::today();

        return $query->where(function ($q) use ($today) {
            $q->whereNull('start_date')
                ->orWhere('start_date', '<=', $today);
        })->where(function ($q) use ($today) {
            $q->whereNull('end_date')
                ->orWhere('end_date', '>=', $today);
        });
    }

    /**
     * Scope ordered
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Get image URL
     */
    public function getImageUrlAttribute(): string
    {
        return asset('storage/'.$this->image);
    }

    /**
     * Get mobile image URL
     */
    public function getMobileImageUrlAttribute(): ?string
    {
        return $this->image_mobile ? asset('storage/'.$this->image_mobile) : null;
    }
}
