<?php

namespace App\Http\Controllers;

use App\Services\Youtube\YoutubeVideoService;
use Illuminate\Support\Str;

/**
 * Class DownloadFileController
 * @package App\Http\Controllers
 */
class DownloadFileController extends Controller
{
    /**
     * Run video downloading
     *
     * @param string $id
     */
    public function video(string $id)
    {
        $ytv = new YoutubeVideoService('https://www.youtube.com/watch?v='.$id);
        $this->downloadFile($ytv->download(), $ytv->getModel()->title);
    }

    /**
     * Run audio downloading
     *
     * @param string $id
     */
    public function audio(string $id)
    {
        $ytv = new YoutubeVideoService('https://www.youtube.com/watch?v='.$id);
        $this->downloadFile($ytv->downloadAudio(), $ytv->getModel()->title);
    }

    /**
     * Run file downloading
     *
     * @param string $filepath
     */
    private function downloadFile(string $filepath, string $title = '')
    {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.($title !== '' ? Str::slug($title).'-' : '').basename($filepath).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filepath));
        readfile($filepath);
        exit;
    }
}
