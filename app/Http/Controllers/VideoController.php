<?php

namespace App\Http\Controllers;

use App\Events\UploadingFile\ProgressEvent;
use App\File;
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('videos', [
            'videos' => File::query()->where('type', 'video/audio')->get()
        ]);
    }

    public function video(Request $request, $id)
    {
        $ytv = new YoutubeVideo('https://www.youtube.com/watch?v='.$id);

        return view('video', [
            'data' => [
                [
                    'url' => route('videos.play', ['id' => $id]),
                    'type' => 'video/mp4',
                    'size' => '1080',
                    'model' => $ytv->getModel(true)
                ]
            ]
        ]);
    }

    public function play(Request $request, $id)
    {
        $ytv = new YoutubeVideo('https://www.youtube.com/watch?v='.$id);

        if ($request->get('type') === 'audio') {
            VideoStreamer::streamFile($ytv->downloadAudio());
            return;
        }

        VideoStreamer::streamFile($ytv->download());
    }
}
