<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Exchange Service
    |--------------------------------------------------------------------------
    |
    | Exchange service configuration
    |
    */

    'exchanges' => [
        'nobitex' => [
            'base_url' => env('EXCHANGE_NOBITEX_BASEURL', null),
            'auth_token' => env('EXCHANGE_NOBITEX_AUTH_TOKEN', null),
        ],
        'coinex' => [
            'base_url' => env('EXCHANGE_COINEX_BASEURL', null),
            'access_id' => env('EXCHANGE_COINEX_ACCESS_ID', null),
            'secret_key' => env('EXCHANGE_COINEX_SECRET_KEY', null),
        ],
        'bingx' => [
            'api_key' => env('EXCHANGE_BINGX_API_KEY', null),
            'secret_key' => env('EXCHANGE_BINGX_SECRET_KEY', null),
        ],
    ]

];
