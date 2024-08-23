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

    Route::get('/advertisements', [AdvertisementController::class, 'index']);
    Route::post('/advertisements', [AdvertisementController::class, 'store']);
});
