<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;

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
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories-show');

// Admin Routes - Protected by 'checkRole:admin' middleware
Route::middleware(['checkRole:admin'])->group(function () {
    // Product Routes
    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    Route::get('add-product', [ProductController::class, 'create'])->name('products.create');
    Route::post('store-product', [ProductController::class, 'store'])->name('products.store');
    Route::get('show-product/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::get('edit-product/{product}', [ProductController::class, 'edit'])->name('products.edit');
    Route::post('update-product/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::post('delete-product/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    // Brand Routes
    Route::get('brands', [BrandController::class, 'index'])->name('brands.index');
    Route::get('add-brand', [BrandController::class, 'create'])->name('brands.create');
    Route::post('store-brand', [BrandController::class, 'store'])->name('brands.store');

    // Category Routes
    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('add-category', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('store-category', [CategoryController::class, 'store'])->name('categories.store');
});

// Customer Routes - Protected by 'checkRole:customer' middleware
Route::middleware(['checkRole:customer'])->group(function () {
    // Your customer routes can go here
    // For example:
    // Route::get('/customer', 'CustomerController@index');
});

// Authenticated User Routes
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
