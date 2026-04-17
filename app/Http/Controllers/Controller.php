<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'KarcisDigital Ticketing API',
    description: 'REST API untuk aplikasi KarcisDigital — marketplace tiket event. Digunakan oleh aplikasi mobile Flutter untuk browse events, order tickets, manage vendor, dan validasi tiket.',
    contact: new OA\Contact(
        name: 'KarcisDigital Support',
        email: 'support@KarcisDigital.com'
    )
)]
#[OA\Server(
    url: '/api',
    description: 'KarcisDigital API'
)]
#[OA\SecurityScheme(
    securityScheme: 'sanctum',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT',
    description: 'Masukkan token Sanctum (tanpa prefix Bearer)'
)]
#[OA\Tag(name: 'Authentication', description: 'Register, login, Google OAuth, logout')]
#[OA\Tag(name: 'Events', description: 'Browse events, CRUD event (vendor), event categories')]
#[OA\Tag(name: 'Orders', description: 'Order tiket, riwayat order buyer & vendor')]
#[OA\Tag(name: 'SKUs', description: 'Manage tipe tiket (SKU) per event')]
#[OA\Tag(name: 'Tickets', description: 'Tiket vendor, validasi/redeem tiket')]
#[OA\Tag(name: 'Vendors', description: 'Register vendor, get vendor by user')]
#[OA\Tag(name: 'Webhook', description: 'Xendit payment webhook callback')]
abstract class Controller
{
    //
}
