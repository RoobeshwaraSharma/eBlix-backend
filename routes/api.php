<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ContactMessageController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [RegisterController::class, 'register']);
Route::put('/register/{id}', [RegisterController::class, 'edit']);
Route::get('/user', [RegisterController::class, 'show']);

Route::post('/login', [LoginController::class, 'login']);

//products
Route::get('/products', [ProductController::class, 'index']);
Route::post('/products', [ProductController::class, 'store']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::put('/products/{id}', [ProductController::class, 'update']);
Route::delete('/products/{id}', [ProductController::class, 'destroy']);
Route::get('/product-count', [ProductController::class, 'getProductCount']);

//contactus
Route::post('/contact-us', [ContactMessageController::class, 'store']);
Route::get('/contact-messages', [ContactMessageController::class, 'index']);
Route::delete('/contact-messages/{id}', [ContactMessageController::class, 'destroy']);
Route::get('/message-count', [ContactMessageController::class, 'getMessageCount']);

// gallery
Route::get('/gallery', [GalleryController::class, 'index']);
Route::post('/gallery', [GalleryController::class, 'store']);
Route::get('/gallery/{id}', [GalleryController::class, 'show']); // Changed to GET
Route::put('/gallery/{id}', [GalleryController::class, 'update']);
Route::delete('/gallery/{id}', [GalleryController::class, 'destroy']);
Route::get('/gallery-count', [GalleryController::class, 'getGalleryCount']);

//Orders
Route::post('/orders', [OrdersController::class, 'store']);
Route::get('/orders', [OrdersController::class, 'index']);
Route::get('/orders/report', [OrdersController::class, 'indexreport']);
Route::delete('/orders/{id}', [OrdersController::class, 'destroy']);

//Payment Process
Route::post('/paymentdone', [PaymentController::class, 'store']);
