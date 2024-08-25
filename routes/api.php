<?php

use App\Http\Controllers\API\v1\AdvertisementController;
use App\Http\Controllers\API\v1\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('v1')->group(function () {
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

    Route::get('/advertisements', [AdvertisementController::class, 'index']);
    Route::post('/advertisements', [AdvertisementController::class, 'store']);
    Route::get('/advertisements/{id}', [AdvertisementController::class, 'show']);
    Route::put('/advertisements/{id}', [AdvertisementController::class, 'update']);
    Route::delete('/advertisements/{id}', [AdvertisementController::class, 'destroy']);
});
