<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'applicant' => \App\Http\Middleware\ApplicantMiddleware::class,
            'client' => \App\Http\Middleware\ClientMiddleware::class,
            'check.applicant.completion' => \App\Http\Middleware\CheckApplicantCompletion::class,
            'verify.coupon' => \App\Http\Middleware\VerifyCoupon::class,
            'registration.check' => \App\Http\Middleware\CheckRegistration::class,
            'prevent-back' => \App\Http\Middleware\PreventBackHistory::class,
            'check.credits' => \App\Http\Middleware\CheckCredits::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
