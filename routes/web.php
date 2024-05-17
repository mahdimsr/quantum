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

use Modules\CCXT\ccxt;

Route::get('/', function () {

    $coinex = new \Modules\CCXT\coinex(array(
                                   'apiKey' => '',
                                   'secret' => '',
                               ));

    dd($coinex->v2_private_get_futures_finished_order(array('market_type' => 'FUTURES')));


    return view('welcome');
});
