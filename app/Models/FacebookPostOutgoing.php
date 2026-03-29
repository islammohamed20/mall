<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FacebookPostOutgoing extends Model
{
    use HasFactory;

    protected $table = 'facebook_posts_outgoing';

    protected $fillable = [
        'shop_id',
        'user_id',
        'message',
        'image',
        'post_type',
        'link_url',
        'facebook_post_id',
        'facebook_permalink',
        'status',
        'error_message',
        'retry_count',
        'published_at',
        'scheduled_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'scheduled_at' => 'datetime',
            'retry_count' => 'integer',
        ];
    }

    // Status constants
    const STATUS_DRAFT = 'draft';
    const STATUS_PENDING = 'pending';
    const STATUS_PUBLISHING = 'publishing';
    const STATUS_PUBLISHED = 'published';
    const STATUS_FAILED = 'failed';

    // Post type constants
    const TYPE_TEXT = 'text';
    const TYPE_PHOTO = 'photo';
    const TYPE_LINK = 'link';

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    public function scopeForShop($query, $shopId)
    {
        return $query->where('shop_id', $shopId);
    }

    // Helpers
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    public function canRetry(): bool
    {
        return $this->isFailed() && $this->retry_count < 3;
    }

    public function markAsPublishing(): void
    {
        $this->update([
            'status' => self::STATUS_PUBLISHING,
        ]);
    }

    public function markAsPublished(string $facebookPostId, ?string $permalink = null): void
    {
        $this->update([
            'status' => self::STATUS_PUBLISHED,
            'facebook_post_id' => $facebookPostId,
            'facebook_permalink' => $permalink,
            'published_at' => now(),
            'error_message' => null,
        ]);
    }

    public function markAsFailed(string $errorMessage): void
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'error_message' => $errorMessage,
            'retry_count' => $this->retry_count + 1,
        ]);
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }
        
        return asset('storage/' . $this->image);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            self::STATUS_DRAFT => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">مسودة</span>',
            self::STATUS_PENDING => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">في الانتظار</span>',
            self::STATUS_PUBLISHING => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">جاري النشر</span>',
            self::STATUS_PUBLISHED => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">تم النشر</span>',
            self::STATUS_FAILED => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">فشل</span>',
            default => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">غير معروف</span>',
        };
    }
}
