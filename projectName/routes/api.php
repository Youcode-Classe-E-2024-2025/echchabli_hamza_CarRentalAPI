<?php

use App\Http\Controllers\CarsController;
use App\Http\Controllers\AuthController; 
use App\Http\Controllers\RentalsController; 
use Illuminate\Support\Facades\Route;



Route::get('/cars', [CarsController::class, 'getAll']);


Route::get('/cars/{id}', [CarsController::class, 'getById']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/cars', [CarsController::class, 'create']);
    Route::put('/cars/{id}', [CarsController::class, 'update']);
    Route::delete('/cars/{id}', [CarsController::class, 'delete']);
});



Route::middleware('auth:sanctum')->get('/rentals', [RentalsController::class, 'getUserRentals']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/rentals', [RentalsController::class, 'create']);
    Route::get('/rentals', [RentalsController::class, 'getUserRentals']);
    Route::put('/rentals', [RentalsController::class, 'update']);
    Route::delete('/rentals/{id}', [RentalsController::class, 'delete']);

});



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);



