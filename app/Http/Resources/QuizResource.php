<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'user_id'=>$this->user_id,
            'total_questions'=>$this->total_questions,
            'correct_count'=>$this->correct_count,
            'wrong_count'=>$this->wrong_count,
            'score'=>$this->score,
            'time_seconds'=>$this->time_seconds,
            'started_at'=>$this->started_at,
            'finished_at'=>$this->finished_at,
            'question_ids'=>$this->question_ids
        ];
    }
}
