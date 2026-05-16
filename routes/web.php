<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\landingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController; 
use App\Http\Controllers\Api\ProcessPaymentController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

Route::get('/', [landingController::class, 'index'])->name('home');

Route::name('admin.')->prefix('admin')->middleware(['auth','admin'])->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name("dashboard");

    Route::get('/produk', [AdminController::class, 'produkIndex'])->name('produk.index');
    Route::post('/produk', [AdminController::class, 'produkStore'])->name('produk.store');
    Route::put('/produk/{id}', [AdminController::class, 'produkUpdate'])->name('produk.update');
    Route::delete('/produk/{id}', [AdminController::class, 'produkDestroy'])->name('produk.destroy');

    Route::get('/users', [AdminController::class, 'usersIndex'])->name('users.index');
    Route::put('/users/{id}/role', [AdminController::class, 'usersUpdateRole'])->name('users.role');
    Route::post('/users', [AdminController::class, 'usersStore'])->name('users.store');
    Route::delete('/users/{id}', [AdminController::class, 'usersDestroy'])->name('users.destroy');

    Route::get('/orders', [AdminController::class, 'ordersIndex'])->name('orders.index');

    Route::patch('/orders/{order}/status', [AdminController::class, 'ordersUpdateStatus'])
        ->name('orders.updateStatus');
});

Route::get('/login', function () {
    return redirect('/'); 
})->name('login');


Route::get('Katalog/{slug}', [landingController::class, 'Katalog'])->name('landing.Katalog');
Route::get('/product/{id}', [landingController::class, 'showProduct'])->name('product.detail');

Route::get('/search', [landingController::class, 'search'])->name('search');

Route::middleware('auth')->group(function () {
    Route::get('/cart/view',   [landingController::class, 'getCart'])->name('cart.view');
    Route::post('/cart/add',   [landingController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/remove',[landingController::class, 'removeFromCart'])->name('cart.remove');
    Route::get('/checkout', [landingController::class, 'checkoutPage'])->name('checkout.page');
    Route::post('/checkout', [landingController::class, 'checkout'])->name('checkout');
    Route::get('/my-orders', [landingController::class, 'myOrders'])->name('orders.my');
    Route::get('/my-orders/{order}', [landingController::class, 'myOrderDetail'])->name('orders.my.detail');
    Route::get('/pay/{order}', [ProcessPaymentController::class, 'pay'])->name('pay');
    Route::get('/pay/{order}/isPayed', [ProcessPaymentController::class, 'isPayed'])->name('pay.success');

});

Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [ResetPasswordController::class, 'reset'])
    ->middleware('guest')
    ->name('password.update');

Route::post('/cart/update', [landingController::class, 'updateCartQty'])
    ->middleware('auth')
    ->name('cart.update');

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login',    [AuthController::class, 'login'])->name('login');
Route::post('/logout',   [AuthController::class, 'logout'])->name('logout');

Route::get('/pay/{order}', [ProcessPaymentController::class, 'pay'])->name('pay');
Route::post('/midtrans/notification', [ProcessPaymentController::class, 'notification']);

Route::post('/midtrans/notification',
    [ProcessPaymentController::class, 'notification']
)->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);