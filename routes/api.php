<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\PatientController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Rating Routes
Route::post('/ratings', [RatingController::class, 'rateProduct']);
Route::post('/ratings/change', [RatingController::class, 'changeRating']);
Route::post('/ratings/remove', [RatingController::class, 'removeRating']);
Route::get('/products', [RatingController::class, 'listProducts']);

// Bonus Hospital Patient Registration
Route::post('/patient-registration', [PatientController::class, 'registerPatient']);
