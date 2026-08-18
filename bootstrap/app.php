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
        /*
         * Trust the hosting proxy's forwarded headers.
         *
         * SSL is terminated in front of PHP on this host, so without this
         * Laravel sees plain HTTP even though the browser is on HTTPS. Every
         * asset() and every signed document URL was then emitted as
         * http://ourhousehelp.com/..., which browsers block as mixed content
         * on an HTTPS page - profile photos, ID cards and guarantor documents
         * all silently failed to render.
         */
        $middleware->trustProxies(at: '*');

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
 * Two deployment layouts are in play:
 *
 *   1. Laravel's default - the document root is <base>/public.
 *   2. This project's production host - the whole application sits INSIDE the
 *      document root, so index.php and build/ are at <base> itself and
 *      <base>/public is a leftover directory nothing serves.
 *
 * Under layout 2, public_path() resolved to <base>/public, so every upload was
 * written where no URL could reach it: new profile photos, ID cards and
 * receipts all 404'd while older files kept working.
 *
 * Detecting the layout rather than hardcoding a path keeps local development
 * on the framework default. A sibling public_html/ is checked first for hosts
 * that keep the application outside the document root.
 */
$documentRoot = null;

if (is_dir($app->basePath('public_html'))) {
    // Application lives beside the document root.
    $documentRoot = $app->basePath('public_html');
} elseif (is_file($app->basePath('index.php')) && is_dir($app->basePath('build'))) {
    // Application lives inside the document root.
    $documentRoot = $app->basePath();
}

if ($documentRoot !== null) {
    $app->usePublicPath($documentRoot);
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
