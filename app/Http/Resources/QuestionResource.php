<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\AlternativeResource;

class QuestionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'title'=>$this->title,
            'explanation'=>$this->explanation,
            'difficulty'=>$this->difficulty,
            'alternatives'=>AlternativeResource::collection($this->whenLoaded('alternatives', $this->alternatives))
        ];
    }
}
