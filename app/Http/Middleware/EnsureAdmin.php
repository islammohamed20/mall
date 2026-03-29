<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->is_admin) {
            return redirect()
                ->route('home')
                ->with('status', app()->getLocale() === 'ar' ? 'غير مصرح لك بالدخول إلى لوحة التحكم.' : 'You are not allowed to access the admin panel.');
        }

        return $next($request);
    }
}
