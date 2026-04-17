<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Override Faker binding agar tidak error saat --no-dev di production
        if (!class_exists(\Faker\Factory::class)) {
            $this->app->singleton(\Faker\Generator::class, function () {
                throw new \RuntimeException('Faker is not installed. Run: composer install (with dev dependencies).');
            });
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Rate limit: register — max 3 per IP per hour
        RateLimiter::for('register', function (Request $request) {
            return Limit::perHour(3)->by($request->ip())
                ->response(function (Request $request, array $headers) {
                    if ($request->expectsJson()) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Terlalu banyak percobaan registrasi. Coba lagi nanti.',
                        ], 429, $headers);
                    }
                    return back()->withErrors(['email' => 'Terlalu banyak percobaan registrasi. Coba lagi nanti.']);
                });
        });

        // Rate limit: login — max 5 per IP per minute
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip())
                ->response(function (Request $request, array $headers) {
                    if ($request->expectsJson()) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Terlalu banyak percobaan login. Coba lagi dalam 1 menit.',
                        ], 429, $headers);
                    }
                    return back()->withErrors(['email' => 'Terlalu banyak percobaan login. Coba lagi dalam 1 menit.']);
                });
        });

        // Rate limit: vendor register — max 2 per IP per hour
        RateLimiter::for('vendor-register', function (Request $request) {
            return Limit::perHour(2)->by($request->ip())
                ->response(function (Request $request, array $headers) {
                    if ($request->expectsJson()) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Terlalu banyak percobaan registrasi vendor. Coba lagi nanti.',
                        ], 429, $headers);
                    }
                    return back()->withErrors(['name' => 'Terlalu banyak percobaan registrasi vendor. Coba lagi nanti.']);
                });
        });
    }
}
