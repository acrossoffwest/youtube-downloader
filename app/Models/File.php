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
        'left_days',
        'title',
        'description',
    ];

    protected $appends = [
        'left_days'
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
        return $this->created_at->diffInDays(Carbon::now()->subWeek());
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
