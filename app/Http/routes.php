<?php
use App\Post;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/thumb', function () {
    $directory = '/alidata1/media/youtube';
    $files = File::allFiles($directory);
        //上传
    $destinationPath = base_path() . '/public/Uploads/thumb'; // upload path

    foreach ($files as $file)
    {
        $filehash = md5($file->getFileName());
        $subpath = '/'.substr($filehash, 0, 2) . '/' . substr($filehash, 2, 2);
        
        //不存在则新建
        if(!file_exists($destinationPath . $subpath)){
            mkdir($destinationPath . $subpath,0777,true);
        }

        $thumbfilename = $destinationPath . $subpath . '/thumb_'.$filehash . '.' . 'jpg';
        $ffmpeg = \FFMpeg\FFMpeg::create(array(
        'ffmpeg.binaries'  => env('FFMPEG_BIN'),
        'ffprobe.binaries' => env('FFPROBE_BIN'),
        'timeout'          => 3600, // The timeout for the underlying process
        'ffmpeg.threads'   => 12,   // The number of threads that FFMpeg should use
        ));
       $video = $ffmpeg->open($file->getPathName());
       $frame = $video->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds(20));
       $frame->save($thumbfilename);
       \Image::make($thumbfilename)->resize(1200, 900)->save($thumbfilename);
       $filethumb = '/thumb'. $subpath . '/thumb_' . $filehash . '.jpg';
       $filepath = '/media/youtube/';

        Post::create([
        'title' => str_replace(['.mp4', '.MP4'], ['',''], $file->getFileName()),
        'tag' => '纪录片',
        'type' => 'pano',
        'file' => $filepath . $file->getFileName(),
        'thumb' => $filethumb,
        ]);
    }
    return redirect('/');
});

Route::get('/', function () {
	$posts = Post::simplePaginate(10);
    return view('index', compact('posts'));
});

Route::get('/type/{type}', function ($type) {
	$posts = Post::where('type', $type)->simplePaginate(10);
    return view('index', compact('posts'));
});


Route::get('/upload', function () {
    return view('upload')->with('title', '上传视频');
});

Route::post('/upload', function (Illuminate\Http\Request $request) {
	//上传
	$destinationPath = base_path() . '/public/Uploads'; // upload path
	$uploadfile = $request->file('file');
	$orgname = $uploadfile->getClientOriginalName();
	$mimes = $uploadfile->getClientMimeType();
	$ext = $uploadfile->getClientOriginalExtension();
	$filehash = md5($orgname);
	$filename = $filehash . '.' . $ext;
	$subpath = '/'.substr($filehash, 0, 2) . '/' . substr($filehash, 2, 2);
	$destinationPath .= $subpath;
	//不存在则新建
	if(!file_exists($destinationPath)){
        mkdir($destinationPath,0777,true);
    }
	$uploadfile->move($destinationPath, $filename);
    $file = $destinationPath . '/' . $filename;
	$thumbfilename = $destinationPath .'/thumb_'.$filehash . '.' . 'jpg';	
	$ffmpeg = \FFMpeg\FFMpeg::create(array(
    'ffmpeg.binaries'  => env('FFMPEG_BIN'),
    'ffprobe.binaries' => env('FFPROBE_BIN'),
    'timeout'          => 3600, // The timeout for the underlying process
    'ffmpeg.threads'   => 12,   // The number of threads that FFMpeg should use
    ));
   $video = $ffmpeg->open($file);
   $frame = $video->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds(20));
   $frame->save($thumbfilename);
   \Image::make($thumbfilename)->resize(1200, 900)->save($thumbfilename);

	$file = $subpath . '/' . $filename;
    $filethumb = $subpath . '/thumb_' . $filehash . '.jpg';

    Post::create([
    	'title' => str_replace('.'.$ext, '', $orgname),
    	'tag' => '纪录片',
    	'type' => 'pano',
    	'file' => $file,
    	'thumb' => $filethumb,
    ]);

    return redirect('/');

});

