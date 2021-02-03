<h1>{{ $title }}</h1>
<p>{!! $content !!}</p>
<a href="{{ url('/', [], config('app.env') !== 'local') }}">Youtube Downloader</a>
