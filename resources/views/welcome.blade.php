@extends('base')

@section('content')
<div class="row text-center mt-40">
    <div class="col-md-12">
        <div class="title m-b-md">
            {{ config('app.name') }}
        </div>
        <div class="links">
            <a href="{{ route('videos.index') }}">Go to videos list</a>
            <a href="https://youtube.com">Youtube</a>
            <a href="https://github.com/laravel/laravel">Based on Laravel</a>
        </div>
    </div>
</div>
@stop
