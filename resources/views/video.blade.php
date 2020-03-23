@extends('base')

@section('content')
    <div class="text-center">
        <div class="row mt-10">
            <div class="col-md-12">
                <div class="title m-b-md">
                    Youtube Downloader
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <video-player-component style="margin-bottom: 25px; margin-top: 25px;" :items="{{ json_encode($data) }}"></video-player-component>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h3>Download</h3>
                <div class="links">
                    <a href="{{ route('videos.download.video', ['id' => $data[0]['model']->youtube_id]) }}" style="text-decoration: underline">video</a>
                    <a href="{{ route('videos.download.audio', ['id' => $data[0]['model']->youtube_id]) }}" style="text-decoration: underline">audio</a>
                </div>
            </div>
        </div>
        <div class="row mt-40">
            <div class="col-md-12">
                <div class="links">
                    <a href="{{ route('videos.index') }}">Go to videos list</a>
                    <a href="https://youtube.com">Youtube</a>
                    <a href="https://github.com/laravel/laravel">Based on Laravel</a>
                </div>
            </div>
        </div>
    </div>
@stop
