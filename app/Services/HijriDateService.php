<?php

namespace App\Services;

use Carbon\Carbon;

class HijriDateService
{
    private const ISLAMIC_LOCALE = 'en_US@calendar=islamic-umalqura';

    public function isSupported(): bool
    {
        return class_exists(\IntlCalendar::class);
    }

    /**
     * @return array{year:int, month:int, day:int}|null
     */
    public function todayHijri(): ?array
    {
        return $this->gregorianToHijri(Carbon::today());
    }

    /**
     * @return array{year:int, month:int, day:int}|null
     */
    public function gregorianToHijri(Carbon $date): ?array
    {
        if (! $this->isSupported()) {
            return null;
        }

        try {
            $timezone = new \DateTimeZone(config('app.timezone'));
            $cal = \IntlCalendar::createInstance($timezone, self::ISLAMIC_LOCALE);
            $cal->setTime($date->copy()->startOfDay()->getTimestamp() * 1000);

            $year = (int) $cal->get(\IntlCalendar::FIELD_YEAR);
            $month = (int) $cal->get(\IntlCalendar::FIELD_MONTH) + 1;
            $day = (int) $cal->get(\IntlCalendar::FIELD_DAY_OF_MONTH);

            return ['year' => $year, 'month' => $month, 'day' => $day];
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function hijriToGregorian(int $hijriYear, int $hijriMonth, int $hijriDay): ?Carbon
    {
        if (! $this->isSupported()) {
            return null;
        }

        try {
            $timezone = new \DateTimeZone(config('app.timezone'));
            $cal = \IntlCalendar::createInstance($timezone, self::ISLAMIC_LOCALE);

            // Intl months are 0-based.
            $cal->set(\IntlCalendar::FIELD_YEAR, $hijriYear);
            $cal->set(\IntlCalendar::FIELD_MONTH, $hijriMonth - 1);
            $cal->set(\IntlCalendar::FIELD_DAY_OF_MONTH, $hijriDay);
            $cal->set(\IntlCalendar::FIELD_HOUR_OF_DAY, 0);
            $cal->set(\IntlCalendar::FIELD_MINUTE, 0);
            $cal->set(\IntlCalendar::FIELD_SECOND, 0);

            $ms = (int) $cal->getTime();

            return Carbon::createFromTimestampMs($ms)->startOfDay();
        } catch (\Throwable $e) {
            return null;
        }
    }
}
