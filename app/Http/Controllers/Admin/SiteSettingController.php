<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiteSettingController extends Controller
{
    private const KEYS = [
        'mall_logo',
        'mall_favicon',
        'mall_name',
        'mall_slogan',
        'mall_contact_phone',
        'mall_contact_whatsapp',
        'mall_contact_email',
        'mall_contact_address',
        'mall_working_hours',
        'mall_map_embed_url',
        'mall_social_facebook',
        'mall_social_instagram',
        'mall_social_twitter',
        'mall_social_tiktok',
        'mall_social_snapchat',
        'mall_stats_shops',
        'mall_stats_restaurants',
        'mall_stats_parking_spots',
        'mall_stats_monthly_visitors',
    ];

    public function edit()
    {
        $settings = Setting::query()
            ->whereIn('key', self::KEYS)
            ->get()
            ->keyBy('key');

        $defaults = [
            'mall_logo' => ['ar' => null, 'en' => null],
            'mall_favicon' => ['ar' => null, 'en' => null],
            'mall_name' => ['ar' => config('mall.name.ar'), 'en' => config('mall.name.en')],
            'mall_slogan' => ['ar' => config('mall.slogan.ar'), 'en' => config('mall.slogan.en')],
            'mall_contact_phone' => ['ar' => config('mall.contact.phone'), 'en' => config('mall.contact.phone')],
            'mall_contact_whatsapp' => ['ar' => config('mall.contact.whatsapp'), 'en' => config('mall.contact.whatsapp')],
            'mall_contact_email' => ['ar' => config('mall.contact.email'), 'en' => config('mall.contact.email')],
            'mall_contact_address' => ['ar' => config('mall.contact.address_ar'), 'en' => config('mall.contact.address_en')],
            'mall_working_hours' => ['ar' => config('mall.working_hours.ar'), 'en' => config('mall.working_hours.en')],
            'mall_map_embed_url' => ['ar' => config('mall.map.embed_url'), 'en' => config('mall.map.embed_url')],
            'mall_social_facebook' => ['ar' => config('mall.social.facebook'), 'en' => config('mall.social.facebook')],
            'mall_social_instagram' => ['ar' => config('mall.social.instagram'), 'en' => config('mall.social.instagram')],
            'mall_social_twitter' => ['ar' => config('mall.social.twitter'), 'en' => config('mall.social.twitter')],
            'mall_social_tiktok' => ['ar' => config('mall.social.tiktok'), 'en' => config('mall.social.tiktok')],
            'mall_social_snapchat' => ['ar' => config('mall.social.snapchat'), 'en' => config('mall.social.snapchat')],
            'mall_stats_shops' => ['ar' => (string) config('mall.stats.shops'), 'en' => (string) config('mall.stats.shops')],
            'mall_stats_restaurants' => ['ar' => (string) config('mall.stats.restaurants'), 'en' => (string) config('mall.stats.restaurants')],
            'mall_stats_parking_spots' => ['ar' => (string) config('mall.stats.parking_spots'), 'en' => (string) config('mall.stats.parking_spots')],
            'mall_stats_monthly_visitors' => ['ar' => (string) config('mall.stats.monthly_visitors'), 'en' => (string) config('mall.stats.monthly_visitors')],
        ];

        $values = [];
        foreach (self::KEYS as $key) {
            $values[$key] = [
                'ar' => old("{$key}.ar", $settings[$key]->value_ar ?? ($defaults[$key]['ar'] ?? null)),
                'en' => old("{$key}.en", $settings[$key]->value_en ?? ($defaults[$key]['en'] ?? null)),
            ];
        }

        return view('admin.settings.edit', compact('values'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'mall_logo' => ['nullable', 'image', 'max:4096'],
            'mall_favicon' => ['nullable', 'file', 'mimes:png,ico', 'max:2048'],
            'mall_name.ar' => ['required', 'string', 'max:255'],
            'mall_name.en' => ['required', 'string', 'max:255'],
            'mall_slogan.ar' => ['nullable', 'string', 'max:255'],
            'mall_slogan.en' => ['nullable', 'string', 'max:255'],
            'mall_contact_phone.ar' => ['nullable', 'string', 'max:255'],
            'mall_contact_phone.en' => ['nullable', 'string', 'max:255'],
            'mall_contact_whatsapp.ar' => ['nullable', 'string', 'max:255'],
            'mall_contact_whatsapp.en' => ['nullable', 'string', 'max:255'],
            'mall_contact_email.ar' => ['nullable', 'email', 'max:255'],
            'mall_contact_email.en' => ['nullable', 'email', 'max:255'],
            'mall_contact_address.ar' => ['nullable', 'string', 'max:2000'],
            'mall_contact_address.en' => ['nullable', 'string', 'max:2000'],
            'mall_working_hours.ar' => ['nullable', 'string', 'max:2000'],
            'mall_working_hours.en' => ['nullable', 'string', 'max:2000'],
            'mall_map_embed_url.ar' => ['nullable', 'string', 'max:2000'],
            'mall_map_embed_url.en' => ['nullable', 'string', 'max:2000'],
            'mall_social_facebook.ar' => ['nullable', 'url', 'max:255'],
            'mall_social_facebook.en' => ['nullable', 'url', 'max:255'],
            'mall_social_instagram.ar' => ['nullable', 'url', 'max:255'],
            'mall_social_instagram.en' => ['nullable', 'url', 'max:255'],
            'mall_social_twitter.ar' => ['nullable', 'url', 'max:255'],
            'mall_social_twitter.en' => ['nullable', 'url', 'max:255'],
            'mall_social_tiktok.ar' => ['nullable', 'url', 'max:255'],
            'mall_social_tiktok.en' => ['nullable', 'url', 'max:255'],
            'mall_social_snapchat.ar' => ['nullable', 'string', 'max:255'],
            'mall_social_snapchat.en' => ['nullable', 'string', 'max:255'],
            'mall_stats_shops.ar' => ['nullable', 'string', 'max:50'],
            'mall_stats_shops.en' => ['nullable', 'string', 'max:50'],
            'mall_stats_restaurants.ar' => ['nullable', 'string', 'max:50'],
            'mall_stats_restaurants.en' => ['nullable', 'string', 'max:50'],
            'mall_stats_parking_spots.ar' => ['nullable', 'string', 'max:50'],
            'mall_stats_parking_spots.en' => ['nullable', 'string', 'max:50'],
            'mall_stats_monthly_visitors.ar' => ['nullable', 'string', 'max:50'],
            'mall_stats_monthly_visitors.en' => ['nullable', 'string', 'max:50'],
        ]);

        $fileKeys = ['mall_logo', 'mall_favicon'];
        foreach (self::KEYS as $key) {
            if (in_array($key, $fileKeys, true)) {
                continue;
            }

            // Note: Laravel's ConvertEmptyStringsToNull middleware turns cleared inputs into null.
            // For site settings, clearing a field should persist as an empty string (not fall back to defaults).
            $valueAr = $data[$key]['ar'] ?? '';
            $valueEn = $data[$key]['en'] ?? '';

            if ($valueAr === null) {
                $valueAr = '';
            }
            if ($valueEn === null) {
                $valueEn = '';
            }

            if ($key === 'mall_map_embed_url') {
                $normalize = function (?string $value): ?string {
                    if (blank($value)) {
                        return null;
                    }

                    $value = trim((string) $value);
                    if (str_contains($value, '<iframe') && preg_match('/src\s*=\s*(["\"])\s*([^"\"]+)\s*\1/i', $value, $m)) {
                        return html_entity_decode($m[2], ENT_QUOTES);
                    }

                    return $value;
                };

                $valueAr = $normalize($valueAr);
                $valueEn = $normalize($valueEn);

                if (blank($valueAr) && filled($valueEn)) {
                    $valueAr = $valueEn;
                }
                if (blank($valueEn) && filled($valueAr)) {
                    $valueEn = $valueAr;
                }
            }

            Setting::setValue(
                key: $key,
                valueAr: $valueAr,
                valueEn: $valueEn,
                group: 'site',
                type: in_array($key, ['mall_contact_address', 'mall_working_hours', 'mall_map_embed_url'], true) ? 'textarea' : 'text'
            );
        }

        if ($request->hasFile('mall_logo')) {
            $old = Setting::query()->where('key', 'mall_logo')->first()?->value_ar;
            $path = $request->file('mall_logo')->store('site', 'public');

            Setting::setValue(key: 'mall_logo', valueAr: $path, valueEn: $path, group: 'site', type: 'image');

            if ($old) {
                Storage::disk('public')->delete($old);
            }
        }

        if ($request->hasFile('mall_favicon')) {
            $old = Setting::query()->where('key', 'mall_favicon')->first()?->value_ar;
            $path = $request->file('mall_favicon')->store('site', 'public');

            Setting::setValue(key: 'mall_favicon', valueAr: $path, valueEn: $path, group: 'site', type: 'image');

            if ($old) {
                Storage::disk('public')->delete($old);
            }
        }

        return redirect()->route('admin.settings.edit')->with('status', 'Saved.');
    }
}
