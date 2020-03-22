<?php

namespace App\Http\Controllers\Api;

use App\Events\UploadingFile\LoadingVideoStartedEvent;
use App\Events\UploadingFile\ProgressEvent;
use App\File;
use App\Http\Controllers\Controller;
use App\Jobs\Video\StartLoadingJob;
use App\ProgressData;
use App\YoutubeVideo;
use Illuminate\Http\Request;
use Iman\Streamer\VideoStreamer;

class VideoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $videos = File::query()
            ->where('type', 'video/audio')
            ->get();

        return response()
            ->json($videos);
    }

    public function download(Request $request)
    {
        $ytv = new YoutubeVideo($request->get('url'));

        if (!$ytv->getModel()->uploaded) {
            event(new LoadingVideoStartedEvent($ytv->getModel(true)));
            dispatch(new StartLoadingJob($ytv));
        }

        return response($ytv->getModel()->fresh());
    }
}
