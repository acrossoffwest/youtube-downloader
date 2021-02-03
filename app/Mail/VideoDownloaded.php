<?php

namespace App\Mail;

use App\Models\File;
use http\Url;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VideoDownloaded extends Mailable
{
    use Queueable, SerializesModels;

    public File $video;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(File $video)
    {
        $this->video = $video;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('yd@m.aow.space', 'Videos Downloader')->view('mail', [
            'title' => 'Video: "'.$this->video->title.'" downloaded.',
            'content' => '<b>Link to video</b>: <a href="'.route('videos.show', ['id' => $this->video->youtube_id]).'">'.$this->video->title.'</a>'
        ]);
    }
}
