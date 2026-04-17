<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\EventController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\VendorController;
use App\Http\Controllers\Web\VendorDashboardController;
use App\Http\Controllers\Web\VendorEventController;
use App\Http\Controllers\Web\VendorSkuController;
use App\Http\Controllers\Web\VendorOrderController;
use App\Http\Controllers\Web\VendorTicketController;
use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\VendorPromoController;
use Illuminate\Support\Facades\Route;

// Landing
Route::get('/', fn() => view('landing'));
Route::get('/design-system', fn() => view('design-system'));

// Guest only
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:register');
    Route::get('/register/vendor', [VendorController::class, 'showRegister'])->name('vendor.register');
    Route::post('/register/vendor', [VendorController::class, 'register'])->middleware('throttle:vendor-register');
});

// Auth required
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/events/{id}/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
    Route::post('/events/{id}/order', [OrderController::class, 'store'])->name('orders.store');
    Route::post('/promo/apply', [\App\Http\Controllers\Api\PromoCodeController::class, 'apply'])->name('promo.apply');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{id}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/tickets/{id}', [\App\Http\Controllers\Web\TicketController::class, 'show'])->name('tickets.show');
    Route::get('/payment/success', [OrderController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('/payment/failed', [OrderController::class, 'paymentFailed'])->name('payment.failed');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Vendor area
    Route::middleware('vendor')->prefix('vendor')->name('vendor.')->group(function () {
        Route::get('/dashboard', [VendorDashboardController::class, 'index'])->name('dashboard');

        Route::get('/events', [VendorEventController::class, 'index'])->name('events.index');
        Route::get('/events/create', [VendorEventController::class, 'create'])->name('events.create');
        Route::post('/events', [VendorEventController::class, 'store'])->name('events.store');
        Route::get('/events/{id}/edit', [VendorEventController::class, 'edit'])->name('events.edit');
        Route::put('/events/{id}', [VendorEventController::class, 'update'])->name('events.update');
        Route::delete('/events/{id}', [VendorEventController::class, 'destroy'])->name('events.destroy');

        Route::get('/events/{eventId}/skus', [VendorSkuController::class, 'index'])->name('skus.index');
        Route::get('/events/{eventId}/skus/create', [VendorSkuController::class, 'create'])->name('skus.create');
        Route::post('/events/{eventId}/skus', [VendorSkuController::class, 'store'])->name('skus.store');

        Route::get('/events/{eventId}/orders', [VendorOrderController::class, 'index'])->name('orders.index');
        Route::get('/events/{eventId}/orders/export', [VendorOrderController::class, 'export'])->name('orders.export');
        Route::get('/events/{eventId}/orders/{id}', [VendorOrderController::class, 'show'])->name('orders.show');

        Route::get('/events/{eventId}/promos', [VendorPromoController::class, 'index'])->name('promos.index');
        Route::post('/events/{eventId}/promos', [VendorPromoController::class, 'store'])->name('promos.store');
        Route::delete('/events/{eventId}/promos/{promoId}', [VendorPromoController::class, 'destroy'])->name('promos.destroy');

        Route::get('/tickets/check', [VendorTicketController::class, 'showCheck'])->name('tickets.check');
        Route::post('/tickets/check', [VendorTicketController::class, 'check'])->name('tickets.check.post');
        Route::get('/tickets/bulk-check', [VendorTicketController::class, 'showBulkCheck'])->name('tickets.bulk-check');
        Route::post('/tickets/bulk-check', [VendorTicketController::class, 'bulkCheck'])->name('tickets.bulk-check.post');
    });
});

// Admin area
Route::middleware('admin')->prefix('superadmin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/vendors', [AdminController::class, 'vendors'])->name('vendors');
    Route::patch('/vendors/{id}/status', [AdminController::class, 'vendorUpdateStatus'])->name('vendors.status');
    Route::get('/events', [AdminController::class, 'events'])->name('events');
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::get('/refunds', [AdminController::class, 'refunds'])->name('refunds');
    Route::post('/refunds/{id}/approve', [AdminController::class, 'approveRefund'])->name('refunds.approve');
    Route::post('/refunds/{id}/reject', [AdminController::class, 'rejectRefund'])->name('refunds.reject');
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::get('/users/export', [AdminController::class, 'exportUsers'])->name('users.export');
    Route::get('/vendors/export', [AdminController::class, 'exportVendors'])->name('vendors.export');
    Route::get('/events/export', [AdminController::class, 'exportEvents'])->name('events.export');
    Route::get('/orders/export', [AdminController::class, 'exportOrders'])->name('orders.export');
    Route::get('/reports/export', [AdminController::class, 'exportReports'])->name('reports.export');
    Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
    Route::post('/categories', [AdminController::class, 'categoryStore'])->name('categories.store');
    Route::delete('/categories/{id}', [AdminController::class, 'categoryDestroy'])->name('categories.destroy');
});

// Legal pages (for Play Store)
Route::get('/privacy-policy', fn() => view('pages.privacy-policy'))->name('privacy-policy');
Route::get('/terms-of-service', fn() => view('pages.terms-of-service'))->name('terms-of-service');

// Public
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');
