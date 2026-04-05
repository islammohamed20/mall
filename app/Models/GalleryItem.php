<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'gallery_id',
        'type',
        'file_path',
        'thumbnail_path',
        'title_ar',
        'title_en',
        'description_ar',
        'description_en',
        'sort_order',
        'width',
        'height',
        'video_url',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'sort_order' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
    ];

    /**
     * Get the gallery that owns the item.
     */
    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }

    /**
     * Get the file URL.
     */
    public function getFileUrlAttribute()
    {
        return $this->file_path ? asset('storage/' . $this->file_path) : null;
    }

    /**
     * Get the thumbnail URL.
     */
    public function getThumbnailUrlAttribute()
    {
        return $this->thumbnail_path ? asset('storage/' . $this->thumbnail_path) : $this->file_url;
    }

    /**
     * Check if this is a video item.
     */
    public function isVideo()
    {
        return $this->type === 'video';
    }

    /**
     * Check if this is an image item.
     */
    public function isImage()
    {
        return $this->type === 'image';
    }
}
