<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'type' => $this->type,
            'genres' => $this->genres,
            'rating' => (float) $this->rating,
            'year' => $this->year,
            'synopsis' => $this->synopsis,
            'users' => UserResource::collection($this->whenLoaded('users')),
        ];
    }
}
