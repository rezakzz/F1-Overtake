<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        //
    }


    public function boot(): void
    {
        Paginator::useBootstrapFive();

        $host = request()->getHost();

    if (Str::endsWith($host, 'ngrok-free.dev')) {
        URL::forceScheme('https');
    }
    }
}
