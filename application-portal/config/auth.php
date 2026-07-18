<?php

return [

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'administrators',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'administrators',
        ],
        'admin' => [
            'driver' => 'session',
            'provider' => 'administrators',
        ],
    ],

    'providers' => [
        'administrators' => [
            'driver' => 'eloquent',
            'model' => App\Models\Administrator::class,
        ],
    ],

    'passwords' => [
        'administrators' => [
            'provider' => 'administrators',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];