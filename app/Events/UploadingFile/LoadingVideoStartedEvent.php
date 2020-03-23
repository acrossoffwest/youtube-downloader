<?php


namespace App\Events\UploadingFile;


use App\Models\File;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class LoadingVideoStartedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public File $file;

    public function __construct(File $file)
    {
        $this->file = $file;
    }

    public function broadcastOn()
    {
        return ['uploading-file'];
    }

    public function broadcastAs()
    {
        return 'new';
    }
}

