<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StringAnalyzerController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



// 1. Create/Analyze String
Route::post('/strings', [StringAnalyzerController::class, 'store']);


Route::get('/strings', [StringAnalyzerController::class, 'index']);

// 2. Get Specific String
// Route::get('/strings/{id}', [StringAnalyzerController::class, 'show']);
Route::get('/strings/{string}', [StringAnalyzerController::class, 'show']);


// 3. Get All Strings with Filtering Options
// I want the access route to be strings but target analyze-strings controller API methods
// The below code base contained 
Route::get('/strings/natural-language-processing', [StringAnalyzerController::class, 'naturalLanguageProcessing']);


// Route::delete('/strings/{id}', [StringAnalyzerController::class, 'destroy']);

// 4. Delete Specific String
Route::delete('/strings/{string}', [StringAnalyzerController::class, 'destroy']);

