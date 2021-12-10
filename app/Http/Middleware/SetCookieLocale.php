<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;

class SetCookieLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->cookie('locale')) {
            $arrayLocales = split_locales_list($request->server('HTTP_ACCEPT_LANGUAGE'));
            $lastCompare = false;
            do {
                $locale = array_shift($arrayLocales);
            } while (($lastCompare = !array_key_exists($locale, config('app.locales'))) && count($arrayLocales) > 0);
            if ($lastCompare) {
                $locale = config('app.fallback_locale');
            }
            return redirect()->route('setlocale', ['locale' => $locale]);
        }
        return $next($request);
    }
}