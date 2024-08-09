<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {

    $bingx = new \App\Services\Exchange\Bingx\BingXService();

    dd($bingx->candles('BTC-USDT',100, '1h'));

    return view('welcome');
});
