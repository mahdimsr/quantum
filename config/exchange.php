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
        ]
    ]

];
