<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'group',
        'value_ar',
        'value_en',
        'type',
    ];

    /**
     * Get localized value
     */
    public function getValueAttribute(): ?string
    {
        return app()->getLocale() === 'ar' ? $this->value_ar : $this->value_en;
    }

    /**
     * Get a setting value by key
     */
    public static function getValue(string $key, ?string $default = null, ?string $locale = null): ?string
    {
        if (! Schema::hasTable('settings')) {
            return $default;
        }

        $locale = $locale ?? app()->getLocale();

        // This value is often updated and should reflect changes immediately.
        // Also, it is not language-specific, so we allow fallback between locales.
        if ($key === 'mall_map_embed_url') {
            $setting = static::where('key', $key)->first();
            if (! $setting) {
                return $default;
            }

            $primary = $locale === 'ar' ? $setting->value_ar : $setting->value_en;
            $secondary = $locale === 'ar' ? $setting->value_en : $setting->value_ar;

            $resolved = filled($primary) ? $primary : (filled($secondary) ? $secondary : $default);

            return static::normalizeMapEmbedUrl($resolved) ?? $default;
        }
        $cacheKey = "setting_{$key}_{$locale}";

        return Cache::remember($cacheKey, 3600, function () use ($key, $default, $locale) {
            $setting = static::where('key', $key)->first();
            if (! $setting) {
                return $default;
            }

            $primary = $locale === 'ar' ? $setting->value_ar : $setting->value_en;
            $secondary = $locale === 'ar' ? $setting->value_en : $setting->value_ar;

            return filled($primary) ? $primary : (filled($secondary) ? $secondary : $default);
        });
    }

    private static function normalizeMapEmbedUrl(?string $value): ?string
    {
        if (! filled($value)) {
            return null;
        }

        $value = trim((string) $value);

        // If user pasted a full iframe snippet, extract the src URL.
        if (str_contains($value, '<iframe')) {
            if (preg_match('/src\s*=\s*(["\"])\s*([^"\"]+)\s*\1/i', $value, $matches)) {
                return html_entity_decode($matches[2], ENT_QUOTES);
            }

            return null;
        }

        return $value;
    }

    /**
     * Set a setting value
     */
    public static function setValue(string $key, ?string $valueAr, ?string $valueEn = null, string $group = 'general', string $type = 'text'): void
    {
        if (! Schema::hasTable('settings')) {
            return;
        }

        static::updateOrCreate(
            ['key' => $key],
            [
                'value_ar' => $valueAr,
                'value_en' => $valueEn ?? $valueAr,
                'group' => $group,
                'type' => $type,
            ]
        );

        Cache::forget("setting_{$key}_ar");
        Cache::forget("setting_{$key}_en");
    }

    /**
     * Get all settings by group
     */
    public static function getByGroup(string $group): array
    {
        if (! Schema::hasTable('settings')) {
            return [];
        }

        return static::where('group', $group)
            ->get()
            ->mapWithKeys(fn ($setting) => [$setting->key => $setting])
            ->toArray();
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache(): void
    {
        if (! Schema::hasTable('settings')) {
            return;
        }

        $settings = static::all();
        foreach ($settings as $setting) {
            Cache::forget("setting_{$setting->key}_ar");
            Cache::forget("setting_{$setting->key}_en");
        }
    }
}
