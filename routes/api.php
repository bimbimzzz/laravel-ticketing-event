<?php

use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\VendorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [App\Http\Controllers\Api\AuthController::class, 'register'])->middleware('throttle:register');

Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login'])->middleware('throttle:login');

Route::post('/login/google', [App\Http\Controllers\Api\AuthController::class, 'loginGoogle'])->middleware('throttle:login');

Route::post('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::delete('/user/delete-account', [App\Http\Controllers\Api\AuthController::class, 'deleteAccount'])->middleware('auth:sanctum');

Route::get('/events', [App\Http\Controllers\Api\EventController::class, 'index']);

Route::get('/event-categories', [App\Http\Controllers\Api\EventController::class, 'categories']);

Route::get('/events/all', [EventController::class, 'getAllEvents'])->middleware('auth:sanctum');

Route::post('/events', [EventController::class, 'create'])->middleware('auth:sanctum');

Route::get('/events/user/{id}', [EventController::class, 'getEventByUser'])->middleware('auth:sanctum');

Route::get('/event/{event_id}', [App\Http\Controllers\Api\EventController::class, 'detail']);

Route::post('/event/update/{event_id}', [App\Http\Controllers\Api\EventController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/event/{event_id}', [App\Http\Controllers\Api\EventController::class, 'delete'])->middleware('auth:sanctum');
Route::post('/order', [App\Http\Controllers\Api\OrderController::class, 'create'])->middleware('auth:sanctum');

Route::get('/orders/user/{id}', [OrderController::class, 'getOrderByUserId'])->middleware('auth:sanctum');

Route::put('/orders/{id}', [OrderController::class, 'updateStatus'])->middleware('auth:sanctum');

Route::post('/orders/{id}/cancel', [OrderController::class, 'cancelOrder'])->middleware('auth:sanctum');

Route::get('/orders/user/{id}/vendor', [OrderController::class, 'getOrderByVendor'])->middleware('auth:sanctum');

Route::get('/orders/user/{id}/vendor/total', [OrderController::class, 'getOrderTotalByVendor'])->middleware('auth:sanctum');

Route::get('/tickets/user/{id}', [TicketController::class, 'getTickeUser'])->middleware('auth:sanctum');

Route::get('/vendors/user/{id}', [VendorController::class, 'getVendorByUser'])->middleware('auth:sanctum');
Route::post('/vendor', [VendorController::class, 'createVendor'])->middleware('auth:sanctum');

Route::get('/skus/user/{id}', [App\Http\Controllers\Api\SkuController::class, 'index'])->middleware('auth:sanctum');

Route::post('/sku', [App\Http\Controllers\Api\SkuController::class, 'store'])->middleware('auth:sanctum');
Route::put('/sku/{id}', [App\Http\Controllers\Api\SkuController::class, 'update'])->middleware('auth:sanctum');

// Route::put('/ticket/{id}', [App\Http\Controllers\Api\TicketController::class, 'updateTicketStatus'])->middleware('auth:sanctum');

Route::post('/check-ticket', [App\Http\Controllers\Api\TicketController::class, 'checkTicketValid']);

Route::post('/tickets/bulk-check', [TicketController::class, 'bulkCheck'])->middleware('auth:sanctum');

Route::post('/promo/apply', [App\Http\Controllers\Api\PromoCodeController::class, 'apply'])->middleware('auth:sanctum');

Route::post('/xendit/webhook', [App\Http\Controllers\Api\XenditWebhookController::class, 'handle']);

Route::get('/payment/finish', function (Illuminate\Http\Request $request) {
    $status = $request->query('status', 'unknown');
    $orderId = $request->query('order_id', '');
    return response()->json([
        'status' => $status,
        'order_id' => $orderId,
        'message' => $status === 'success' ? 'Pembayaran berhasil' : 'Pembayaran gagal',
    ]);
});
