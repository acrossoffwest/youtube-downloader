<?php

namespace App\Jobs\Video;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StartLoadingJob extends AbstractVideo
{
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $chain = [
            new LoadAudioJob($this->video),
            (new LoadVideoJob($this->video)),
            new JoinAudioWithVideoJob($this->video)
        ];
        dispatch(new LoadFullVideoJob($this->video))->chain($chain);
    }
}
