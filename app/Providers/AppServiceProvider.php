<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home'; // Or whatever your home is

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        // Configure rate limiters (this part is usually present)
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            // API Routes
            Route::middleware('api') // Applies the 'api' middleware group (throttling, etc.)
            ->prefix('api')      // Prefixes all routes in api.php with '/api'
            ->group(base_path('routes/api.php'));

            // Web Routes
            Route::middleware('web') // Applies the 'web' middleware group (sessions, csrf, etc.)
            ->group(base_path('routes/web.php'));
        });
    }
}
