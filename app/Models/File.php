<?php

namespace App\Models;

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
        'title',
        'description',
    ];
}
