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

    $price = 0.6700;
    $asset = $price * 15;

    $amount = \App\Services\Order\Calculate::maxOrderAmount($price,$asset, 10);

    dd(\App\Services\Exchange\Facade\Exchange::setOrder('FTM-USDT', \App\Services\Exchange\Enums\TypeEnum::LIMIT, \App\Services\Exchange\Enums\SideEnum::BUY, \App\Services\Exchange\Enums\SideEnum::LONG, $amount, $price));

    return view('welcome');
});
