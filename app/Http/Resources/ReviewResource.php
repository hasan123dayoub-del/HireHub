<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'client_name' => $this->user ? $this->user->name : 'Anonymous',
            'created_at' => $this->created_at->format('Y-m-d'),
        ];
    }
}
