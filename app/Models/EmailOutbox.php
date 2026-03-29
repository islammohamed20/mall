<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailOutbox extends Model
{
    protected $fillable = [
        'to',
        'cc',
        'bcc',
        'from',
        'subject',
        'message_id',
        'transport',
        'mailable',
        'html_body',
        'text_body',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'to' => 'array',
            'cc' => 'array',
            'bcc' => 'array',
            'from' => 'array',
            'sent_at' => 'datetime',
        ];
    }
}
