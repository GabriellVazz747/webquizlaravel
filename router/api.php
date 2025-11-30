<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\Api\RankingController;
use App\Http\Controllers\Api\QuestionController;

Route::get('test-api', function () {
    return 'API OK';
});
Route::post('/register', [AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class,'logout']);

    // Quiz
    Route::post('/quizzes/start', [QuizController::class,'start']);
    Route::get('/quizzes/{quiz}', [QuizController::class,'show']);
    Route::post('/quizzes/{quiz}/answer', [QuizController::class,'answer']);
    Route::post('/quizzes/{quiz}/finish', [QuizController::class,'finish']);

    // Ranking
    Route::get('/ranking', [RankingController::class,'index']);
    Route::get('/my-position', [RankingController::class,'myPosition']);

    // Questions
    Route::get('/questions/{question}', [QuestionController::class,'show']);
});