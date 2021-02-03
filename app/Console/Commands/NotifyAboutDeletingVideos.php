<?php

namespace App\Console\Commands;

use App\Mail\VideoWillDeletedSoon;
use App\Models\File;
use App\Services\Youtube\YoutubeVideoService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class NotifyAboutDeletingVideos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:expired:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify about soon deleting video';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $files = File::query()->whereNotNull('user_id')->where('created_at', '<', Carbon::now()->subWeek()->addDays(2))->get();
        /** @var File $file */
        foreach ($files as $file) {
            Mail::to($file->user->email)->send(new VideoWillDeletedSoon($file));
        }
        return 0;
    }
}
