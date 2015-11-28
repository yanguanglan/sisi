@extends('layout.default')
@section('content')
	@foreach($posts as $post)
    <div class="entry">
    <a class="entry-thumb" href="{{env('UPLOAD_PATH').$post->file}}" title="预览 {{$post->title}}" target="_blank">
      <p><img src="{{env('UPLOAD_PATH').$post->thumb}}"></p>

      <span class="icon icon-forward entry-icon"></span>
    </a>
    <div class="entry-content">
      <h2 class="entry-title">
        <a href="{{env('UPLOAD_PATH').$post->file}}">{{$post->title}}</a>
      </h2>
      <p class="entry-date">右键点击标题另存为下载</p>
    </div>
  </div>
  @endforeach
  {!! $posts->render() !!}
@endsection

