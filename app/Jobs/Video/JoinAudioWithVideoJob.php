<?php

namespace App\Jobs\Video;

use App\Mail\VideoDownloaded;
use Illuminate\Support\Facades\Mail;

class JoinAudioWithVideoJob extends AbstractVideo
{
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->video
            ->joinVideoWithAudio(
                $this->video->getAudioPath(),
                $this->video->getVideoPath()
            );

        $videoModel = $this->video->getModel(true);
        Mail::to($videoModel->user->email)->send(new VideoDownloaded($videoModel));
    }
}
