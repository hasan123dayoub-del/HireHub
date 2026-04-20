<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProposalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $user = $request->user();

        $isOwner = $user && $user->id === $this->user_id;
        $isClient = $user && $user->id === ($this->project->user_id ?? null);

        return [
            'id'            => $this->id,
            'amount'        => (float) $this->amount,
            'delivery_days' => (int) $this->delivery_days,
            'status'        => $this->status,

            'cover_letter' => $this->when(
                $isOwner || $isClient,
                $this->cover_letter
            ),

            'freelancer'   => new UserResource($this->whenLoaded('freelancer')),
            'project'      => new ProjectResource($this->whenLoaded('project')),

            'accepted_data' => $this->when($this->status === 'accepted', [
                'accepted_at' => $this->updated_at->format('M d, Y'),

                'attachments' => FileResource::collection($this->whenLoaded('attachments')),
            ]),

            'owner_meta' => $this->when($isOwner, [
                'views_count' => $this->views_count ?? 0,
            ]),

            'submitted_at' => $this->created_at->format('M d, Y'),
        ];
    }
}
