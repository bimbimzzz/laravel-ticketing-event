<?php

return [
    'secret_key' => env('XENDIT_SECRET_KEY'),
    'public_key' => env('XENDIT_PUBLIC_KEY'),
    'webhook_token' => env('XENDIT_WEBHOOK_TOKEN'),
    'is_production' => env('XENDIT_IS_PRODUCTION', false),
    'invoice_duration' => env('XENDIT_INVOICE_DURATION', 3600),
    'currency' => env('XENDIT_CURRENCY', 'IDR'),
];
