<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProposalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $user = $request->user();

        return [
            'id' => $this->id,
            'amount' => (float) $this->amount,
            'delivery_days' => (int) $this->delivery_days,
            'status' => $this->status,

            'cover_letter' => $this->when(
                $user && ($user->id === $this->user_id || $user->id === $this->project->user_id),
                $this->cover_letter
            ),

            'freelancer' => new UserResource($this->whenLoaded('freelancer')),
            'project' => new ProjectResource($this->whenLoaded('project')),
            'submitted_at' => $this->created_at->format('M d, Y'),
        ];
    }
}
