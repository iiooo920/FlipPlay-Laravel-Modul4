<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            // Email/password sengaja tidak dikirim karena tidak diperlukan untuk demo.
            'collection' => MediaResource::collection($this->whenLoaded('media')),
        ];
    }
}
