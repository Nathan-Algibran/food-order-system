<?php
/**
 * Purpose: All web routes — guest, user, admin
 * Used by: Laravel router
 * Dependencies: All controllers, AdminMiddleware
 * Main functions: Route definitions
 * Side effects: None
 */

use App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\UlasanController;
use Illuminate\Support\Facades\Route;

// ─── Guest ───────────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',           [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',          [AuthController::class, 'login']);
    Route::get('/register',        [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',       [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ─── Public menu browse ───────────────────────────────────────────────────────
// Landing page (list menu)
Route::get('/', [HomeController::class, 'index'])->name('welcome');

// List menu juga bisa diakses dari /menu
Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');

// Detail menu
Route::get('/menu/{menu}', [MenuController::class, 'show'])->name('menu.show');

// ─── Authenticated User ───────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Cart
    Route::get('/cart',                    [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{menu}',        [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update/{id}',      [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}',     [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear',           [CartController::class, 'clear'])->name('cart.clear');

    // Checkout
    Route::get('/checkout',                [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout',               [CheckoutController::class, 'process'])->name('checkout.process');

    // Orders
    Route::get('/pesanan',                         [PemesananController::class, 'index'])->name('pemesanan.index');
    Route::get('/pesanan/{pemesanan}',             [PemesananController::class, 'show'])->name('pemesanan.show');
    Route::patch('/pesanan/{pemesanan}/confirm',   [PemesananController::class, 'confirmDelivered'])->name('pemesanan.confirm');

    // Reviews
    Route::post('/pesanan/{pemesanan}/ulasan',     [UlasanController::class, 'store'])->name('ulasan.store');
});

// ─── Admin ────────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Menu CRUD
    Route::resource('menu', Admin\MenuController::class)->except(['show']);

    // Orders
    Route::get('/orders',                        [Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{pemesanan}',            [Admin\OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{pemesanan}/status',   [Admin\OrderController::class, 'updateStatus'])->name('orders.status');

    Route::patch('/orders/{pemesanan}/confirm-payment',    [Admin\OrderController::class, 'confirmPayment'])->name('orders.confirm-payment');
    Route::patch('/orders/{pemesanan}/reject-payment',     [Admin\OrderController::class, 'rejectPayment'])->name('orders.reject-payment');
});
