@extends('layout.default')
@section('content')
	@foreach($posts as $post)
    <div class="entry">
    <a class="entry-thumb" href="@if($post->mimes==0){{env('UPLOAD_PATH').$post->file}} @else {{url('panoimage', [$post->id])}} @endif" title="预览 {{$post->title}}" target="_blank">
      <p><img src="{{env('UPLOAD_PATH').$post->thumb}}"></p>

      <span class="icon icon-forward entry-icon"></span>
    </a>
    <div class="entry-content">
      <h2 class="entry-title">
        <a href="{{url('download', [$post->id])}}">{{$post->title}}</a>
      </h2>
      <p class="entry-date">点击标题下载</p>
    </div>
  </div>
  @endforeach
  @include('include.pagination')
@endsection

