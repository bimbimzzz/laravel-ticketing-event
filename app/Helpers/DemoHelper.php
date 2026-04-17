<?php

namespace App\Helpers;

class DemoHelper
{
    /**
     * Demo account emails that should be protected from destructive actions.
     */
    public const DEMO_EMAILS = [
        'admin@admin.com',
        'andi@KarcisDigital.com',
        'siti@KarcisDigital.com',
        'budi@KarcisDigital.com',
        'dewi@KarcisDigital.com',
        'rizky@KarcisDigital.com',
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
