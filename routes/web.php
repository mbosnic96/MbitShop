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

Route::get('/', function () {
    return view('welcome');
})->name('/');





Route::middleware([
    'auth:sanctum',
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});



Route::get('products',[ProductController::class, 'index'])->name('products');
Route::middleware(['auth:sanctum','verified'])->get('add-product',[ProductController::class,'create'])->name('add-product');
Route::middleware(['auth:sanctum','verified'])->post('store-product',[ProductController::class,'store'])->name('store-product');

Route::middleware(['auth:sanctum','verified'])->get('brands',[BrandController::class,'index'])->name('brands');
Route::middleware(['auth:sanctum','verified'])->get('add-brand',[BrandController::class,'create'])->name('add-brand');
Route::middleware(['auth:sanctum','verified'])->post('store-brand',[BrandController::class,'store'])->name('store-brand');

Route::middleware(['auth:sanctum','verified'])->get('categories',[CategoryController::class,'index'])->name('categories');
Route::middleware(['auth:sanctum','verified'])->get('add-category',[CategoryController::class,'create'])->name('add-category');
Route::middleware(['auth:sanctum','verified'])->post('store-category',[CategoryController::class,'store'])->name('store-category');
