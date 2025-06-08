<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\AddressController; 
use App\Http\Controllers\Api\UserFavoriteController;
use App\Http\Controllers\Api\RestaurantController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductReviewController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\OrderItemController;
use App\Http\Controllers\Api\AuthController;


// Test route
Route::get('/ping', function () {
    return response()->json(['message' => 'API is working', 'timestamp' => now()]);
});

// Public routes (no authentication required)
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Public API routes (if you want them accessible without auth)
Route::apiResource('categories', CategoryController::class);
Route::apiResource('products', ProductController::class);
Route::apiResource('restaurants', RestaurantController::class);

// Custom restaurant routes (BEFORE the resource routes that might conflict)
Route::get('/restaurants/{restaurant}/categories', [RestaurantController::class, 'getRestaurantCategories'])->name('restaurants.categories');
Route::get('/restaurants/{restaurant}/products', [RestaurantController::class, 'getRestaurantProducts'])->name('restaurants.products');

// Product reviews (public)
Route::get('/products/{product}/reviews', [ProductReviewController::class, 'index'])->name('products.reviews.index');

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    // User info
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // User-specific resources
    Route::apiResource('addresses', AddressController::class);
    Route::apiResource('orders', OrderController::class);
    Route::apiResource('payments', PaymentController::class);
    Route::apiResource('order-items', OrderItemController::class);
    
    // Favorites
    Route::get('/favorites', [UserFavoriteController::class, 'index']);
    Route::post('/products/{product}/favorite', [UserFavoriteController::class, 'storeProduct']);
    Route::delete('/products/{product}/unfavorite', [UserFavoriteController::class, 'destroyProduct']);

    // Restaurant Favorites
    Route::post('/restaurants/{restaurant}/favorite', [UserFavoriteController::class, 'storeRestaurant']);
    Route::delete('/restaurants/{restaurant}/unfavorite', [UserFavoriteController::class, 'destroyRestaurant']);
    
    // Product reviews (authenticated)
    Route::post('/products/{product}/reviews', [ProductReviewController::class, 'store'])->name('products.reviews.store');
});
