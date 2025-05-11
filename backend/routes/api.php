<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\UserBookController;
use App\Http\Controllers\UserBadgeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('api_key')->group(
    function () {
    Route::post('/register', [AuthenticationController::class, 'register']);
    Route::post('/login', [AuthenticationController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthenticationController::class, 'logout']);
        Route::patch('/me', [UserController::class, 'updateUser']);
        Route::patch('/me/password', [AuthenticationController::class, 'resetPassword']);
        Route::get('/me', [UserController::class, 'getCurrentUser']);
        Route::get('/me/badges', [UserBadgeController::class, 'index']);
        Route::delete('/me', [UserController::class, 'deleteUser']);

        //books
        Route::post('/books', [BookController::class, 'store']);
        Route::get('/books/search', [BookController::class, 'search']);

        // user library
        Route::post('/user-books', [UserBookController::class, 'store']);
        Route::post('/user-books/full', [UserBookController::class, 'storeFull']);
        Route::get('/user-books', [UserBookController::class, 'getUserBooks']);
        Route::patch('/user-books/{userBook}', [UserBookController::class, 'update']);

        //genres
        Route::post('/genres', [GenreController::class, 'store']);
        Route::get('/genres', [GenreController::class, 'index']);

        // Add other protected routes here
    });
});
