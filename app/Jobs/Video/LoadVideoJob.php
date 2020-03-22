<?php

namespace App\Jobs\Video;

use App\YoutubeVideo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LoadVideoJob extends AbstractVideo
{
    public function __construct(YoutubeVideo $video)
    {
        parent::__construct($video);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->video->downloadVideo();
        logs()->info('Video id: "'.$this->video->getId().'" video loaded.');
    }
}
