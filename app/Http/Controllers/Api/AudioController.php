<?php

namespace App\Http\Controllers\Api;

use App\Events\UploadingFile\LoadingVideoStartedEvent;
use App\Jobs\Video\CallbackAfterLoadingJob;
use App\Jobs\Video\LoadAudioJob;
use App\Models\File;
use App\Http\Controllers\Controller;
use App\Http\Requests\YoutubeUrlRequest;
use App\Jobs\Video\StartLoadingJob;
use App\Services\Youtube\YoutubeVideoService;

class AudioController extends Controller
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
     * Run uploading audio from Youtube
     *
     * @OA\Post(
     *     path="/videos/audio",
     *     tags={"File"},
     *     summary="Run uploading of audio only",
     *     description="Run uploading of audio from YouTube by URL",
     *     operationId="videos.uploading.run.audio",
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
     *              "title"="Test audio",
     *              "description"="Description of Test audio",
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
        $file = $ytv->getModel();

        if (!$file->uploaded) {
            dispatch(new LoadAudioJob($ytv))->chain([
                new CallbackAfterLoadingJob($ytv)
            ]);
        }

        return response($file->fresh());
    }
}
