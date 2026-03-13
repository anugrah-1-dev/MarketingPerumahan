<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Setting;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Auto-generate referral_code saat user baru dibuat
        User::observe(UserObserver::class);

        // Bagikan nomor WA admin ke semua view yang memakai layout app
        View::composer('layouts.app', function ($view) {
            $view->with('navWa', Setting::get('wa_admin', '6283876766055'));
        });
    }
}
