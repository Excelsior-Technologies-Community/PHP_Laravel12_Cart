<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;

Route::get('/', [CartController::class, 'products']);

Route::get('/create-product', [CartController::class, 'create']);
Route::post('/store-product', [CartController::class, 'store']);

Route::get('/edit-product/{id}', [CartController::class, 'edit']);
Route::post('/update-product/{id}', [CartController::class, 'update']);

Route::get('/delete-product/{id}', [CartController::class, 'delete']);

Route::get('/add-to-cart/{id}', [CartController::class, 'addToCart']);
Route::get('/cart', [CartController::class, 'cart']);
Route::post('/remove-cart', [CartController::class, 'remove']);
