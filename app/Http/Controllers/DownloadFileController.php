<?php

namespace App\Http\Controllers;

use App\YoutubeVideo;
use Illuminate\Http\Request;

class DownloadFileController extends Controller
{
    public function video(Request $request, string $id)
    {
        $ytv = new YoutubeVideo('https://www.youtube.com/watch?v='.$id);
        $this->downloadFile($ytv->download());
    }

    public function audio(Request $request, string $id)
    {
        $ytv = new YoutubeVideo('https://www.youtube.com/watch?v='.$id);
        $this->downloadFile($ytv->downloadAudio());
    }

    private function downloadFile($file)
    {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    }
}
