<?php

namespace App\Providers;

use App\Models\User;
use App\Services\RbacService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Define RBAC Gates
        foreach (RbacService::PERMISSIONS as $permission) {
            Gate::define($permission, function (User $user) use ($permission) {
                return RbacService::hasPermission($user, $permission);
            });
        }

        // Rate Limiter for Login
        RateLimiter::for('login', function (Request $request) {
            if (app()->environment('testing') && !$request->boolean('test_rate_limit')) {
                return Limit::none();
            }
            $email = (string) $request->input('email');
            return Limit::perMinute(5)->by(mb_strtolower($email).'|'.$request->ip());
        });

        // Rate Limiter for Forgot Password
        RateLimiter::for('forgot-password', function (Request $request) {
            return Limit::perHour(5)->by($request->ip());
        });

        // Rate Limiter for Reset Password
        RateLimiter::for('reset-password', function (Request $request) {
            return Limit::perHour(5)->by($request->ip());
        });

        // Rate Limiter for Change Password
        RateLimiter::for('change-password', function (Request $request) {
            $userId = $request->user()?->id ?? $request->ip();
            return Limit::perMinutes(15, 5)->by($userId);
        });

        // Rate Limiter for Public Leads Submission
        RateLimiter::for('leads-submission', function (Request $request) {
            if (app()->environment('testing') && !$request->boolean('test_rate_limit')) {
                return Limit::none();
            }
            return Limit::perMinute(5)->by($request->ip());
        });
    }
}
