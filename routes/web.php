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
    return  redirect('login');
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('coin-market',[HomeController::class,'ajaxdt'])->name('coin-market.ajaxdt');
Route::post('coin-marketAjaxdata',[HomeController::class,'ajaxdata'])->name('coin.ajaxdata');
Route::get('coinMarket',[HomeController::class,'loadIndex'])->name('coin-market.ajaxdata');
Route::resource('market-cap',HomeController::class);



