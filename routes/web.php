<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\MediaController as AdminMediaController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminCustomerController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Admin\AdminActivityController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\Store\ProductController as StoreProductController;
use App\Http\Controllers\Store\CategoryController as StoreCategoryController;
use App\Http\Controllers\Store\CartController;
use App\Http\Controllers\Store\CheckoutController;
use App\Http\Controllers\Store\OrderController as StoreOrderController;
use App\Http\Controllers\Store\HomeController;
use App\Http\Controllers\Store\SearchController;
use App\Http\Controllers\Store\SearchSuggestionsController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

Route::get('/lang/{locale}', [LocaleController::class, 'switch'])->name('lang.switch');

// Guest-only routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);

    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->middleware('throttle:5,1');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

// Store routes
Route::prefix('store')->name('store.')->group(function () {
    Route::get('/products', [StoreProductController::class, 'index'])->name('products.index');
    Route::get('/products/{slug}', [StoreProductController::class, 'show'])->name('products.show');

    Route::get('/categories/{slug}', [StoreCategoryController::class, 'show'])->name('categories.show');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{cartItemId}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItemId}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('cart.apply-coupon');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    Route::get('/orders/{orderId}', [StoreOrderController::class, 'show'])->name('orders.show')->middleware('auth');
});

Route::get('/search', SearchController::class)->name('search');
Route::get('/search/suggestions', SearchSuggestionsController::class)->name('search.suggestions');

// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('categories', AdminCategoryController::class)->except('show');
    Route::resource('products', AdminProductController::class)->except('show');

    Route::post('/media', [AdminMediaController::class, 'store'])->name('media.store');
    Route::delete('/media/{media}', [AdminMediaController::class, 'destroy'])->name('media.destroy');
    Route::patch('/media/reorder', [AdminMediaController::class, 'reorder'])->name('media.reorder');

    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');

    Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{payment}', [AdminPaymentController::class, 'show'])->name('payments.show');
    Route::patch('/payments/{payment}/verify', [AdminPaymentController::class, 'verify'])->name('payments.verify');
    Route::patch('/payments/{payment}/reject', [AdminPaymentController::class, 'reject'])->name('payments.reject');

    Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{customer}', [AdminCustomerController::class, 'show'])->name('customers.show');

    Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::patch('/settings', [AdminSettingController::class, 'update'])->name('settings.update');

    Route::get('/activity-log', [AdminActivityController::class, 'index'])->name('activity-log.index');
});
