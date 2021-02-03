<?php

namespace App\Console\Commands;

use App\Models\File;
use App\Services\Youtube\YoutubeVideoService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteExpiredMediaDownloadedByUnauthorizedUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unauthorized:users:media:expired:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete media with expired life time of unauthorized users';

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
        $files = File::query()->where('created_at', '<', Carbon::now()->subHour())->whereNull('user_id')->get();
        /** @var File $file */
        foreach ($files as $file) {
            $ytv = new YoutubeVideoService('https://www.youtube.com/watch?v='.$file->youtube_id);
            $ytv->deleteAll();
            $file->delete();
        }
        return 0;
    }
}
