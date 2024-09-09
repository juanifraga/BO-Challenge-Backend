<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\NotesController;

Route::post('/login', [LoginController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [LoginController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/user', [UsersController::class, 'getUser']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/contacts', [ContactsController::class, 'index']);
    Route::get('/contacts/{id}', [ContactsController::class, 'show']);
    Route::post('/contacts', [ContactsController::class, 'store']);
    Route::put('/contacts/{id}', [ContactsController::class, 'update']);
    Route::delete('/contacts/{id}', [ContactsController::class, 'destroy']);
    Route::post('/contacts/{contactId}/notes', [NotesController::class, 'storeForContact']);

    Route::get('/notes', [NotesController::class, 'index']);
    Route::get('/notes/{id}', [NotesController::class, 'show']);
});

