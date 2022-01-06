<?php


namespace App\Services\Youtube;

use App\Services\FileService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;
use YouTube\Models\StreamFormat;
use YouTube\YouTubeDownloader;

/**
 * Class LicenseAgreementService
 * @package App\Services
 */
class YoutubeService
{
    /**
     * Get videos links
     *
     * @param string $url
     * @return StreamFormat[]
     * @throws \Exception
     */
    public function getLinks(string $url): array
    {
        $yt = new YouTubeDownloader();
        $id = $this->getVideoId($url);
        $downloadOptions = $yt->getDownloadLinks($id);
        return array_merge($downloadOptions->getVideoFormats(), $downloadOptions->getAudioFormats());
    }

    /*
     * Get the video information
     * return array
     * @throws \Exception
     */
    public function getVideoInfo(string $url)
    {
        $apiKey = config('services.youtube.api_key');
        $videoId = $this->getVideoId($url);
        $url = "https://www.googleapis.com/youtube/v3/videos?id=".$videoId."&key=".$apiKey."&part=snippet,contentDetails,statistics,status";
        $data = Http::get($url)->json();

        if (!isset($data['items']) || !isset($data['items'][0])) {
            return [
                'title' => '',
                'description' => ''
            ];
        }

        $item = $data['items'][0]['snippet'];

        return [
            'title' => trim($item['title']),
            'description' => Str::limit(trim($item['description']), 250)
        ];
    }

    /**
     * Get YouTube video ID from URL
     *
     * @param string $url
     * @return string
     * @throws \Exception
     */
    public function getVideoId(string $url): string
    {
        if (preg_match('/(https\:\/\/youtu\.be\/)/', $url)) {
            return str_replace('https://youtu.be/', '', $url);
        }
        $params = explode('&', parse_url($url)['query']);
        foreach ($params as $param) {
            if (preg_match('/(v=)/', $param)) {
                return str_replace('v=', '', $param);
            }
        }

        throw new \Exception('Something went wrong');
    }

    /**
     * @param array $links
     * @return string
     */
    public function getMostQualityVideoUrl(array $links): string
    {
        $result = collect($links)
            ->map(function (StreamFormat $item) use (&$audio){
                if (preg_match('/audio/', $item->getCleanMimeType())) {
                    return null;
                }
                return [
                    'url' => $item->url,
                    'type' => $item->getCleanMimeType(),
                    'size' => $item->contentLength,
                ];
            })
            ->filter(fn ($v) => $v)
            ->sort(fn ($a, $b) => $a['size'] <=> $b['size'] )
            ->last();

        return $result['url'];
    }

    /**
     * @param StreamFormat[] $links
     * @return string
     */
    public function getAudioUrl(array $links): string
    {
        /** @var StreamFormat $streamFormat */
        $streamFormat = collect($links)
            ->filter(function (StreamFormat $v) {
                return preg_match('/audio/', $v->getCleanMimeType());
            })
            ->first();
        return $streamFormat->url;
    }

    /**
     * @param string $directUrl
     * @param string $filePath
     * @param \Closure|null $progress
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
