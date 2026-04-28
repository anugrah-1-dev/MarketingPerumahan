<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Setting;
use App\Observers\UserObserver;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Set Carbon locale sesuai app locale (id = Indonesia)
        Carbon::setLocale(config('app.locale'));

        // Auto-generate referral_code saat user baru dibuat
        User::observe(UserObserver::class);

        RateLimiter::for('login', function (Request $request) {
            $email = Str::lower((string) $request->input('email'));

            return Limit::perMinute(5)->by($email.'|'.$request->ip());
        });

        RateLimiter::for('tracking', function (Request $request) {
            return Limit::perMinute(30)->by($request->ip());
        });

        // Bagikan nomor WA admin ke semua view yang memakai layout app
        View::composer('layouts.app', function ($view) {
            $view->with('navWa', Setting::get('wa_admin', '6283876766055'));
        });
    }
}
