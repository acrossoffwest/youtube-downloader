<?php

namespace App\Jobs\Video;

use App\Http\Resources\File;
use Illuminate\Support\Facades\Http;

/**
 * Class CallbackAfterLoadingJob
 * @package App\Jobs\Video
 */
class CallbackAfterLoadingJob extends AbstractVideo
{
    /**
     * @throws \Exception
     */
    public function handle()
    {
        $model = $this->video->getModel(true);

        if (empty($model->callback_url)) {
            return;
        }

        $model->fill(['uploaded' => true])
            ->save();

        try {
            Http::post($model->callback_url, File::make($model)->toArray(null));
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
