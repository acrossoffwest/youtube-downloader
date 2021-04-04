<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class File
 * @package App\Models
 * @property User $user
 * @property Carbon $created_at
 * @property string $callback_url
 * @property bool $uploaded
 * @property string $youtube_id
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
        'callback_url',
        'description',
    ];

    protected $visible = [
        'id',
        'youtube_id',
        'uploaded',
        'user_id',
        'left_days',
        'left_minutes',
        'title',
        'video_url',
        'audio_url',
        'callback_url',
        'description',
    ];

    protected $appends = [
        'left_days',
        'left_minutes',
        'video_url',
        'audio_url',
    ];

    public function scopeByUserId(Builder $q, int $userId)
    {
        return $q->where('user_id', $userId);
    }

    public function scopeWithoutUser(Builder $q)
    {
        return $q->whereNull('user_id');
    }

    public function getLeftDaysAttribute()
    {
        return $this->user_id ? $this->created_at->diffInDays(Carbon::now()->subWeek()) : null;
    }

    public function getLeftMinutesAttribute()
    {
        return $this->created_at->diffInMinutes(Carbon::now()->subHour());
    }

    public function getAudioUrlAttribute()
    {
        return route('videos.download.audio', [
            'id' => $this->youtube_id
        ]);
    }

    public function getVideoUrlAttribute()
    {
        return route('videos.download.video', [
            'id' => $this->youtube_id
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
