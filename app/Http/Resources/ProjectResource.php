<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => str($this->description)->limit(150),
            'budget' => [
                'raw' => $this->budget,
                'formatted' => number_format($this->budget, 0) . " $",
            ],
            'client' => [
                'name' => $this->user->name,
                'avatar' => $this->user->avatar_url ?? 'default.png',
                'rating' => round($this->user->rating_cache ?? 5.0, 1),
            ],
            'proposals_count' => $this->proposals_count ?? 0,

            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'status' => $this->status,
            'published_at' => [
                'human' => $this->created_at->diffForHumans(),
                'date' => $this->created_at->format('Y-m-d'),
            ],
        ];
    }
}
