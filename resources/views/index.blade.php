@extends('layout.default')
@section('content')
	@foreach($posts as $post)
    <div class="entry">
    <a class="entry-thumb" href="{{env('UPLOAD_PATH').$post->file}}" title="下载 {{$post->title}}" target="_blank">
      <p><img src="{{env('UPLOAD_PATH').$post->thumb}}"></p>

      <span class="icon icon-forward entry-icon"></span>
    </a>
    <div class="entry-content">
      <h2 class="entry-title">
        <a href="{{env('UPLOAD_PATH').$post->file}}">{{$post->title}}</a>
      </h2>
      <p class="entry-date">点击下载</p>
    </div>
  </div>
  @endforeach
  {!! $posts->render() !!}
@endsection

