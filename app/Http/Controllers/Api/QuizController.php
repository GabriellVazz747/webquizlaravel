<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\AnswerRequest;
use App\Services\QuizService;
use App\Http\Resources\QuizResource;
use App\Http\Resources\QuestionResource;
use App\Models\Quiz;

class QuizController
{
    protected $service;

    public function __construct(QuizService $service)
    {
        $this->service = $service;
    }

    public function start(Request $req)
    {
        $quiz = $this->service->startQuizForUser($req->user());
        $first = $this->service->firstQuestionForQuiz($quiz);

        return response()->json([
            'quiz' => new QuizResource($quiz),
            'first_question' => new QuestionResource($first)
        ], 201);
    }

    public function show(Request $req, Quiz $quiz)
    {
        abort_if($quiz->user_id !== $req->user()->id, 403);

        $questions = $this->service->questionsForQuiz($quiz);

        return response()->json([
            'quiz' => new QuizResource($quiz->fresh()),
            'questions' => QuestionResource::collection($questions)
        ]);
    }

    public function answer(AnswerRequest $req, Quiz $quiz)
    {
        abort_if($quiz->user_id !== $req->user()->id, 403);

        $res = $this->service->recordAnswer(
            $quiz,
            $req->question_id,
            $req->alternative_id,
            $req->time_taken ?? null
        );

        return response()->json($res);
    }

    public function finish(Request $req, Quiz $quiz)
    {
        abort_if($quiz->user_id !== $req->user()->id, 403);

        $this->service->finishQuiz($quiz);

        return response()->json([
            'quiz' => new QuizResource($quiz->fresh()),
            'answers' => $quiz->answers()->get()
        ]);
    }
}
