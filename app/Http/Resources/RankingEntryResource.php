<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class RankingEntryResource extends JsonResource
{
    public function toArray($request)
    {
        // Accept both stdClass from query and model
        return [
            'user_id' => $this['id'] ?? $this->id,
            'name' => $this['name'] ?? $this->name,
            'score' => $this['score'] ?? $this->score,
            'time_seconds' => $this['time_seconds'] ?? $this->time_seconds,
        ];
    }
}
