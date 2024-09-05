<?php

use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\v1\AdvertisementController;
use App\Http\Controllers\API\v1\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) { return $request->user(); });
    Route::post('logout', [AuthController::class, 'logout']);

    Route::post('/email/verification-notification', [AuthController::class, 'sendVerificationEmail'])
        ->name('verification.send');
    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
        ->name('verification.verify');
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('unauthenticated', [AuthController::class, 'unauthenticated'])->name('guest');


Route::prefix('v1')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::put('/categories/{id}', [CategoryController::class, 'update']);
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
    });
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);


    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/advertisements', [AdvertisementController::class, 'store']);
        Route::put('/advertisements/{id}', [AdvertisementController::class, 'update']);
        Route::delete('/advertisements/{id}', [AdvertisementController::class, 'destroy']);
    });
    Route::get('/advertisements', [AdvertisementController::class, 'index']);
    Route::get('/advertisements/search', [AdvertisementController::class, 'index']);
    Route::get('/advertisements/{id}', [AdvertisementController::class, 'show']);

});
