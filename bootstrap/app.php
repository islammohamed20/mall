<?php

use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\TrackVisit;
use App\Http\Middleware\Cors;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withSchedule(function (Schedule $schedule): void {
        $schedule->command('facebook:sync-posts')->hourly();
    })
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(SecurityHeaders::class);

        $middleware->appendToGroup('web', [
            SetLocale::class,
            TrackVisit::class,
        ]);

        $middleware->appendToGroup('api', [
            Cors::class,
        ]);

        $middleware->alias([
            'admin' => EnsureAdmin::class,
            'cors' => Cors::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
