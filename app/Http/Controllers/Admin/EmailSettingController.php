<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

class EmailSettingController extends Controller
{
    private const KEYS = [
        'mail_mailer',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_address',
        'mail_from_name',
    ];

    public function edit()
    {
        $settings = Setting::query()
            ->whereIn('key', self::KEYS)
            ->get()
            ->keyBy('key');

        $defaults = [
            'mail_mailer'       => env('MAIL_MAILER', 'smtp'),
            'mail_host'         => env('MAIL_HOST', ''),
            'mail_port'         => env('MAIL_PORT', '587'),
            'mail_username'     => env('MAIL_USERNAME', ''),
            'mail_password'     => env('MAIL_PASSWORD', ''),
            'mail_encryption'   => env('MAIL_ENCRYPTION', 'tls'),
            'mail_from_address' => env('MAIL_FROM_ADDRESS', ''),
            'mail_from_name'    => env('MAIL_FROM_NAME', config('mall.name.en', 'Mall')),
        ];

        $values = [];
        foreach (self::KEYS as $key) {
            $values[$key] = old($key, $settings[$key]->value_en ?? ($defaults[$key] ?? ''));
        }

        return view('admin.email.edit', compact('values'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'mail_mailer'       => ['required', 'string', 'in:smtp,sendmail,log'],
            'mail_host'         => ['nullable', 'string', 'max:255'],
            'mail_port'         => ['nullable', 'string', 'max:10'],
            'mail_username'     => ['nullable', 'string', 'max:255'],
            'mail_password'     => ['nullable', 'string', 'max:255'],
            'mail_encryption'   => ['nullable', 'string', 'in:tls,ssl,null'],
            'mail_from_address' => ['nullable', 'email', 'max:255'],
            'mail_from_name'    => ['nullable', 'string', 'max:255'],
        ]);

        foreach (self::KEYS as $key) {
            $val = $data[$key] ?? '';
            Setting::setValue(
                key: $key,
                valueAr: $val,
                valueEn: $val,
                group: 'mail',
                type: $key === 'mail_password' ? 'password' : 'text'
            );
        }

        return redirect()->route('admin.email.edit')
            ->with('status', app()->getLocale() === 'ar' ? 'تم حفظ إعدادات البريد بنجاح.' : 'Email settings saved.');
    }

    public function sendTest(Request $request)
    {
        $request->validate([
            'test_email' => ['required', 'email', 'max:255'],
        ]);

        // Apply settings dynamically for this request
        $this->applyMailConfig();

        try {
            Mail::raw(
                app()->getLocale() === 'ar'
                    ? 'هذا بريد تجريبي من ' . config('mall.name.ar', 'المول') . '. إعدادات البريد تعمل بنجاح!'
                    : 'This is a test email from ' . config('mall.name.en', 'Mall') . '. Email settings are working correctly!',
                function ($message) use ($request) {
                    $message->to($request->test_email)
                        ->subject(app()->getLocale() === 'ar' ? 'بريد تجريبي' : 'Test Email');
                }
            );

            return redirect()->route('admin.email.edit')
                ->with('status', app()->getLocale() === 'ar'
                    ? 'تم إرسال البريد التجريبي بنجاح إلى ' . $request->test_email
                    : 'Test email sent successfully to ' . $request->test_email);
        } catch (\Exception $e) {
            return redirect()->route('admin.email.edit')
                ->with('error', (app()->getLocale() === 'ar' ? 'فشل إرسال البريد: ' : 'Failed to send email: ') . $e->getMessage());
        }
    }

    private function applyMailConfig(): void
    {
        $mailer = Setting::getValue('mail_mailer', 'smtp') ?: 'smtp';

        Config::set('mail.default', $mailer);
        Config::set("mail.mailers.{$mailer}.host", Setting::getValue('mail_host', ''));
        Config::set("mail.mailers.{$mailer}.port", (int) Setting::getValue('mail_port', '587'));
        Config::set("mail.mailers.{$mailer}.username", Setting::getValue('mail_username', ''));
        Config::set("mail.mailers.{$mailer}.password", Setting::getValue('mail_password', ''));
        Config::set("mail.mailers.{$mailer}.encryption", Setting::getValue('mail_encryption', 'tls'));
        Config::set('mail.from.address', Setting::getValue('mail_from_address', ''));
        Config::set('mail.from.name', Setting::getValue('mail_from_name', config('mall.name.en')));
    }
}
