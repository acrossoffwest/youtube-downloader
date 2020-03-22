<?php

namespace App\Http\Controllers;

use App\Events\UploadingFile\ProgressEvent;
use App\ProgressData;
use App\YoutubeVideo;
use Illuminate\Http\Request;
use Iman\Streamer\VideoStreamer;

class HomeController extends Controller
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
        return view('home');
    }

    public function play(Request $request)
    {
        $ytv = new YoutubeVideo($request->get('url'));

        if ($request->get('type') === 'audio') {
            VideoStreamer::streamFile($ytv->downloadAudio());
            return;
        }
        VideoStreamer::streamFile($ytv->download());
    }

    public function runTrigger(Request $request)
    {
        $data = new ProgressData('id', $request->get('percent'));
        event(new ProgressEvent($data));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function player(Request $request)
    {
        return view('welcome', [
            'data' => [
                [
                    'url' => url('/play.mp4?url='.$request->get('url')),
                    'type' => 'video/mp4',
                    'size' => '1080'
                ]
            ]
        ]);
    }
}
