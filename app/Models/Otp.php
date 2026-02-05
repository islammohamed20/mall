<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $fillable = [
        'email',
        'code',
        'type',
        'used',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'used' => 'boolean',
            'expires_at' => 'datetime',
        ];
    }

    /**
     * Generate and store a new OTP.
     */
    public static function generate(string $email, string $type = 'register'): self
    {
        // Invalidate old OTPs for this email and type
        static::where('email', $email)
            ->where('type', $type)
            ->where('used', false)
            ->update(['used' => true]);

        return static::create([
            'email' => $email,
            'code' => str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT),
            'type' => $type,
            'used' => false,
            'expires_at' => now()->addMinutes(10),
        ]);
    }

    /**
     * Verify an OTP code.
     */
    public static function verify(string $email, string $code, string $type = 'register'): bool
    {
        $otp = static::where('email', $email)
            ->where('code', $code)
            ->where('type', $type)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (! $otp) {
            return false;
        }

        $otp->update(['used' => true]);

        return true;
    }

    /**
     * Clean up expired OTPs.
     */
    public static function cleanup(): void
    {
        static::where('expires_at', '<', now()->subDay())->delete();
    }
}
