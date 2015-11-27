@extends('layout.default')
@section('content')
<div class="page">
  <h1 class="page-title">
    {{ $title }}
  </h1>
  @yield('pagecontent')
</div>
@endsection
