<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\QuizController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/questions', [QuizController::class, 'index']);
    Route::post('/quiz/result', [QuizController::class, 'store']);
    Route::get('/ranking', [QuizController::class, 'ranking']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
