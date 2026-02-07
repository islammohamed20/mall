<?php

use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\TrackVisit;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withSchedule(function (Schedule $schedule): void {
        $schedule->command('facebook:sync-posts')->hourly();
    })
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->appendToGroup('web', [
            SetLocale::class,
            TrackVisit::class,
        ]);

        $middleware->alias([
            'admin' => EnsureAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
