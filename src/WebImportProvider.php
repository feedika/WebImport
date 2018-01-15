<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class WebImportProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
//        $this->loadViewsFrom(__DIR__.'/path/to/views', 'courier');
        $this->loadMigrationsFrom(__DIR__.'/migrations');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
