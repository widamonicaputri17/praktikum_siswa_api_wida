<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/mahasiswas', [AuthController::class, 'mahasiswas ']);

// Authenticated user route to get current user
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Routes for authenticated users
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('mahasiswas', MahasiswaController::class);
    // Admin route protected by the AdminMiddleware
    Route::get('/admin', function (Request $request) {
        return response()->json(['message' => 'Welcome Admin']);
    })->middleware(AdminMiddleware::class); // Use the middleware directly

    Route::apiResource('mahasiswas', MahasiswaController::class)->only('index'); // Only for viewing all mahasiswa

    Route::middleware('admin')->group(function () {
        // Admin can manage all mahasiswa resources except index
        Route::apiResource('mahasiswas', MahasiswaController::class)->except('index'); 
    });

    Route::middleware('auth:sanctum')->get('/mahasiswas', [MahasiswaController::class, 'index']);

});
