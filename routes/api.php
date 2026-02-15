<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/services', [\App\Http\Controllers\Api\BookingApiController::class, 'getServices']);
Route::get('/slots', [\App\Http\Controllers\Api\BookingApiController::class, 'getSlots']);
Route::post('/book', [\App\Http\Controllers\Api\BookingApiController::class, 'book']);
