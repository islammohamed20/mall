<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = [
        'visitor_uid',
        'session_id',
        'user_id',
        'ip',
        'user_agent',
        'path',
        'referer',
        'method',
        'device_type',
        'platform',
        'browser',
        'lat',
        'lng',
        'accuracy_m',
        'geo_source',
        'geo_captured_at',
    ];

    protected $casts = [
        'lat' => 'float',
        'lng' => 'float',
        'accuracy_m' => 'float',
        'geo_captured_at' => 'datetime',
    ];
}
