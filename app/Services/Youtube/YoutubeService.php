<?php


namespace App\Services\Youtube;

use App\Services\FileService;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
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
     * @return array
     * @throws \Exception
     */
    public function getLinks(string $url): array
    {
        $yt = new YouTubeDownloader();
        return $yt->getDownloadLinks($this->getVideoId($url));
    }

    /*
     * Get the video information
     * return array
     */
    public function getVideoInfo(string $url)
    {
        $html = new Crawler((new YouTubeDownloader())->getPageHtml($url));
        return [
            'title' => $html->filter('meta[name="twitter:title"]')->attr('content'),
            'description' => $html->filter('meta[name="twitter:description"]')->attr('content')
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
        $id = null;
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
        return collect($links)
            ->map(function ($item) use (&$audio){
                $format = explode(', ', $item['format']);

                if (!isset($format[2]) || preg_match('/audio/', $format[2]) || count($format) < 3) {
                    return null;
                }

                return [
                    'url' => $item['url'],
                    'type' => 'video/'.$format[0],
                    'size' => @intval(trim($format[count($format)])) ?? 0,
                ];
            })
            ->filter(fn ($v) => $v)
            ->sort(fn ($a, $b) => $a['size'] < $b['size'] )
            ->first()['url'];
    }

    /**
     * @param array $links
     * @return string
     */
    public function getAudioUrl(array $links): string
    {
        $url = collect($links)
            ->filter(fn ($v) => preg_match('/(m4a, audio)/', $v['format']))
            ->first();
        logs()->info($links);
        return $url['url'] ?? '';
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
