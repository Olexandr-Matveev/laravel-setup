<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'entity',
        'entity_id',
        'file_path',
        'original_name',
    ];

    public function fileable(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'entity', 'entity_id');
    }
}
