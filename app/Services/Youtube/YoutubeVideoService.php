<?php


namespace App\Services\Youtube;

use App\Events\UploadingFile\ProgressEvent;
use App\Models\File;
use App\DataStructures\ProgressData;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class YoutubeVideoService
 * @package App\Services\Youtube
 */
class YoutubeVideoService
{
    private string $url;
    private array $links;
    private YoutubeService $service;
    protected File $model;
    protected $userId;

    /**
     * YoutubeVideoService constructor.
     * @param string $url
     * @throws \Exception
     */
    public function __construct(string $url, int $userId = null)
    {
        $this->service = new YoutubeService();
        $this->url = $url;
        $this->userId = $userId;
        $this->links = $this->service->getLinks($url);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getId()
    {
        return $this->service->getVideoId($this->getUrl());
    }

    /**
     * @return string
     */
    public function getVideoUrl()
    {
        return $this->service->getMostQualityVideoUrl($this->getLinks());
    }

    /**
     * @return string
     */
    public function getAudioUrl()
    {
        return $this->service->getAudioUrl($this->getLinks());
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return array
     */
    public function getLinks(): array
    {
        return $this->links;
    }

    /**
     * @return string
     */
    public function download()
    {
        $model = $this->getModel(true);

        if (file_exists($this->getFullVideoPath())) {
            if (!$model->uploaded) {
                $model->uploaded = true;
                $model->save();
            }
            return $this->getFullVideoPath();
        }

        return $this->joinVideoWithAudio($this->downloadAudio(), $this->downloadVideo());
    }

    /**
     * @param string $audioPath
     * @param string $videoPath
     * @return string
     */
    public function joinVideoWithAudio(string $audioPath, string $videoPath): string
    {
        $fullVideo = $this->getPath($this->getFullVideoFilename());


        if (!file_exists($fullVideo)) {
            $cmd = 'ffmpeg -i '.$videoPath.' -i '.$audioPath.' -c:v copy -c:a aac -strict experimental '.$fullVideo;
            exec($cmd, $out);
        }

        if (!$this->getModel(true)->uploaded) {
            $this->getModel()->uploaded = true;
            $this->getModel()->save();
        }

        return $fullVideo;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getFullVideoFilename()
    {
        return $this->getId().'-full.mp4';
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getAudioFilename()
    {
        return $this->getId().'.m4a';
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getVideoFilename()
    {
        return $this->getId().'.mp4';
    }

    /**
     * @param bool $fresh
     * @return File
     * @throws \Exception
     */
    public function getModel(bool $fresh = false)
    {
        if (!empty($this->model) && !$fresh) {
            return $this->model;
        }

        /** @var Builder $fileQuery */
        $fileQuery = File::query()->where('youtube_id', $this->getId());

        if ($this->userId) {
            $fileQuery = $fileQuery->where('user_id', $this->userId);
        } else {
            $fileQuery = $fileQuery->whereNull('user_id');
        }

        /** @var File $file */
        $file = $fileQuery->first();
        if (!empty($file)) {
            return $this->model = $file;
        }

        $file = File::query()->create(array_merge([
            'full_video_filename' => $this->getFullVideoFilename(),
            'video_filename' => $this->getVideoFilename(),
            'audio_filename' => $this->getAudioFilename(),
            'youtube_id' => $this->getId(),
            'user_id' => $this->userId
        ], $this->service->getVideoInfo($this->url)));
        return $this->model = $file;
    }

    /**
     * @return string
     */
    public function downloadAudio()
    {
        $prevPercent = 0;
        return $this->service->download($this->getAudioUrl(), $this->getAudioPath(), function ($totalBytes, $restBytes) use (&$prevPercent) {
            if (!$totalBytes) {
                return;
            }
            $status = 'Loading audio';
            $percent = floor(($restBytes / ($totalBytes * 0.01)));
            if ($percent === $prevPercent) {
                return;
            }
            event(new ProgressEvent(new ProgressData($this->getId(), $percent, $status)));
            $prevPercent = $percent;
        });
    }

    /**
     * @return string
     */
    public function downloadVideo()
    {
        $prevPercent = 0;
        return $this->service->download($this->getVideoUrl(), $this->getVideoPath(), function ($totalBytes, $restBytes) use (&$prevPercent) {
            if (!$totalBytes) {
                return;
            }
            $percent = floor(($restBytes / ($totalBytes * 0.01)));
            $status = 'Loading video';
            if ($percent === $prevPercent) {
                return;
            }
            event(new ProgressEvent(new ProgressData($this->getId(), $percent, $status)));
            $prevPercent = $percent;
        });
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getAudioPath(): string
    {
        return $this->getPath($this->getAudioFilename());
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getVideoPath(): string
    {
        return $this->getPath($this->getVideoFilename());
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getFullVideoPath(): string
    {
        return $this->getPath($this->getFullVideoFilename());
    }

    /**
     * @param string $relativePath
     * @return string
     */
    private function getPath(string $relativePath): string
    {
        return storage_path('app/public/'.$relativePath);
    }
}
