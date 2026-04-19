<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StatsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'total_users'     => $this['total_users'],
            'total_projects'  => $this['total_projects'],
            'total_proposals' => $this['total_proposals'],
            'total_revenue'   => [
                'formatted' => number_format($this['total_value'], 2) . " $",
                'raw'       => $this['total_value'],
                'currency'  => 'USD'
            ],
            'last_synced'     => now()->diffForHumans(),
        ];
    }
}
