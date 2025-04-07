<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Définition des routes API pour l'application
|
*/

Route::get('/test', function () {
    return response()->json(['message' => 'Backend fonctionne !']);
});


// Routes publiques pour l'inscription et la connexion
Route::post('/login', [UsersController::class, 'login']);
Route::post('/register', [UsersController::class, 'register']);

// Routes protégées (authentifiées)
Route::middleware('auth:sanctum')->group(function () {
    // Routes AdminController
    Route::get('/admin/events', [AdminController::class, 'index']);
    Route::get('/admin/events/{id}/users', [AdminController::class, 'listUsers']);
    Route::post('/admin/events', [AdminController::class, 'store']);
    Route::get('/admin/events/{id}', [AdminController::class, 'show']);
    Route::put('/admin/events/{id}', [AdminController::class, 'update']);
    Route::delete('/admin/events/{id}', [AdminController::class, 'destroy']);
    Route::get('/admin/events/stats', [AdminController::class, 'stats']);
    Route::get('/admin/events/{id}/generate-pdf', [AdminController::class, 'generatePDF']);
    
    // Routes EventController
    Route::get('/events', [EventController::class, 'index']);
    Route::get('/events/not-registered', [EventController::class, 'notEvents']);
    Route::get('/events/my-events', [EventController::class, 'myEvents']);
    Route::post('/events', [EventController::class, 'store']);
    Route::post('/events/{id}/register', [EventController::class, 'register']);
    Route::post('/events/{id}/cancel', [EventController::class, 'cancel']);
});

