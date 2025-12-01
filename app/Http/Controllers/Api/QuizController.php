<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\QuizResult;
use App\Models\User;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index()
    {
        $questions = Question::with('options')
                    ->inRandomOrder()
                    ->limit(10)
                    ->get();

        return response()->json($questions);
    }

    public function store(Request $request)
    {
        $request->validate([
            'score' => 'required|integer',
            'time_seconds' => 'required|integer'
        ]);

        $result = QuizResult::create([
            'user_id' => $request->user()->id,
            'score' => $request->score,
            'time_seconds' => $request->time_seconds
        ]);

        return response()->json(['message' => 'Resultado salvo!', 'data' => $result], 201);
    }

    public function ranking()
    {
        $ranking = QuizResult::with('user')
            ->orderByDesc('score')
            ->orderBy('time_seconds')
            ->limit(10)
            ->get()
            ->map(function ($result) {
                return [
                    'name' => $result->user->name,
                    'score' => $result->score,
                    'time' => gmdate("i:s", $result->time_seconds) // Converte segundos para 00:00
                ];
            });

        return response()->json($ranking);
    }
}
