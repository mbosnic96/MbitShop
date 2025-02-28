<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BrandController;

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
    return $request->user();
});



// Route to get all products from all categories
Route::get('/products/filter/{slug}', [ProductController::class, 'show']);
Route::post('/search', [ProductController::class, 'search']);
Route::get('/dashboard/brands', [BrandController::class, 'index']);
Route::delete('/dashboard/brands/{id}', [BrandController::class, 'destroy']);

