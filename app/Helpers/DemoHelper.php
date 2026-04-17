<?php

namespace App\Helpers;

class DemoHelper
{
    /**
     * Demo account emails that should be protected from destructive actions.
     */
    public const DEMO_EMAILS = [
        'admin@admin.com',
        'andi@JagoEvent.com',
        'siti@JagoEvent.com',
        'budi@JagoEvent.com',
        'dewi@JagoEvent.com',
        'rizky@JagoEvent.com',
        'mega@gmail.com',
        'fajar@gmail.com',
        'anisa@gmail.com',
        'rendi@gmail.com',
        'tari@gmail.com',
    ];

    public static function isDemoAccount(?string $email = null): bool
    {
        $email = $email ?? auth()->user()?->email;
        return in_array($email, self::DEMO_EMAILS);
    }
}
