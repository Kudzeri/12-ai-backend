<?php

use App\Http\Controllers\API\v1\AdvertisementController;
use App\Http\Controllers\API\v1\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/advertisements', [AdvertisementController::class, 'index']);
