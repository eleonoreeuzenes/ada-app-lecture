<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\UserBookController;

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

Route::get('/test', function (Request $request) {
    return response()->json(['message' => 'Hello, World!']);
});
Route::post('/register', [AuthenticationController::class, 'register']);
Route::post('/login', [AuthenticationController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthenticationController::class, 'logout']);
    Route::patch('/me', [UserController::class, 'updateUser']);
    Route::patch('/me/password', [AuthenticationController::class, 'resetPassword']);

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
