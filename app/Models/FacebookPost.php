<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FacebookPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'fb_post_id',
        'message',
        'permalink_url',
        'image_url',
        'posted_at',
        'status',
        'raw_payload',
    ];

    protected $casts = [
        'posted_at' => 'datetime',
        'raw_payload' => 'array',
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
