<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;


$app = Application::configure(basePath: dirname(__DIR__))
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

/*
 * Point public_path() at the directory the web server actually serves.
 *
 * This project is deployed on cPanel-style hosting where the document root is
 * <base>/public_html, while Laravel's default public path is <base>/public.
 * The two were different directories, so every public_path() call - uploads in
 * particular - wrote files somewhere no URL could ever reach them. New profile
 * photos, ID cards and receipts all landed in <base>/public and 404'd.
 *
 * Detecting the directory rather than hardcoding a path keeps local
 * development (which has no public_html) on the framework default.
 */
$publicHtml = $app->basePath('public_html');

if (is_dir($publicHtml)) {
    $app->usePublicPath($publicHtml);
}

// Bind our console kernel over the framework default. It supplies the
// symfony/console >= 7.4 compatibility shim (see App\Console\Artisan) and, as a
// side effect, finally activates the schedule defined in App\Console\Kernel -
// Laravel 11 does not pick that class up on its own.
$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

return $app;
