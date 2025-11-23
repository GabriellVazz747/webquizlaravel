<?php
namespace App\Http\Controllers\Api;

use App\Models\Question;
use App\Http\Resources\QuestionResource;

class QuestionController
{
    public function show(Question $question)
    {
        $question->load([
            'alternatives' => function($q) {
                $q->select('id','question_id','content','position')
                  ->orderBy('position');
            }
        ]);

        return new QuestionResource($question);
    }
}
