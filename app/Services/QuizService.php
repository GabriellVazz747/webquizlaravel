<?php
namespace App\Services;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\Alternative;
use App\Models\QuizAnswer;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class QuizService
{
    protected $perQuiz = 10;
    protected $pointsPerCorrect = 10;

    public function startQuizForUser($user)
    {
        return DB::transaction(function () use ($user) {
            // Determinístico: orderBy id
            $questionIds = Question::orderBy('id')->limit($this->perQuiz)->pluck('id')->toArray();

            $quiz = Quiz::create([
                'user_id' => $user->id,
                'total_questions' => $this->perQuiz,
                'started_at' => now(),
                'question_ids' => $questionIds
            ]);

            return $quiz;
        });
    }

    public function firstQuestionForQuiz(Quiz $quiz)
    {
        $ids = $quiz->question_ids ?? [];
        if (empty($ids)) return null;
        return Question::with(['alternatives' => function($q){
            $q->select('id','question_id','content','position')->orderBy('position');
        }])->find($ids[0]);
    }

    public function questionsForQuiz(Quiz $quiz)
    {
        $ids = $quiz->question_ids ?? [];
        if (empty($ids)) return collect();
        $idsList = implode(',', $ids);
        return Question::whereIn('id', $ids)->orderByRaw("FIELD(id, $idsList)")
            ->with(['alternatives' => function($q){
                $q->select('id','question_id','content','position')->orderBy('position');
            }])->get();
    }

    public function recordAnswer(Quiz $quiz, int $questionId, int $alternativeId, ?int $timeTaken)
    {
        return DB::transaction(function () use ($quiz, $questionId, $alternativeId, $timeTaken) {
            if (!in_array($questionId, $quiz->question_ids ?? [])) {
                abort(422, 'Pergunta não faz parte deste quiz.');
            }

            $alt = Alternative::where('id', $alternativeId)->where('question_id', $questionId)->first();
            if (!$alt) abort(422, 'Alternativa inválida para a pergunta informada.');

            // evitar duplicidade
            $exists = QuizAnswer::where('quiz_id', $quiz->id)->where('question_id', $questionId)->first();
            if ($exists) abort(409, 'Resposta já registrada.');

            $isCorrect = (bool)$alt->is_correct;

            $answer = QuizAnswer::create([
                'quiz_id' => $quiz->id,
                'question_id' => $questionId,
                'alternative_id' => $alternativeId,
                'is_correct' => $isCorrect,
                'time_taken' => $timeTaken,
                'answered_at' => now()
            ]);

            return ['answer'=>$answer,'is_correct'=>$isCorrect];
        });
    }

    public function finishQuiz(Quiz $quiz)
    {
        return DB::transaction(function () use ($quiz) {
            $correct = $quiz->answers()->where('is_correct', true)->count();
            $wrong = $quiz->answers()->where('is_correct', false)->count();
            $score = $correct * $this->pointsPerCorrect;

            $finishedAt = now();
            $timeSeconds = null;
            if ($quiz->started_at) {
                $timeSeconds = $finishedAt->diffInSeconds(Carbon::parse($quiz->started_at));
            } else {
                $timeSeconds = (int)$quiz->answers()->sum('time_taken');
            }

            $quiz->update([
                'correct_count' => $correct,
                'wrong_count' => $wrong,
                'score' => $score,
                'time_seconds' => $timeSeconds,
                'finished_at' => $finishedAt
            ]);

            return $quiz->fresh();
        });
    }
}
