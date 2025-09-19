<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->withSchedule(function (Schedule $schedule) {

        $schedule->command('send:scheduled-reminders h-1')->dailyAt('08:00')->timezone('Asia/Jakarta');
        $schedule->command('send:scheduled-reminders h+1')->dailyAt('09:00')->timezone('Asia/Jakarta');

        // Testing Remainder
        // $schedule->command('send:scheduled-reminders h-1')->everyMinute();
        // $schedule->command('send:scheduled-reminders h+1')->everyMinute();
    })

    ->create();
