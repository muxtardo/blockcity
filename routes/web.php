<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;


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

Route::get('/',				[ WelcomeController::class, 'index' ])->name('welcome');
Route::post('login',		[ AuthController::class, 'login' ])->name('login');
Route::middleware(['auth'])->group(function () {
	Route::get('logout',		[ AuthController::class, 'logout' ])->name('logout');

	Route::prefix('dashboard')->group(function () {
		Route::get('/',			[ DashboardController::class, 'index' ])->name('dashboard');
	});
	Route::post('buyHouse',		[BuildingController::class, 'buyHouse'])->name('buyHouse');

	Route::prefix('inventory')->group(function () {
		Route::get('/',			[ DashboardController::class, 'index' ])->name('inventory');
	});
});

Route::get('readable', function(){
    return (new App\Models\Building())->getRandomBuilding();
});
