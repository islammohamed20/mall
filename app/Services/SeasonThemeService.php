<?php

namespace App\Services;

use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class SeasonThemeService
{
    public const SETTING_KEY = 'site_theme_active';

    /**
     * @return array<string, array>
     */
    public function themes(): array
    {
        return [
            // ── Islamic (Hijri) ──────────────────────────
            'ramadan' => [
                'name_ar' => 'رمضان كريم 🌙',
                'name_en' => 'Ramadan Kareem 🌙',
                'category' => 'islamic',
                'emoji' => '🌙',
                'type' => 'hijri_range',
                'hijri' => ['month' => 9, 'start_day' => 1, 'end_day' => 30],
                'class' => 'season-theme season-theme-ramadan',
                'greeting_ar' => 'رمضان كريم! كل عام وأنتم بخير 🌙',
                'greeting_en' => 'Ramadan Kareem! Wishing you a blessed month 🌙',
                'banner_colors' => 'from-teal-600 to-emerald-500',
            ],
            'eid_fitr' => [
                'name_ar' => 'عيد الفطر المبارك 🎉',
                'name_en' => 'Eid Al-Fitr 🎉',
                'category' => 'islamic',
                'emoji' => '🎉',
                'type' => 'hijri_range',
                'hijri' => ['month' => 10, 'start_day' => 1, 'end_day' => 3],
                'class' => 'season-theme season-theme-eid-fitr',
                'greeting_ar' => 'عيد فطر سعيد! كل عام وأنتم بخير 🎉✨',
                'greeting_en' => 'Happy Eid Al-Fitr! Wishing you joy and blessings 🎉✨',
                'banner_colors' => 'from-emerald-500 to-sky-500',
            ],
            'eid_adha' => [
                'name_ar' => 'عيد الأضحى المبارك 🐑',
                'name_en' => 'Eid Al-Adha 🐑',
                'category' => 'islamic',
                'emoji' => '🐑',
                'type' => 'hijri_range',
                'hijri' => ['month' => 12, 'start_day' => 9, 'end_day' => 13],
                'class' => 'season-theme season-theme-eid-adha',
                'greeting_ar' => 'عيد أضحى مبارك! كل عام وأنتم بخير 🐑🌟',
                'greeting_en' => 'Happy Eid Al-Adha! Blessed celebrations 🐑🌟',
                'banner_colors' => 'from-amber-500 to-orange-500',
            ],
            'hijri_new_year' => [
                'name_ar' => 'رأس السنة الهجرية 🕌',
                'name_en' => 'Islamic New Year 🕌',
                'category' => 'islamic',
                'emoji' => '🕌',
                'type' => 'hijri_range',
                'hijri' => ['month' => 1, 'start_day' => 1, 'end_day' => 3],
                'class' => 'season-theme season-theme-hijri-new-year',
                'greeting_ar' => 'كل عام هجري وأنتم بخير 🕌',
                'greeting_en' => 'Happy Islamic New Year 🕌',
                'banner_colors' => 'from-indigo-600 to-purple-500',
            ],
            'mawlid' => [
                'name_ar' => 'المولد النبوي الشريف 🕋',
                'name_en' => 'Prophet\'s Birthday 🕋',
                'category' => 'islamic',
                'emoji' => '🕋',
                'type' => 'hijri_range',
                'hijri' => ['month' => 3, 'start_day' => 10, 'end_day' => 14],
                'class' => 'season-theme season-theme-mawlid',
                'greeting_ar' => 'كل عام وأنتم بخير بمناسبة المولد النبوي الشريف 🕋💚',
                'greeting_en' => 'Blessed Mawlid! Celebrating the Prophet\'s Birthday 🕋💚',
                'banner_colors' => 'from-green-600 to-emerald-500',
            ],

            // ── Egyptian National ────────────────────────
            'police_day' => [
                'name_ar' => 'عيد الشرطة 🇪🇬',
                'name_en' => 'Police Day 🇪🇬',
                'category' => 'national',
                'emoji' => '🇪🇬',
                'type' => 'gregorian_range',
                'gregorian' => ['start' => '01-25', 'end' => '01-25'],
                'class' => 'season-theme season-theme-national',
                'greeting_ar' => 'تحيا مصر! كل عام وأنتم بخير بمناسبة عيد الشرطة 🇪🇬',
                'greeting_en' => 'Happy Police Day! Long live Egypt 🇪🇬',
                'banner_colors' => 'from-red-600 via-white to-black',
            ],
            'jan25' => [
                'name_ar' => 'ثورة 25 يناير 🇪🇬',
                'name_en' => 'January 25 Revolution 🇪🇬',
                'category' => 'national',
                'emoji' => '🇪🇬',
                'type' => 'gregorian_range',
                'gregorian' => ['start' => '01-25', 'end' => '01-27'],
                'class' => 'season-theme season-theme-national',
                'greeting_ar' => 'ذكرى ثورة 25 يناير المجيدة! تحيا مصر 🇪🇬✊',
                'greeting_en' => 'January 25 Revolution Anniversary! Long live Egypt 🇪🇬✊',
                'banner_colors' => 'from-red-600 via-gray-100 to-gray-900',
            ],
            'sinai_liberation' => [
                'name_ar' => 'عيد تحرير سيناء 🇪🇬',
                'name_en' => 'Sinai Liberation Day 🇪🇬',
                'category' => 'national',
                'emoji' => '🏔️',
                'type' => 'gregorian_range',
                'gregorian' => ['start' => '04-25', 'end' => '04-25'],
                'class' => 'season-theme season-theme-national',
                'greeting_ar' => 'عيد تحرير سيناء! أرض الفيروز 🇪🇬🏔️',
                'greeting_en' => 'Sinai Liberation Day! Land of Turquoise 🇪🇬🏔️',
                'banner_colors' => 'from-red-600 via-yellow-400 to-black',
            ],
            'june30' => [
                'name_ar' => 'ثورة 30 يونيو 🇪🇬',
                'name_en' => 'June 30 Revolution 🇪🇬',
                'category' => 'national',
                'emoji' => '🇪🇬',
                'type' => 'gregorian_range',
                'gregorian' => ['start' => '06-30', 'end' => '07-01'],
                'class' => 'season-theme season-theme-national',
                'greeting_ar' => 'ذكرى ثورة 30 يونيو! إرادة شعب 🇪🇬',
                'greeting_en' => 'June 30 Revolution Anniversary! The will of the people 🇪🇬',
                'banner_colors' => 'from-red-600 via-white to-black',
            ],
            'july23' => [
                'name_ar' => 'ثورة 23 يوليو 🇪🇬',
                'name_en' => 'July 23 Revolution 🇪🇬',
                'category' => 'national',
                'emoji' => '🦅',
                'type' => 'gregorian_range',
                'gregorian' => ['start' => '07-23', 'end' => '07-24'],
                'class' => 'season-theme season-theme-national',
                'greeting_ar' => 'ذكرى ثورة يوليو المجيدة! تحيا مصر 🇪🇬🦅',
                'greeting_en' => 'July 23 Revolution Anniversary! Long live Egypt 🇪🇬🦅',
                'banner_colors' => 'from-red-600 via-white to-black',
            ],
            'october6' => [
                'name_ar' => 'نصر أكتوبر 🇪🇬⚔️',
                'name_en' => 'October Victory 🇪🇬⚔️',
                'category' => 'national',
                'emoji' => '⚔️',
                'type' => 'gregorian_range',
                'gregorian' => ['start' => '10-06', 'end' => '10-07'],
                'class' => 'season-theme season-theme-national season-theme-october6',
                'greeting_ar' => 'ذكرى نصر أكتوبر العظيم! تحيا مصر 🇪🇬⚔️',
                'greeting_en' => 'October Victory Day! Long live Egypt 🇪🇬⚔️',
                'banner_colors' => 'from-red-700 via-amber-400 to-black',
            ],

            // ── Gregorian / International ────────────────
            'new_year' => [
                'name_ar' => 'رأس السنة الميلادية 🎆',
                'name_en' => 'New Year 🎆',
                'category' => 'gregorian',
                'emoji' => '🎆',
                'type' => 'gregorian_range',
                'gregorian' => ['start' => '01-01', 'end' => '01-07'],
                'class' => 'season-theme season-theme-new-year',
                'greeting_ar' => 'كل عام وأنتم بخير! سنة سعيدة 🎆🥳',
                'greeting_en' => 'Happy New Year! Wishing you a wonderful year ahead 🎆🥳',
                'banner_colors' => 'from-violet-600 to-fuchsia-500',
            ],
            'mothers_day' => [
                'name_ar' => 'عيد الأم 💐',
                'name_en' => 'Mother\'s Day 💐',
                'category' => 'gregorian',
                'emoji' => '💐',
                'type' => 'gregorian_range',
                'gregorian' => ['start' => '03-21', 'end' => '03-21'],
                'class' => 'season-theme season-theme-mothers-day',
                'greeting_ar' => 'كل سنة وكل أم بخير! عيد أم سعيد 💐❤️',
                'greeting_en' => 'Happy Mother\'s Day! 💐❤️',
                'banner_colors' => 'from-pink-500 to-rose-400',
            ],
            'valentines' => [
                'name_ar' => 'عيد الحب 💝',
                'name_en' => 'Valentine\'s Day 💝',
                'category' => 'gregorian',
                'emoji' => '💝',
                'type' => 'gregorian_range',
                'gregorian' => ['start' => '02-14', 'end' => '02-14'],
                'class' => 'season-theme season-theme-valentines',
                'greeting_ar' => 'عيد حب سعيد! 💝🌹',
                'greeting_en' => 'Happy Valentine\'s Day! 💝🌹',
                'banner_colors' => 'from-red-500 to-pink-500',
            ],
        ];
    }

    /**
     * @return array{name_ar:string,name_en:string,type:string,class:string}|null
     */
    public function theme(string $key): ?array
    {
        return $this->themes()[$key] ?? null;
    }

    public function activeKey(): string
    {
        return trim((string) Setting::getValue(self::SETTING_KEY, ''));
    }

    public function setActiveKey(?string $key): void
    {
        $key = trim((string) $key);
        if ($key !== '' && ! array_key_exists($key, $this->themes())) {
            return;
        }

        Setting::setValue(self::SETTING_KEY, $key === '' ? '' : $key, $key === '' ? '' : $key, 'appearance', 'text');
    }

    public function activeBodyClass(): string
    {
        $key = $this->activeKey();
        if ($key === '') {
            return '';
        }

        $theme = $this->theme($key);
        return (string) ($theme['class'] ?? '');
    }

    /**
     * Get active theme data for the greeting banner.
     *
     * @return array{key:string,greeting:string,emoji:string,banner_colors:string,category:string}|null
     */
    public function activeBanner(): ?array
    {
        $key = $this->activeKey();
        if ($key === '') {
            return null;
        }

        $theme = $this->theme($key);
        if (! $theme) {
            return null;
        }

        $locale = app()->getLocale();

        return [
            'key' => $key,
            'greeting' => $locale === 'ar' ? (string) ($theme['greeting_ar'] ?? '') : (string) ($theme['greeting_en'] ?? ''),
            'emoji' => (string) ($theme['emoji'] ?? '🎉'),
            'banner_colors' => (string) ($theme['banner_colors'] ?? 'from-primary-500 to-gold-500'),
            'category' => (string) ($theme['category'] ?? 'gregorian'),
        ];
    }

    /**
     * Group themes by category for admin view.
     *
     * @return array<string, string>
     */
    public function categories(): array
    {
        return [
            'islamic' => app()->getLocale() === 'ar' ? '🌙 مناسبات إسلامية (هجرية)' : '🌙 Islamic Occasions (Hijri)',
            'national' => app()->getLocale() === 'ar' ? '🇪🇬 مناسبات وطنية مصرية' : '🇪🇬 Egyptian National Days',
            'gregorian' => app()->getLocale() === 'ar' ? '🎉 مناسبات ميلادية / دولية' : '🎉 Gregorian / International',
        ];
    }

    /**
     * @return array<int, array{key:string,name:string,type:string,is_active:bool,in_season:bool,details:?string,gregorian_range:?string}>
     */
    public function adminList(HijriDateService $hijri): array
    {
        $activeKey = $this->activeKey();
        $locale = app()->getLocale();
        $today = Carbon::today();

        $hijriToday = $hijri->todayHijri();

        $items = [];
        foreach ($this->themes() as $key => $theme) {
            $name = $locale === 'ar' ? (string) $theme['name_ar'] : (string) $theme['name_en'];
            $type = (string) $theme['type'];
            $isActive = $key === $activeKey;

            $inSeason = false;
            $details = null;
            $gregorianRange = null;

            if ($type === 'hijri_range') {
                $range = Arr::get($theme, 'hijri');

                if (is_array($range) && $hijriToday) {
                    $hm = (int) ($range['month'] ?? 0);
                    $startDay = (int) ($range['start_day'] ?? 0);
                    $endDay = (int) ($range['end_day'] ?? 0);

                    $inSeason = ((int) $hijriToday['month'] === $hm)
                        && ((int) $hijriToday['day'] >= $startDay)
                        && ((int) $hijriToday['day'] <= $endDay);

                    $hy = (int) $hijriToday['year'];
                    $gStart = $hijri->hijriToGregorian($hy, $hm, $startDay);
                    $gEnd = $hijri->hijriToGregorian($hy, $hm, $endDay);

                    if ($gStart && $gEnd) {
                        $gregorianRange = $gStart->format('Y-m-d').' → '.$gEnd->format('Y-m-d');
                    }

                    $details = sprintf('Hijri %d/%02d → %d/%02d (HY %d)', $hm, $startDay, $hm, $endDay, $hy);
                }
            } elseif ($type === 'gregorian_range') {
                $g = Arr::get($theme, 'gregorian');
                if (is_array($g)) {
                    $start = (string) ($g['start'] ?? '');
                    $end = (string) ($g['end'] ?? '');

                    if ($start !== '' && $end !== '') {
                        try {
                            $year = (int) $today->year;
                            $startDate = Carbon::createFromFormat('Y-m-d', $year.'-'.$start, config('app.timezone'));
                            $endDate = Carbon::createFromFormat('Y-m-d', $year.'-'.$end, config('app.timezone'));

                            $inSeason = $today->betweenIncluded($startDate, $endDate);
                            $gregorianRange = $startDate->format('Y-m-d').' → '.$endDate->format('Y-m-d');
                        } catch (\Throwable $e) {
                            // ignore malformed date
                        }
                    }
                }
            }

            $items[] = [
                'key' => $key,
                'name' => $name,
                'type' => $type,
                'category' => (string) ($theme['category'] ?? 'gregorian'),
                'emoji' => (string) ($theme['emoji'] ?? '🎉'),
                'is_active' => $isActive,
                'in_season' => $inSeason,
                'details' => $details,
                'gregorian_range' => $gregorianRange,
            ];
        }

        return $items;
    }
}
