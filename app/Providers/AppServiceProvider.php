<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;

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
    public function boot()
    {
		Paginator::useBootstrap();

        if (Cookie::has('locale')) {
            $locale = Str::afterLast(Crypt::decrypt(Cookie::get('locale'), false), '|');
            Carbon::setLocale($locale);
            App::setLocale($locale);
        }
        if (Cookie::has('theme')) {
            $theme = Str::afterLast(Crypt::decrypt(Cookie::get('theme'), false), '|');
            Config::set('app.theme', $theme);
        }
    }
}
