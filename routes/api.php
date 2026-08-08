<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClientDashboardController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\StaffDashboardController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');

Route::post('/register/client', [RegisterController::class, 'client'])->middleware('throttle:login');
Route::post('/register/staff', [RegisterController::class, 'staff'])->middleware('throttle:login');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/client/dashboard', [ClientDashboardController::class, 'index'])
    ->middleware('auth:sanctum');

Route::get('/staff/dashboard', [StaffDashboardController::class, 'index'])
    ->middleware('auth:sanctum');

Route::get('/fetch-related-services/{id}', [ServiceController::class, 'fetchservicesapi'])
    ->name('fetchservices');
