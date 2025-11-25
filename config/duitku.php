<?php

return [
    'merchant_code' => env('DUITKU_MERCHANT_CODE'),
    'merchant_key'  => env('DUITKU_MERCHANT_KEY'),

    'sandbox'   => env('DUITKU_SANDBOX', true),
    'sanitized' => env('DUITKU_SANITIZED', false),
    'log'       => env('DUITKU_LOG', false),

    'callback_url' => env('DUITKU_CALLBACK_URL'),
    'return_url'   => env('DUITKU_RETURN_URL'),
];
