<?php

namespace App\Jobs\Video;

use App\Services\Youtube\YoutubeVideoService;

/**
 * Class LoadAudioJob
 * @package App\Jobs\Video
 */
class LoadAudioJob extends AbstractVideo
{
    protected bool $withProgressEvent = true;

    public function __construct(YoutubeVideoService $video, bool $withProgressEvent = true)
    {
        parent::__construct($video);
        $this->withProgressEvent = $withProgressEvent;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->video->downloadAudio($this->withProgressEvent);
    }
}
