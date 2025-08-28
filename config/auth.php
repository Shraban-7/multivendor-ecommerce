<?php

return [
    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        'seller' => [
            'driver' => 'session',
            'provider' => 'sellers',
        ],
        'employee' => [
            'driver' => 'session',
            'provider' => 'seller_employees',
        ],
        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
        'sellers' => [
            'driver' => 'eloquent',
            'model' => App\Models\Seller::class,
        ],
        'seller_employees' => [
            'driver' => 'eloquent',
            'model' => App\Models\SellerEmployee::class,
        ],
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],

        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        'sellers' => [
            'provider' => 'sellers',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        'seller_employees' => [
            'provider' => 'seller_employees',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        'admins' => [
            'provider' => 'admins',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,
];
