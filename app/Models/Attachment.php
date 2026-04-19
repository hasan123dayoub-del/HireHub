<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Attachment extends Model
{
    protected $fillable = [
        'file_path',
        'file_name',
        'attachable_id',
        'attachable_type'
    ];
    public function attachable()
    {
        return $this->morphTo();
    }
    protected function fileUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => asset('storage/' . $this->file_path),
        );
    }
    protected $appends = ['file_url'];
}
