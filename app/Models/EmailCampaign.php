<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subject_ar',
        'subject_en',
        'body_ar',
        'body_en',
        'status',
        'recipients_count',
        'sent_count',
        'failed_count',
        'scheduled_at',
        'sent_at',
        'created_by',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function getStatusLabelAttribute(): string
    {
        $labels = [
            'draft' => app()->getLocale() === 'ar' ? 'مسودة' : 'Draft',
            'sending' => app()->getLocale() === 'ar' ? 'جاري الإرسال' : 'Sending',
            'sent' => app()->getLocale() === 'ar' ? 'تم الإرسال' : 'Sent',
            'failed' => app()->getLocale() === 'ar' ? 'فشل' : 'Failed',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function getSubjectAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->subject_ar : $this->subject_en;
    }

    public function getBodyAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->body_ar : $this->body_en;
    }
}
