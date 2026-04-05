<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Gallery extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title_ar',
        'title_en',
        'description_ar',
        'description_en',
        'shop_id',
        'cover_image',
        'is_featured',
        'is_active',
        'sort_order',
        'slug',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($gallery) {
            if (empty($gallery->slug)) {
                $gallery->slug = Str::slug($gallery->title_en ?: $gallery->title_ar);
            }
        });

        static::updating(function ($gallery) {
            if (empty($gallery->slug)) {
                $gallery->slug = Str::slug($gallery->title_en ?: $gallery->title_ar);
            }
        });
    }

    /**
     * Get the shop that owns the gallery.
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Get all gallery items.
     */
    public function items()
    {
        return $this->hasMany(GalleryItem::class)->orderBy('sort_order');
    }

    /**
     * Get the gallery's cover image URL.
     */
    public function getCoverImageUrlAttribute()
    {
        return $this->cover_image ? asset('storage/' . $this->cover_image) : null;
    }

    /**
     * Scope a query to only include active galleries.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include featured galleries.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
