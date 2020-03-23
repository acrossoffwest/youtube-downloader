<?php

namespace App\Http\Controllers\Api;

use App\Events\UploadingFile\LoadingVideoStartedEvent;
use App\Models\File;
use App\Http\Controllers\Controller;
use App\Http\Requests\YoutubeUrlRequest;
use App\Jobs\Video\StartLoadingJob;
use App\Services\Youtube\YoutubeVideoService;

class VideoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Get all videos rows
     *
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

    /**
     * Run uploading video from Youtube
     *
     * @param YoutubeUrlRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function runUploading(YoutubeUrlRequest $request)
    {
        $ytv = new YoutubeVideoService($request->get('url'));

        if (!$ytv->getModel()->uploaded) {
            event(new LoadingVideoStartedEvent($ytv->getModel(true)));
            dispatch(new StartLoadingJob($ytv));
        }

        return response($ytv->getModel()->fresh());
    }
}
