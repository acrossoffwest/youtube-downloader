@extends('base')

@section('content')
<div class="text-center">
    <div class="row mt-20">
        <div class="col-md-12">
            <div class="title m-b-md">
                Videos
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <video-download-form-component></video-download-form-component>
            <videos-list-component></videos-list-component>
            <small style="text-align: left">
                * Videos will delete after one week
            </small>
        </div>
    </div>
</div>
@stop
