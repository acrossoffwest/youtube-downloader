@extends('base')

@section('content')
<div class="title m-b-md">
    Progress Bar
</div>
<div class="">
    File loading: <br><progress-bar
        style="width: 140px"
        :options="options"
        :value="value"
    />
</div>
@stop
