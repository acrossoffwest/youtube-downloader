<?php


namespace App\Events\UploadingFile;


use App\DataStructures\ProgressData;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ProgressEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public ProgressData $progressData;
    public $id;

    public function __construct(ProgressData $progressData)
    {
        $this->progressData = $progressData;
    }

    public function broadcastOn()
    {
        return ['uploading-file.'.$this->progressData->id];
    }

    public function broadcastAs()
    {
        return 'progress';
    }
}

