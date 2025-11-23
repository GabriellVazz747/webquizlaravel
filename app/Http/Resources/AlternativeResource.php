<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class AlternativeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'content'=>$this->content,
            'position'=>$this->position
        ];
    }
}
