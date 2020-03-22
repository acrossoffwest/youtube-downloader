<?php

namespace App\Jobs\Video;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class JoinAudioWithVideoJob extends AbstractVideo
{
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->video->joinVideoWithAudio($this->video->getAudioPath(), $this->video->getVideoPath());
        logs()->info('"'.$this->video->getId().'": video and audio joined.');
    }
}
