<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use App\Services\WeatherService;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes (no authentication)
Route::post('/search', [ProductController::class, 'search']);
Route::get('/product/{slug}', [ProductController::class, 'viewData']);
Route::get('/products/filter/{slug}', [ProductController::class, 'show']);
Route::get('/products/top-selling-products', [ProductController::class, 'mostSoldProducts']);
Route::get('/products/on-discount', [ProductController::class, 'onDiscount']);
Route::get('/products/get-latest-products', [ProductController::class, 'latestProducts']);
Route::get('/products/promo', [ProductController::class, 'getPromoProduct']);
Route::get('/categories', [CategoryController::class, 'getHomepageIndex']);

// User authentication check
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Authenticated user routes (no admin role required)
Route::middleware(['auth:sanctum', 'verified'])->post('cart/add', [CartController::class, 'addToCart']);
Route::middleware(['auth:sanctum', 'verified'])->get('cart', [CartController::class, 'showCart']);
Route::middleware(['auth:sanctum', 'verified'])->delete('cart/remove/{productId}', [CartController::class, 'remove']);
Route::middleware(['auth:sanctum', 'verified'])->put('cart/update/{productId}', [CartController::class, 'update']);
Route::middleware(['auth:sanctum', 'verified'])->post('/checkout', [OrderController::class, 'checkout']);
Route::middleware(['auth:sanctum', 'verified'])->get('dashboard/orders/{orderId}/pdf', [OrderController::class, 'downloadPDF']);
Route::middleware(['auth:sanctum', 'verified'])->get('/notifications', [NotificationController::class, 'index']);
Route::middleware(['auth:sanctum', 'verified'])->post('/notifications/read/{id}', [NotificationController::class, 'markAsRead']);

// Admin-only routes
Route::middleware(['auth:sanctum', 'verified', 'checkRole:admin'])->get('/dashboard/products', [ProductController::class, 'index']);
Route::middleware(['auth:sanctum', 'verified', 'checkRole:admin'])->post('/dashboard/products', [ProductController::class, 'store']);
Route::middleware(['auth:sanctum', 'verified', 'checkRole:admin'])->put('/dashboard/products/{id}', [ProductController::class, 'update']);
Route::middleware(['auth:sanctum', 'verified', 'checkRole:admin'])->get('/dashboard/products/{id}', [ProductController::class, 'modalData']);
Route::middleware(['auth:sanctum', 'verified', 'checkRole:admin'])->delete('/dashboard/products/{id}', [ProductController::class, 'destroy']);
Route::middleware(['auth:sanctum', 'verified', 'checkRole:admin'])->get('/dashboard/brands', [BrandController::class, 'index']);
Route::middleware(['auth:sanctum', 'verified', 'checkRole:admin'])->get('/dashboard/brands/{id}', [BrandController::class, 'show']);
Route::middleware(['auth:sanctum', 'verified', 'checkRole:admin'])->post('/dashboard/brands', [BrandController::class, 'store']);
Route::middleware(['auth:sanctum', 'verified', 'checkRole:admin'])->put('/dashboard/brands/{id}', [BrandController::class, 'update']);
Route::middleware(['auth:sanctum', 'verified', 'checkRole:admin'])->delete('/dashboard/brands/{id}', [BrandController::class, 'destroy']);
Route::middleware(['auth:sanctum', 'verified', 'checkRole:admin'])->get('/dashboard/categories', [CategoryController::class, 'index']);
Route::middleware(['auth:sanctum', 'verified', 'checkRole:admin'])->get('/dashboard/categories/{id}', [CategoryController::class, 'show']);
Route::middleware(['auth:sanctum', 'verified', 'checkRole:admin'])->post('/dashboard/categories', [CategoryController::class, 'store']);
Route::middleware(['auth:sanctum', 'verified', 'checkRole:admin'])->put('/dashboard/categories/{id}', [CategoryController::class, 'update']);
Route::middleware(['auth:sanctum', 'verified', 'checkRole:admin'])->delete('/dashboard/categories/{id}', [CategoryController::class, 'destroy']);
Route::middleware(['auth:sanctum', 'verified'])->get('dashboard/orders', [OrderController::class, 'index']);
Route::middleware(['auth:sanctum', 'verified', 'checkRole:admin'])->put('/dashboard/orders/{id}/{status}', [OrderController::class, 'checkStatus']);
Route::middleware(['auth:sanctum', 'verified', 'checkRole:admin'])->get('/order-stats', [OrderController::class, 'getOrderStats']);
Route::middleware(['auth:sanctum', 'verified', 'checkRole:admin'])->get('/user-stats', [UserController::class, 'getUserGrowthStats']);
Route::middleware(['auth:sanctum', 'verified', 'checkRole:admin'])->get('/dashboard/weather', function(WeatherService $weatherService) {
    return response()->json($weatherService->getBihacWeather());
});