<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class FileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'file_name'  => $this->file_name,
            'file_url'   => asset('storage/' . $this->file_path),
            'file_size'  => $this->file_size,
            'extension'  => pathinfo($this->file_path, PATHINFO_EXTENSION),
            'created_at' => $this->created_at->format('Y-m-d H:i'),
        ];
    }
}
