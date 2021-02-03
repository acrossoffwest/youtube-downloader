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
     *
     * @OA\Get(
     *     path="/videos",
     *     tags={"File"},
     *     summary="Load all video models",
     *     description="Load all video models",
     *     operationId="videos",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/File"),
     *              example={
                        {
                        "id": 1,
                        "youtube_id": "Yd7kjmvQS1Y",
                        "uploaded": 1,
                        "title": null,
                        "description": null
                        },
                        {
                        "id": 2,
                        "youtube_id": "wHtH_SHhc6E",
                        "uploaded": 1,
                        "title": null,
                        "description": null
                        },
     *     }
     *         )
     *     )
     * )
     *
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $videos = File::query()
            ->where('type', 'video/audio');

        if (auth()->guest()) {
            $videos = $videos->whereNull('user_id');
        } else {
            $videos = $videos->byUserId(auth()->user()->id);
        }

        return response()
            ->json($videos->get());
    }

    /**
     * Run uploading video from Youtube
     *
     * @OA\Post(
     *     path="/videos",
     *     tags={"File"},
     *     summary="Run uploading of video",
     *     description="Run uploading of video from YouTube by URL",
     *     operationId="videos.uploading.run",
     *     @OA\RequestBody(
     *         description="YouTube video's URL",
     *         required=true,
     *         @OA\JsonContent(
     *          required={"url"},
     *          @OA\Property (
     *              property="url",
     *              title="URL",
     *              type="string",
     *              description="YouTube video URL"
     *          )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(
     *          ref="#/components/schemas/File",
     *          example={
     *              "id"="2",
     *              "uploaded"="1",
     *              "youtube_id"="X_sby-AczxCkl",
     *              "title"="Test video",
     *              "description"="Description of Test video",
     *          }
     *         ),
     *
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity. Empty request body or invalid URL",
     *         @OA\JsonContent(
     *              example={
                        "errors": {
                            "url": {
                                "The url format is invalid."
                            }
                        }
                    }
 *              )
     *     )
     * )
     *
     * @param YoutubeUrlRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function runUploading(YoutubeUrlRequest $request)
    {
        $ytv = new YoutubeVideoService($request->get('url'), auth()->id());

        if (!$ytv->getModel()->uploaded) {
            event(new LoadingVideoStartedEvent($ytv->getModel(true)));
            dispatch(new StartLoadingJob($ytv));
        }

        return response($ytv->getModel()->fresh());
    }
}
