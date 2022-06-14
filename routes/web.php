<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HomeController;

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

Route::get('/', function () {
    return redirect('login');
});


Auth::routes();

Route::post('ajaxdtV3', [HomeController::class, 'ajaxdtV3'])->name('coin-market.reloadDataV3');
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::post('coin-market', [HomeController::class, 'ajaxdt'])->name('coin-market.ajaxdt');
    Route::post('coin-marketAjaxdata', [HomeController::class, 'ajaxdata'])->name('coin.ajaxdata');

    Route::post('globalMetrics', [HomeController::class, 'globalMetrics'])->name('coin-market.globalMetrics');


    Route::post('loadData', [HomeController::class, 'loadData'])->name('coin-market.reloadData');

    // Version 3
    Route::post('ajaxdtV3', [HomeController::class, 'ajaxdtV3'])->name('ajaxdtV3');

    Route::post('loadDataV3', [HomeController::class, 'loadDataV3'])->name('coinmarket.reloadDataV3');

    Route::post('coin-marketajaxdataV3', [HomeController::class, 'ajaxdataV3'])->name('coin.ajaxdataV3');

    Route::get('coinMarket', [HomeController::class, 'loadIndex'])->name('coin-market.ajaxdata');
    Route::resource('market-cap', HomeController::class);

});




