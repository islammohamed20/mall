<?php

namespace App\Providers;

use App\Models\Event as MallEvent;
use App\Models\Offer;
use App\Models\Unit;
use App\Models\EmailOutbox;
use App\Models\AdminNotification;
use App\Models\Setting;
use App\Services\SeasonThemeService;
use Carbon\Carbon;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email as SymfonyEmail;

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

        // Persist sent emails for Admin Outbox
        $this->registerEmailOutboxListener();

        // Hide empty public sections (offers/events/units)
        $this->registerPublicSectionsComposer();

        // Seasonal theme (manual activation)
        $this->registerSeasonThemeComposer();
    }

    private function registerSeasonThemeComposer(): void
    {
        View::composer(['layouts.app', 'layouts.admin'], function ($view) {
            try {
                $themes = app(SeasonThemeService::class);
                $view->with('seasonThemeKey', $themes->activeKey());
                $view->with('seasonThemeBodyClass', $themes->activeBodyClass());
                $view->with('seasonBanner', $themes->activeBanner());
            } catch (\Throwable $e) {
                $view->with('seasonThemeKey', '');
                $view->with('seasonThemeBodyClass', '');
                $view->with('seasonBanner', null);
            }
        });
    }

    private function registerPublicSectionsComposer(): void
    {
        View::composer(['partials.navbar', 'partials.footer', 'pages.home'], function ($view) {
            $view->with('publicSections', $this->getPublicSectionsFlags());
        });
    }

    /**
     * @return array{offers: bool, events: bool, units: bool}
     */
    private function getPublicSectionsFlags(): array
    {
        return Cache::remember('public_sections_flags', now()->addMinutes(5), function () {
            try {
                $offersVisible = false;
                $eventsVisible = false;
                $unitsVisible = false;

                if (Schema::hasTable('offers')) {
                    $offersVisible = Offer::query()
                        ->active()
                        // current OR upcoming == not expired
                        ->whereDate('end_date', '>=', Carbon::today())
                        ->exists();
                }

                if (Schema::hasTable('events')) {
                    $eventsVisible = MallEvent::query()
                        ->active()
                        ->where(function ($q) {
                            $q->current()->orWhere(function ($q2) {
                                $q2->upcoming();
                            });
                        })
                        ->exists();
                }

                if (Schema::hasTable('units')) {
                    $unitsVisible = Unit::query()->active()->exists();
                }

                return [
                    'offers' => (bool) $offersVisible,
                    'events' => (bool) $eventsVisible,
                    'units' => (bool) $unitsVisible,
                ];
            } catch (\Throwable $e) {
                // Safe default: don't hide navigation if something goes wrong.
                return ['offers' => true, 'events' => true, 'units' => true];
            }
        });
    }

    private function registerEmailOutboxListener(): void
    {
        if (! Schema::hasTable('email_outboxes')) {
            return;
        }

        Event::listen(MessageSent::class, function (MessageSent $event) {
            try {
                if (! Schema::hasTable('email_outboxes')) {
                    return;
                }

                $message = $event->message;
                if (! $message instanceof SymfonyEmail) {
                    return;
                }

                $to = collect($message->getTo())->map(fn (Address $a) => $a->toString())->values()->all();
                $cc = collect($message->getCc())->map(fn (Address $a) => $a->toString())->values()->all();
                $bcc = collect($message->getBcc())->map(fn (Address $a) => $a->toString())->values()->all();
                $from = collect($message->getFrom())->map(fn (Address $a) => $a->toString())->values()->all();

                EmailOutbox::create([
                    'to' => empty($to) ? null : $to,
                    'cc' => empty($cc) ? null : $cc,
                    'bcc' => empty($bcc) ? null : $bcc,
                    'from' => empty($from) ? null : $from,
                    'subject' => $message->getSubject(),
                    'message_id' => method_exists($event->sent, 'getMessageId') ? $event->sent->getMessageId() : null,
                    'transport' => config('mail.default'),
                    'mailable' => $event->data['__laravel_mailable'] ?? null,
                    'html_body' => method_exists($message, 'getHtmlBody') ? $message->getHtmlBody() : null,
                    'text_body' => method_exists($message, 'getTextBody') ? $message->getTextBody() : null,
                    'sent_at' => now(),
                ]);

                if (Schema::hasTable('admin_notifications')) {
                    AdminNotification::create([
                        'type' => 'email.sent',
                        'level' => 'info',
                        'title' => 'Email sent',
                        'body' => trim('To: '.implode(', ', $to)." | Subject: ".(string) $message->getSubject()),
                        'data' => [
                            'to' => $to,
                            'subject' => $message->getSubject(),
                            'mailable' => $event->data['__laravel_mailable'] ?? null,
                        ],
                    ]);
                }
            } catch (\Throwable $e) {
                // Never block sending mail if logging fails.
            }
        });
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
