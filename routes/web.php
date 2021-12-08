<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Set locale
Route::get('locale/{locale}', function ($locale) {
    if (!array_key_exists($locale, Config::get('app.locales'))) {
        abort(400);
    }
	Cookie::queue(Cookie::forever('locale', $locale));
	return redirect()->back();
});
// Set Theme
Route::get('theme/{theme}', function ($theme) {
    if (!in_array($theme, Config::get('app.themes'))) {
        abort(400);
    }
	Cookie::queue(Cookie::forever('theme', $theme));
	return redirect()->back();
});

Route::get('/',					function () { return view('dashboard'); });
Route::post('login',			[ AuthController::class, 'login' ])->name('login');
Route::get('logout',			[ AuthController::class, 'logout' ])->name('logout');
Route::middleware(['auth'])->group(function () {
	Route::get('logado',		function () {
		return 'aaaaaaaaaaaaaaaaaaa';
	});
});
