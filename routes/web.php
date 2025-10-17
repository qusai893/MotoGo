<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\OrderController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



// routes/web.php
Route::post('/send-verification-code', [RegisterController::class, 'sendVerificationCode'])->name('send.verification.code');
Route::post('/verify-code', [RegisterController::class, 'verifyCode'])->name('verify.code');

// Bot durumu için (opsiyonel)
Route::get('/whatsapp-status', function (App\Services\WhatsAppWebService $service) {
    return response()->json($service->getStatus());
});



// Admin Routes
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/orders', [AdminController::class, 'orders'])->name('orders.index');
    Route::get('/orders/{order}', [AdminController::class, 'showOrder'])->name('orders.show');
    Route::put('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.update-status');
    // Restoran Routes
    Route::get('/restaurants', [AdminController::class, 'restaurants'])->name('restaurants');
    Route::get('/restaurants/create', [AdminController::class, 'createRestaurant'])->name('restaurants.create');
    Route::post('/restaurants', [AdminController::class, 'storeRestaurant'])->name('restaurants.store');
    Route::get('/restaurants/{restaurant}/edit', [AdminController::class, 'editRestaurant'])->name('restaurants.edit');
    Route::put('/restaurants/{restaurant}', [AdminController::class, 'updateRestaurant'])->name('restaurants.update');
    Route::delete('restaurants/{restaurant}/delete', [AdminController::class, 'deleteRestaurant'])->name('restaurants.delete');

    // Kategori Routes
    Route::get('/restaurants/{restaurant}/categories', [AdminController::class, 'categories'])->name('categories');
    Route::get('/restaurants/{restaurant}/categories/create', [AdminController::class, 'createCategory'])->name('categories.create');
    Route::post('/restaurants/{restaurant}/categories', [AdminController::class, 'storeCategory'])->name('categories.store');

    // Ürün Routes
    Route::get('/categories/{category}/products', [AdminController::class, 'products'])->name('products');
    Route::get('/categories/{category}/products/create', [AdminController::class, 'createProduct'])->name('products.create');
    Route::post('/categories/{category}/products', [AdminController::class, 'storeProduct'])->name('products.store');
});



// routes/web.php



// Public Routes
Route::get('/restaurants', [RestaurantController::class, 'index'])->name('restaurants.index');
Route::get('/restaurants/{restaurant}', [RestaurantController::class, 'show'])->name('restaurants.show');
Route::get('/restaurants/{restaurant}/menu', [RestaurantController::class, 'menu'])->name('restaurants.menu');

// Order Routes (Authenticated)
Route::middleware(['auth'])->group(function () {
    Route::get('/restaurants/{restaurant}/order', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/restaurants/{restaurant}/order', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
