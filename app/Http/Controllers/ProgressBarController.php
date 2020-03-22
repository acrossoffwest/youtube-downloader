<?php

namespace App\Http\Controllers;

use App\Events\UploadingFile\ProgressEvent;
use App\ProgressData;
use App\YoutubeVideo;
use Illuminate\Http\Request;
use Iman\Streamer\VideoStreamer;

class ProgressBarController extends Controller
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

    public function progress()
    {
        return view('progress-bar');
    }
}
