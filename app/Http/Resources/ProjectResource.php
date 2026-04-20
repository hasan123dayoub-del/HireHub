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
                'raw' => $this->budget_amount,
                'formatted' => number_format($this->budget_amount ?? 0, 0) . " $",
            ],
            'client' => [
                'name' => optional($this->user)->name,
                'avatar' => optional($this->user)->avatar_url ?? 'default.png',
                'rating' => round(optional($this->user)->rating_cache ?? 5.0, 1),
            ],
            'proposals_count' => $this->proposals_count ?? 0,

            'tags' => TagResource::collection($this->whenLoaded('tags')),

            'status' => $this->status ?? 'open',

            'published_at' => [
                'human' => $this->created_at->diffForHumans(),
                'date' => $this->created_at->format('Y-m-d'),
            ],
        ];
    }
}
