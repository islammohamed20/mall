<?php

namespace App\Http\Middleware;

use App\Models\Visit;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class TrackVisit
{
    private const VISITOR_COOKIE = 'mall_vid';

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $path = ltrim($request->path(), '/');

        if ($this->shouldSkip($request, $path)) {
            return $next($request);
        }

        $visitorUid = (string) $request->cookie(self::VISITOR_COOKIE);
        if ($visitorUid === '' || ! Str::isUuid($visitorUid)) {
            $visitorUid = (string) Str::uuid();
        }

        $sessionId = $request->hasSession() ? (string) $request->session()->getId() : '';
        $ip = $request->ip();
        $userAgent = (string) $request->userAgent();

        $deviceType = $this->detectDeviceType($userAgent);
        $platform = $this->detectPlatform($userAgent);
        $browser = $this->detectBrowser($userAgent);

        $geo = (array) $request->session()->get('geo', []);

        $throttleKey = sprintf(
            'visit:%s:%s:%s',
            $visitorUid,
            sha1((string) $request->fullUrlWithoutQuery() . '|' . $request->method()),
            now()->format('YmdHi')
        );

        if (! Cache::add($throttleKey, 1, now()->addMinutes(2))) {
            $response = $next($request);
            return $this->attachVisitorCookie($response, $request, $visitorUid);
        }

        try {
            Visit::create([
                'visitor_uid' => $visitorUid,
                'session_id' => $sessionId,
                'user_id' => auth()->id(),
                'ip' => $ip,
                'user_agent' => $userAgent ?: null,
                'method' => $request->method(),
                'path' => '/' . $path,
                'referer' => $request->headers->get('referer'),
                'device_type' => $deviceType,
                'platform' => $platform,
                'browser' => $browser,
                'lat' => isset($geo['lat']) ? (float) $geo['lat'] : null,
                'lng' => isset($geo['lng']) ? (float) $geo['lng'] : null,
                'accuracy_m' => isset($geo['accuracy_m']) ? (float) $geo['accuracy_m'] : null,
                'geo_source' => $geo['source'] ?? null,
                'geo_captured_at' => isset($geo['captured_at']) ? $geo['captured_at'] : null,
            ]);
        } catch (\Throwable $e) {
            // ignore tracking failures
        }

        $response = $next($request);

        return $this->attachVisitorCookie($response, $request, $visitorUid);
    }

    private function attachVisitorCookie(Response $response, Request $request, string $visitorUid): Response
    {
        $existing = (string) $request->cookie(self::VISITOR_COOKIE);
        if ($existing !== '' && $existing === $visitorUid) {
            return $response;
        }

        return $response->withCookie(cookie(
            name: self::VISITOR_COOKIE,
            value: $visitorUid,
            minutes: 60 * 24 * 365,
            path: '/',
            domain: null,
            secure: $request->isSecure(),
            httpOnly: false,
            raw: false,
            sameSite: 'Lax'
        ));
    }

    private function shouldSkip(Request $request, string $path): bool
    {
        if ($path === '') {
            return false;
        }

        if (str_starts_with($path, 'admin')) {
            return true;
        }

        if (str_starts_with($path, '_debugbar')) {
            return true;
        }

        if (str_starts_with($path, 'build') || str_starts_with($path, 'storage')) {
            return true;
        }

        if ($path === 'favicon.ico' || $path === 'robots.txt' || $path === 'up') {
            return true;
        }

        if ($request->isMethod('head')) {
            return true;
        }

        if ($request->expectsJson()) {
            return true;
        }

        return false;
    }

    private function detectDeviceType(string $userAgent): ?string
    {
        $ua = strtolower($userAgent);

        if ($ua === '') {
            return null;
        }

        if (str_contains($ua, 'ipad') || str_contains($ua, 'tablet')) {
            return 'tablet';
        }

        if (str_contains($ua, 'mobi') || str_contains($ua, 'android') || str_contains($ua, 'iphone')) {
            return 'mobile';
        }

        return 'desktop';
    }

    private function detectPlatform(string $userAgent): ?string
    {
        $ua = strtolower($userAgent);

        return match (true) {
            $ua === '' => null,
            str_contains($ua, 'windows') => 'Windows',
            str_contains($ua, 'mac os') || str_contains($ua, 'macintosh') => 'macOS',
            str_contains($ua, 'android') => 'Android',
            str_contains($ua, 'iphone') || str_contains($ua, 'ipad') || str_contains($ua, 'ios') => 'iOS',
            str_contains($ua, 'linux') => 'Linux',
            default => null,
        };
    }

    private function detectBrowser(string $userAgent): ?string
    {
        $ua = strtolower($userAgent);

        return match (true) {
            $ua === '' => null,
            str_contains($ua, 'edg/') => 'Edge',
            str_contains($ua, 'chrome/') && ! str_contains($ua, 'edg/') => 'Chrome',
            str_contains($ua, 'safari/') && ! str_contains($ua, 'chrome/') => 'Safari',
            str_contains($ua, 'firefox/') => 'Firefox',
            str_contains($ua, 'opr/') || str_contains($ua, 'opera') => 'Opera',
            default => null,
        };
    }
}
