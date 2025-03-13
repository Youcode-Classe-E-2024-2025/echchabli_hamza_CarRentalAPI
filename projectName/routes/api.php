<?php

use App\Http\Controllers\CarsController;
use App\Http\Controllers\AuthController; 
use App\Http\Controllers\RentalsController; 
use App\Http\Controllers\PaymentsController; 
use Illuminate\Support\Facades\Route;



Route::get('/cars/pagin/{param}', [CarsController::class, 'getAll']);




Route::middleware('auth:sanctum')->group(function () {
   
    Route::apiResource('cars', CarsController::class);

   
});

Route::get('selectCars', [CarsController::class, 'filterByModelAndCompany']);



Route::middleware('auth:sanctum')->group(function () {
    // Rentals Resource Routes
    Route::apiResource('rentals', RentalsController::class);

    // Custom Routes for Rentals
    // Route::get('/rentals', [RentalsController::class, 'getUserRentals']); // Get user-specific rentals
    // Route::get('/rentals/{id}', [RentalsController::class, 'show']); // Get a specific rental (if needed)

    
    Route::apiResource('payments', PaymentsController::class);


   

    // Custom Routes for Payments
    Route::get('/payments/user/{user_id}', [PaymentsController::class, 'getUserPaymentsById']); // Get user-specific payments
    Route::get('/payments/rental/{rentalId}', [PaymentsController::class, 'getPaymentByRentalId']); // Get payments by rental ID  
});
 Route::get('/payment/success', [PaymentsController::class, 'success'])->name('payment.success');
    Route::get('/payments/success', [PaymentsController::class, 'cancel'])->name('payment.cancel');


Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');



Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);