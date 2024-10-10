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

    $target = \App\Services\Exchange\Repository\Target::create(\App\Services\Exchange\Enums\TypeEnum::TAKE_PROFIT->value,$price +1, $price+1);
    $stopLose = \App\Services\Exchange\Repository\Target::create(\App\Services\Exchange\Enums\TypeEnum::STOP_MARKET->value,$price -0.05, $price -0.05);


    $setOrderResponse =\App\Services\Exchange\Facade\Exchange::setOrder('FTM-USDT', \App\Services\Exchange\Enums\TypeEnum::LIMIT, \App\Services\Exchange\Enums\SideEnum::BUY, \App\Services\Exchange\Enums\SideEnum::LONG, $amount, $price, null, $target, $stopLose);

    dd($setOrderResponse->order());

    return view('welcome');
});
