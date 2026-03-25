<?php

namespace App\Providers;

use App\Models\Setting;
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
        if (Schema::hasTable('settings')) {
            $defaults = config('parque');

            config(['parque.name' => Setting::getValue('parque.name', $defaults['name'])]);
            config(['parque.city' => Setting::getValue('parque.city', $defaults['city'])]);
            config(['parque.state' => Setting::getValue('parque.state', $defaults['state'])]);
            config(['parque.contact' => Setting::getValue('parque.contact', $defaults['contact'])]);
        }
    }
}
