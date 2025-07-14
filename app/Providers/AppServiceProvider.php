<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\UrlGenerator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function boot()
    {
        // paksa scheme & host dari env
        if (env('APP_URL')) {
            app('url')->forceRootUrl(env('APP_URL'));
            app('url')->forceScheme('https');
        }
    }
}
