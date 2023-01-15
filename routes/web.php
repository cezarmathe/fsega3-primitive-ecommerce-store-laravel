<?php

use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CRUDController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', [ProductController::class, 'index'])->name('products:index');
Route::resource('products', CRUDController::class);

Route::get('/cart', [CartController::class, 'index'])->name('cart:index');
Route::patch('/cart', [CartController::class, 'update'])->name('cart:update');
Route::post('/cart:addItem', [CartController::class, 'addItem'])->name('cart:addItem');
Route::delete('/cart:removeItem', [CartController::class, 'removeItem'])->name('cart:removeItem');
Route::post('/cart:checkout', [CartController::class, 'checkout'])->name('cart:checkout');

Route::get('/orders', [OrderController::class, 'list'])->name('order:list');
Route::get("/order/{id}", [OrderController::class, "index"])->name("order:get");

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
