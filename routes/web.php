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
    if (!in_array($locale, [ 'en', 'es', 'br' ])) {
        abort(400);
    }

    App::setLocale($locale);

	Cookie::queue(Cookie::forever('locale', $locale));

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
