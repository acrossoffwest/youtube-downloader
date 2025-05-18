<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Services\Youtube\YoutubeVideoService;
use Illuminate\Http\Request;
use Iman\Streamer\VideoStreamer;
use App\Services\Youtube\YoutubeService;

/**
 * Class VideoController
 * @package App\Http\Controllers
 */
class LinksController extends Controller
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
    public function index(Request $request, YoutubeService $youtubeService)
    {
        return $youtubeService->getLinks($request->get('url'));
    }
}
