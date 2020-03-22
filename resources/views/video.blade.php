@extends('base')

@section('content')
<div class="text-center">
    <div class="row">
        <div class="col-xs-12">
            <div class="title m-b-md">
                Youtube Downloader
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <video-player-component style="max-width: 1080px; margin-bottom: 25px; margin-top: 25px;" :items="{{ json_encode($data) }}"></video-player-component>
        </div>
    </div>
    <br>
    <h3>Download</h3>
    <div class="links">
        <a href="{{ route('videos.video.load', ['id' => $data[0]['model']->youtube_id]) }}" style="text-decoration: underline">video</a>
        <a href="{{ route('videos.audio.load', ['id' => $data[0]['model']->youtube_id]) }}" style="text-decoration: underline">audio</a>
    </div>
    <br>
    <div class="row">
        <div class="links">
            <a href="{{ url('/') }}">Go to videos list</a>
            <a href="https://youtube.com">Youtube</a>
            <a href="https://github.com/laravel/laravel">Based on Laravel</a>
        </div>
    </div>
</div>
@stop
