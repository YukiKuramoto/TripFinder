<?php

namespace App\Providers;

use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;

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

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
     public function boot(UrlGenerator $url)
     {
         // 以下を追記
         if (in_array(config('app.env'), ['prd', 'stg'], true)) {
           $url->forceScheme('https');
         }
     }
}
