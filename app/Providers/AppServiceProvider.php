<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->input('email');

            return Limit::perMinute(5)->by(strtolower($email).'|'.$request->ip());
        });

        // Dynamically configure mail from database settings
        $this->configureMailFromSettings();
    }

    /**
     * Apply mail configuration from database settings.
     */
    private function configureMailFromSettings(): void
    {
        if (! Schema::hasTable('settings')) {
            return;
        }

        try {
            $mailer = Setting::getValue('mail_mailer');
            if (! $mailer) {
                return;
            }

            Config::set('mail.default', $mailer);
            Config::set("mail.mailers.{$mailer}.host", Setting::getValue('mail_host') ?: config("mail.mailers.{$mailer}.host"));
            Config::set("mail.mailers.{$mailer}.port", (int) (Setting::getValue('mail_port') ?: config("mail.mailers.{$mailer}.port")));
            Config::set("mail.mailers.{$mailer}.username", Setting::getValue('mail_username') ?: config("mail.mailers.{$mailer}.username"));
            Config::set("mail.mailers.{$mailer}.password", Setting::getValue('mail_password') ?: config("mail.mailers.{$mailer}.password"));

            $encryption = Setting::getValue('mail_encryption');
            if ($encryption && $encryption !== 'null') {
                Config::set("mail.mailers.{$mailer}.encryption", $encryption);
            }

            $fromAddress = Setting::getValue('mail_from_address');
            $fromName = Setting::getValue('mail_from_name');
            if ($fromAddress) {
                Config::set('mail.from.address', $fromAddress);
            }
            if ($fromName) {
                Config::set('mail.from.name', $fromName);
            }
        } catch (\Exception $e) {
            // Silently fail if settings can't be loaded
        }
    }
}
