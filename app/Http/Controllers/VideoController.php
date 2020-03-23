<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Services\Youtube\YoutubeVideoService;
use Illuminate\Http\Request;
use Iman\Streamer\VideoStreamer;

/**
 * Class VideoController
 * @package App\Http\Controllers
 */
class VideoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * List of loaded(or loading) videos
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('videos', [
            'videos' => File::query()->where('type', 'video/audio')->get()
        ]);
    }

    /**
     * Single page for watching loaded video with links for downloading video or audio
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $ytv = new YoutubeVideoService('https://www.youtube.com/watch?v='.$id);

        return view('video', [
            'data' => [
                [
                    'url' => route('videos.stream', ['id' => $id]),
                    'type' => 'video/mp4',
                    'size' => '1080',
                    'model' => $ytv->getModel(true)
                ]
            ]
        ]);
    }

    /**
     * Video/audio stream
     *
     * @param Request $request
     * @param $id
     * @throws \Exception
     */
    public function stream(Request $request, $id)
    {
        $ytv = new YoutubeVideoService('https://www.youtube.com/watch?v='.$id);

        if ($request->get('type') === 'audio') {
            VideoStreamer::streamFile($ytv->downloadAudio());
            return;
        }

        VideoStreamer::streamFile($ytv->download());
    }
}
