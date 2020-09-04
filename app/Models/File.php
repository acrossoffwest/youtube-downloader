<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class File
 * @package App\Models
 */
class File extends Model
{
    protected $fillable = [
        'full_video_filename',
        'video_filename',
        'audio_filename',
        'youtube_id',
        'uploaded',
        'user_id',
        'title',
        'description',
    ];

    protected $visible = [
        'id',
        'youtube_id',
        'uploaded',
        'user_id',
        'title',
        'description',
    ];

    public function scopeByUserId(Builder $q, int $userId)
    {
        return $q->where('user_id', $userId);
    }

    public function scopeWithoutUser(Builder $q)
    {
        return $q->whereNull('user_id');
    }
}
