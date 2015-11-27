@extends('layout.page')
@section('pagecontent')
    <p>
    我们将会审核您上传的视频，请确保上传清晰，有观赏性的360度全景视频，并且不侵犯他人权益和互联网规范。
    上传视频一旦发布，表示将会免费提供他人自由观看、下载。谢谢！
    </p>
 <form name="file" action="/upload" method="post" enctype="multipart/form-data">
  <div class="form-group">
    <label for="inputFile">上传</label>
    <input type="file" id="inputFile" name="file">
    <p class="help-block">视频文件格式mp4。</p>
    {!! csrf_field() !!}
  </div>
  <button type="submit" class="btn btn-primary">提交</button>
</form>
@endsection

