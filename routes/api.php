<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KamarApiController;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\MyRentalApiController;
use App\Http\Controllers\Api\BookingApiController;
use App\Http\Controllers\Api\PaymentApiController;
use App\Http\Controllers\Api\IdentityApiController;
use App\Http\Controllers\Api\PaymentHistoryApiController;
use App\Http\Controllers\Api\TestimonialApiController;

Route::get('/kamars', [KamarApiController::class, 'index']);
Route::get('/kamars/{kamar}', [KamarApiController::class, 'show']);

Route::post('/login', [AuthApiController::class, 'login']);
Route::get('/profile', [AuthApiController::class, 'profile']);
Route::post('/logout', [AuthApiController::class, 'logout']);

Route::get('/my-rentals', [MyRentalApiController::class, 'index']);
Route::get('/my-rentals/history', [MyRentalApiController::class, 'historyData']);
Route::post('/bookings', [BookingApiController::class, 'store']);

Route::get('/invoices/{invoice}', [PaymentApiController::class, 'invoice']);
Route::get('/invoices/{invoice}/methods', [PaymentApiController::class, 'methods']);
Route::post('/invoices/{invoice}/choose-method', [PaymentApiController::class, 'chooseMethod']);
Route::get('/invoices/{invoice}/status', [PaymentApiController::class, 'status']);

Route::post('/identity/upload', [IdentityApiController::class, 'upload']);
Route::get('/identity/status', [IdentityApiController::class, 'status']);

Route::post('/my-rentals/{booking}/renew', [MyRentalApiController::class, 'renew']);

Route::get('/payment-history', [PaymentHistoryApiController::class, 'index']);
Route::post('/payment-history/{booking}/cancel', [PaymentHistoryApiController::class, 'cancel']);

Route::get('/testimonial/popup', [TestimonialApiController::class, 'popup']);
Route::get('/testimonials', [TestimonialApiController::class, 'index']);
Route::post('/testimonials', [TestimonialApiController::class, 'store']);


Route::get('/app-version', function () {
    return response()->json([
        'success' => true,
        'latest_version' => '1.0.0',
        'latest_build' => 1,
        'apk_url' => 'https://rafakost.biz.id/downloads/rafakost.apk',
        'force_update' => false,
        'message' => 'Versi baru Rafa Kost tersedia. Silakan update aplikasi.',
    ]);
});