<?php


namespace App;


use App\Events\UploadingFile\ProgressEvent;

class YoutubeVideo
{
    private string $url;
    private array $links;
    private YoutubeService $service;

    public function __construct(string $url)
    {
        $this->service = new YoutubeService();
        $this->url = $url;
        $this->links = $this->service->getLinks($url);
    }

    public function getId()
    {
        return $this->service->getVideoId($this->getUrl());
    }

    public function getVideoUrl()
    {
        return $this->service->getMostQualityVideoUrl($this->getLinks());
    }

    public function getAudioUrl()
    {
        return $this->service->getAudioUrl($this->getLinks());
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getLinks(): array
    {
        return $this->links;
    }

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

    public function getFullVideoFilename()
    {
        return $this->getId().'-full.mp4';
    }

    public function getAudioFilename()
    {
        return $this->getId().'.m4a';
    }

    public function getVideoFilename()
    {
        return $this->getId().'.mp4';
    }
    protected File $model;
    public function getModel(bool $fresh = false)
    {
        if (!empty($this->model) && !$fresh) {
            return $this->model;
        }

        /** @var File $file */
        $file = File::query()->where('youtube_id', $this->getId())->first();
        if (!empty($file)) {
            return $this->model = $file;
        }

        $file = File::query()->create([
            'full_video_filename' => $this->getFullVideoFilename(),
            'video_filename' => $this->getVideoFilename(),
            'audio_filename' => $this->getAudioFilename(),
            'youtube_id' => $this->getId()
        ]);
        return $this->model = $file;
    }

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

    public function getAudioPath(): string
    {
        return $this->getPath($this->getAudioFilename());
    }

    public function getVideoPath(): string
    {
        return $this->getPath($this->getVideoFilename());
    }

    public function getFullVideoPath(): string
    {
        return $this->getPath($this->getFullVideoFilename());
    }

    private function getPath(string $relativePath): string
    {
        return storage_path('app/public/'.$relativePath);
    }
}
