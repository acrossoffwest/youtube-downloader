<?php

namespace App\Jobs\Video;

use App\Services\Youtube\YoutubeVideoService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

abstract class AbstractVideo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected YoutubeVideoService $video;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(YoutubeVideoService $video)
    {
        $this->video = $video;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    abstract public function handle();
}
