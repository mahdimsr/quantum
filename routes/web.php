<?php

use App\Services\Exchange\Enums\ExchangeResolutionEnum;
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

    /**
    0 => 1703242800
    1 => "43733.30" open
    2 => "43685.23" close
    3 => "43733.30" high
    4 => "43661.52" low
    5 => "6.2806" volume
    6 => "274414.217527"
     */

    $now = now()->startOfMinute()->timestamp;
    $twoDaysAgo = now()->startOfMinute()->subHours(6)->timestamp;

    $dateTest = \Illuminate\Support\Carbon::createFromTimestamp('1703242800')->toDateTimeString();

    $response = \App\Services\Exchange\Facade\Exchange::ohlc('BTCUSDT',ExchangeResolutionEnum::EVERY_FIVE_MINUTES,$now,$twoDaysAgo,100);

    $data = [];

    for ($i = 0; $i < $response->count();$i++){
        $candle = $response->ohlc($i);

        $data[] = ['close' => $candle->close()];
    }

    $rsi = \App\Services\Indicator\Facade\Indicator::RSI($data,14);

    dd($rsi);

    return view('welcome');
});
