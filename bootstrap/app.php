<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use App\Http\Middleware\ForceEnglishLocale;
use App\Http\Middleware\SetFrontendLocale;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(
        function (Middleware $middleware): void {
            // Production is commonly behind Nginx/Apache/Cloudflare where the app
            // receives requests as HTTP from a reverse proxy. Trusting forwarded
            // headers prevents HTTPS / host detection issues (redirect loops,
            // wrong absolute URLs in emails, lost secure cookies).
            $middleware->trustProxies(at: '*');

            $middleware->alias(
                [
                    'frontend.locale' => SetFrontendLocale::class,
                    'backend.locale' => ForceEnglishLocale::class,
                    'role' => RoleMiddleware::class,
                    'permission' => PermissionMiddleware::class,
                    'role_or_permission' => RoleOrPermissionMiddleware::class,
                ]
            );
        }
    )
    ->withExceptions(
        function (Exceptions $exceptions): void {
            //
        }
    )
    ->create();
