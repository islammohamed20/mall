<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

class SecurityEvent extends Model
{
    protected $fillable = [
        'type',
        'user_id',
        'email',
        'ip',
        'user_agent',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'meta' => 'array',
        ];
    }

    public static function record(string $type, ?Request $request = null, array $meta = [], ?int $userId = null, ?string $email = null): void
    {
        try {
            $event = static::create([
                'type' => $type,
                'user_id' => $userId,
                'email' => $email,
                'ip' => $request?->ip(),
                'user_agent' => $request?->userAgent(),
                'meta' => empty($meta) ? null : $meta,
            ]);

            if (Schema::hasTable('settings')) {
                $monitorEmail = Setting::getValue('admin_monitor_email');
                $shouldEmail = in_array($type, ['login_failed', 'otp_verify_failed', 'admin_login'], true);

                if ($shouldEmail && filled($monitorEmail)) {
                    $subject = "[Security] {$type}";
                    $lines = [
                        "Type: {$type}",
                        "Email: ".($email ?? '-'),
                        "IP: ".($request?->ip() ?? '-'),
                        "Time: ".$event->created_at?->toDateTimeString(),
                    ];

                    if (! empty($meta)) {
                        $lines[] = 'Meta: '.json_encode($meta, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                    }

                    Mail::raw(implode("\n", $lines), function ($m) use ($monitorEmail, $subject) {
                        $m->to($monitorEmail)->subject($subject);
                    });
                }
            }
        } catch (\Throwable $e) {
            // never block the request
        }
    }
}
