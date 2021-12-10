<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\BuildingsController;
use App\Http\Controllers\ExchangeController;
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

Route::get('/',			[ WelcomeController::class, 'index' ])->name('welcome');
Route::post('auth',		[ AuthController::class, 'auth' ])->name('auth');
Route::middleware(['auth'])->group(function () {
	Route::get('logout',		[ AuthController::class, 'logout' ])->name('logout');

	// Dashboard
	Route::get('dashboard',	[ DashboardController::class, 'index' ])->name('dashboard');

	// Buildings (list, mint, claim, upgrade, repair)
	Route::prefix('buildings')->group(function () {
		Route::get('/',				[ BuildingsController::class, 'index' ])->name('buildings');

		Route::post('mint',			[ BuildingsController::class, 'mint' ])->name('buildingMint');
		Route::post('claim',		[ BuildingsController::class, 'claim' ])->name('buildingClaim');
		Route::post('upgrade',		[ BuildingsController::class, 'upgrade' ])->name('buildingUpgrade');
		Route::post('repair',		[ BuildingsController::class, 'repair' ])->name('buildingRepair');
	});

	Route::prefix('exchange')->group(function () {
		Route::get('/',				[ ExchangeController::class, 'index' ])->name('exchange');

		Route::post('check',		[ ExchangeController::class, 'check' ])->name('exchangeCheck');
		Route::post('deposit',		[ ExchangeController::class, 'deposit' ])->name('exchangeDeposit');
		Route::post('withdrawal',	[ ExchangeController::class, 'withdrawal' ])->name('exchangeWithdrawal');
	});

	// Route::prefix('inventory')->group(function () {
	// 	Route::get('/',			[ InvetoryController::class, 'index' ])->name('inventory');
	// });
});

Route::get('readable', function(){
    return (new App\Models\Building())->mint();
});
