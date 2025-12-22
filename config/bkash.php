<?php

return [
    'base_url' => env('BKASH_CHECKOUT_URL'),
    'app_key' => env('BKASH_CHECKOUT_URL_APP_KEY'),
    'app_secret' => env('BKASH_CHECKOUT_URL_APP_SECRET'),
    'username' => env('BKASH_CHECKOUT_URL_USER_NAME'),
    'password' => env('BKASH_CHECKOUT_URL_PASSWORD'),
    'callback_url' => 'bkash/callback',
];
