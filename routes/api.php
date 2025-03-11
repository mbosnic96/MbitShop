<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    if ($request->user()) {
        return response()->json($request->user());
    } else {
        return response()->json(['message' => 'User is not authenticated'], 401);
    }
});
//searchbox api
Route::post('/search', [ProductController::class, 'search']);

//Products
Route::get('/dashboard/products', [ProductController::class, 'index']);
Route::post('/dashboard/products', [ProductController::class, 'store']);
Route::put('/dashboard/products/{id}', [ProductController::class, 'update']);
Route::get('/dashboard/products/{id}', [ProductController::class, 'modalData']);
Route::delete('/dashboard/products/{id}', [ProductController::class, 'destroy']);
Route::get('/products/filter/{slug}', [ProductController::class, 'show']);

//brand apis
Route::middleware('auth:sanctum')->get('/dashboard/brands', [BrandController::class, 'index']);
Route::get('/dashboard/brands/{id}', [BrandController::class, 'show']);
Route::post('/dashboard/brands', [BrandController::class, 'store']);
Route::put('/dashboard/brands/{id}', [BrandController::class, 'update']);
Route::delete('/dashboard/brands/{id}', [BrandController::class, 'destroy']);


//category apis
Route::get('/dashboard/categories', [CategoryController::class, 'index']);
Route::get('/dashboard/categories/{id}', [CategoryController::class, 'show']);
Route::post('/dashboard/categories', [CategoryController::class, 'store']);
Route::put('/dashboard/categories/{id}', [CategoryController::class, 'update']);
Route::delete('/dashboard/categories/{id}', [CategoryController::class, 'destroy']);

//cart apis

Route::post('cart/add', [CartController::class, 'addToCart']);
Route::get('cart', [CartController::class, 'showCart']);
Route::delete('cart/remove/{productId}', [CartController::class, 'remove']);
Route::put('cart/update/{productId}', [CartController::class, 'update']);
Route::get('dashboard/orders', [OrderController::class, 'index']);
Route::middleware('auth:sanctum')->get('/dashboard/my-orders', [OrderController::class, 'myOrders']);


//Route::get('/dashboard/orders/{id}', [OrderController::class, 'show']);
Route::post('/checkout', [OrderController::class, 'checkout']);
Route::get('dashboard/orders/{orderId}/pdf', [OrderController::class, 'downloadPDF']);
Route::post('/dashboard/orders/{id}/status', [OrderController::class, 'updateStatus']);

Route::put('dashboard/orders/{id}', [OrderController::class, 'approveOrder']);
Route::post('dashboard/orders/{orderId}/cancel', [OrderController::class, 'cancelOrder']);



