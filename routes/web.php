<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
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

// Landing - Visible to all
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Category route (public)
Route::get('/categories/{slug}', function ($slug) {
    return view('categories.show', ['slug' => $slug]);
})->name('categories.show');



// Admin Routes - Protected by 'checkRole:admin' middleware
Route::middleware(['checkRole:admin'])->group(function () {
    // Product Routes
    Route::get('dashboard/users', [DashboardController::class, 'index'])->name('users.index');
    Route::delete('dashboard/users/delete/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('dashboard/products', [ProductController::class, 'dasboardIndex'])->name('products.index');
    Route::get('product/{slug}', [ProductController::class, 'show'])->name('products.show');

    // Brand Routes
    Route::get('dashboard/brands', [BrandController::class, 'dasboardIndex'])->name('brands.index');
    

    // Category Routes
    Route::get('dashboard/categories', [CategoryController::class, 'dasboardIndex'])->name('categories.index');

    
   // Route::get('dashboard/orders', [OrderController::class, 'dasboardIndex'])->name('dashboard.orders');
});

// Customer Routes - Protected by 'checkRole:customer' middleware
Route::middleware(['checkRole:customer'])->group(function () {
   
 //   Route::get('dashboard/orders', [OrderController::class, 'dasboardIndex'])->name('dashboard.orders');
});

// Authenticated User Routes
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('dashboard/orders', [OrderController::class, 'dasboardIndex'])->name('dashboard.orders');
});