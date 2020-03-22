<?php


namespace App;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use YouTube\YouTubeDownloader;

/**
 * Class LicenseAgreementService
 * @package App\Services
 */
class YoutubeService
{
    public function getLinks(string $url): array
    {
        $yt = new YouTubeDownloader();
        return $yt->getDownloadLinks($url);
    }

    public function getVideoId(string $url): string
    {
        $params = explode('&', parse_url($url)['query']);
        $id = null;
        foreach ($params as $param) {
            if (preg_match('/(v=)/', $param)) {
                return str_replace('v=', '', $param);
            }
        }

        throw new \Exception('Something went wrong');
    }

    public function getMostQualityVideoUrl(array $links): string
    {
        return collect($links)
            ->map(function ($item) use (&$audio){
                $format = explode(', ', $item['format']);

                if (!isset($format[2]) || preg_match('/audio/', $format[2])) {
                    return null;
                }

                return [
                    'url' => $item['url'],
                    'type' => preg_match('/(video\/|\/)/', $format[2]) ? $format[2] : $format[2].'/'.$format[0],
                    'size' => @intval(trim($format[1])),
                ];
            })
            ->filter(fn ($v) => $v)
            ->filter(fn ($v) => $v['type'] === 'video/mp4')
            ->sort(fn ($a, $b) => $a['size'] < $b['size'] )
            ->first()['url'];
    }

    public function getAudioUrl(array $links): string
    {
        return collect($links)
            ->filter(fn ($v) => preg_match('/(m4a, audio)/', $v['format']))
            ->first()['url'];
    }

    /**
     * @param array $links
     * @param string $id
     * @return string
     */
    public function download(string $directUrl, string $filePath, \Closure $progress = null)
    {
        if (!file_exists($filePath)) {
            (new FileService())->download($directUrl, $filePath, $progress);
        }

        return $filePath;
    }
}
