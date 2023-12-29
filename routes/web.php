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



    $user = \App\Models\User::find(1);

    \Illuminate\Support\Facades\Notification::send($user,new \App\Notifications\SignalNotification('BTC', 'LONG 🟢'));

    return view('welcome');
});
