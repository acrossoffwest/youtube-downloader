<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
