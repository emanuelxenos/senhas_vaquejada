<?php

namespace App\Providers;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
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
        Gate::define('manage-settings', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('view-reports', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('manage-cadastros', function (User $user) {
            return $user->isAdmin() || $user->isSecretario();
        });

        Gate::define('update-status', function (User $user) {
            return $user->isAdmin() || $user->isSecretario() || $user->isLocutor();
        });

        if (Schema::hasTable('settings')) {
            $defaults = config('parque');

            config(['parque.name' => Setting::getValue('parque.name', $defaults['name'])]);
            config(['parque.city' => Setting::getValue('parque.city', $defaults['city'])]);
            config(['parque.state' => Setting::getValue('parque.state', $defaults['state'])]);
            config(['parque.contact' => Setting::getValue('parque.contact', $defaults['contact'])]);
        }
    }
}
