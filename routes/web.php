<?php

use App\Services\Exchange\BingX\BingXService;
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


    $bingxService = app(BingxService::class);

//    dd($bingxService->orders('FTM-USDT'));





    // set order

    $coin = \App\Models\Coin::findByName('FTM');

    $pendingOrder = \App\Services\OrderService::openOrder(
        $coin,
        0.7360,
        \App\Services\Exchange\Enums\TypeEnum::LIMIT,
        \App\Services\Exchange\Enums\SideEnum::SHORT,
    );

    dd($pendingOrder);

    $price = 0.6700;
    $asset = $price * 15;

    $amount = \App\Services\Order\Calculate::maxOrderAmount($price, $asset, 10);

    $target = \App\Services\Exchange\Repository\Target::create(\App\Services\Exchange\Enums\TypeEnum::TAKE_PROFIT->value, $price + 1, $price + 1);
    $stopLose = \App\Services\Exchange\Repository\Target::create(\App\Services\Exchange\Enums\TypeEnum::STOP_MARKET->value, $price - 0.05, $price - 0.05);


    $setOrderResponse = \App\Services\Exchange\Facade\Exchange::setOrder('FTM-USDT', \App\Services\Exchange\Enums\TypeEnum::LIMIT, \App\Services\Exchange\Enums\SideEnum::BUY, \App\Services\Exchange\Enums\SideEnum::LONG, $amount, $price, null, $target, $stopLose);

    dd($setOrderResponse->order());

    return view('welcome');
});
