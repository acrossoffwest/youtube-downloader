<?php

namespace App\Jobs\Video;

use App\Events\UploadingFile\LoadingVideoStartedEvent;
use App\Services\Youtube\YoutubeVideoService;

class LoadFullVideoJob extends AbstractVideo
{
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $model = $this->video->getModel(true);
        logs()->info('"'.$this->video->getId().'": Model created');
    }
}
