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

/*if(!function_exists('sendsms')){
    function sendsms($phone, $msg){
        $body['account'] = iconv('GB2312', 'GB2312',"weijian-01");
        $body['pswd'] = iconv('GB2312', 'GB2312',"Txb123456");
        $body['mobile'] =$phone;
        $body['msg']=mb_convert_encoding($msg,'UTF-8', 'auto');
        $url='http://222.73.117.158/msg/HttpBatchSendSM'; 
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($body));
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);//用于屏蔽界面输出
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}
*/
Route::get('/thumbimage', function () {
    set_time_limit(0);
    ini_set('memory_limit', '2048M');
    $directory = '/alidata1/media/panoimage';
    $files = File::allFiles($directory);
        //上传
    $destinationPath = base_path() . '/public/Uploads/media/thumb'; // upload path

    foreach ($files as $file)
    {
        $filehash = md5($file->getFileName());
        $subpath = '/'.substr($filehash, 0, 2) . '/' . substr($filehash, 2, 2);
        
        //不存在则新建
        if(!file_exists($destinationPath . $subpath)){
            mkdir($destinationPath . $subpath,0777,true);
        }

        $dest = $destinationPath . $subpath . '/'.$filehash . '.' . 'jpg';

        if(!file_exists($dest)){

            File::copy($file->getPathName(), $dest);

            $thumbfilename = $destinationPath . $subpath . '/thumb_'.$filehash . '.' . 'jpg';
           /* $ffmpeg = \FFMpeg\FFMpeg::create(array(
            'ffmpeg.binaries'  => env('FFMPEG_BIN'),
            'ffprobe.binaries' => env('FFPROBE_BIN'),
            'timeout'          => 3600, // The timeout for the underlying process
            'ffmpeg.threads'   => 12,   // The number of threads that FFMpeg should use
            ));
           $video = $ffmpeg->open($file->getPathName());
           $frame = $video->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds(20));
           $frame->save($thumbfilename);*/
           \Image::make($file->getPathName())->resize(1200, 900)->save($thumbfilename);
           $filethumb = '/media/thumb'. $subpath . '/thumb_' . $filehash . '.jpg';
           $filepath = '/media/thumb'. $subpath . '/' . $filehash . '.jpg';
            Post::create([
            'title' => str_replace(['.jpg', '.JPG'], ['',''], $file->getFileName()),
            'tag' => '纪录片',
            'type' => 'pano',
            'file' => $filepath,
            'thumb' => $filethumb,
            'mimes'=>1,
            ]);
        }
    }
    return redirect('/');
});


Route::get('/thumb', function () {
    set_time_limit(0);
    ini_set('memory_limit', '2048M');
    $directory = '/alidata1/media/360 Rollercoaster Ride';
    $files = File::allFiles($directory);
        //上传
    $destinationPath = base_path() . '/public/Uploads/media/thumb'; // upload path

    foreach ($files as $file)
    {
        $filehash = md5($file->getFileName());
        $subpath = '/'.substr($filehash, 0, 2) . '/' . substr($filehash, 2, 2);
        
        //不存在则新建
        if(!file_exists($destinationPath . $subpath)){
            mkdir($destinationPath . $subpath,0777,true);
        }

        $dest = $destinationPath . $subpath . '/'.$filehash . '.' . 'mp4';

        if(!file_exists($dest)){

            File::copy($file->getPathName(), $dest);

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
           $filethumb = '/media/thumb'. $subpath . '/thumb_' . $filehash . '.jpg';
           $filepath = '/media/thumb'. $subpath . '/' . $filehash . '.mp4';
            Post::create([
            'title' => str_replace(['.mp4', '.MP4'], ['',''], $file->getFileName()),
            'tag' => '纪录片',
            'type' => 'pano',
            'file' => $filepath,
            'thumb' => $filethumb,
            ]);

        }
    }
    return redirect('/');
});

Route::get('/', function () {
    $posts = Post::where('mimes', 0)->simplePaginate(10);
    return view('index', compact('posts'));
});

Route::get('/panoimage/{id}', function ($id) {
    $post = Post::find($id);
    return view('panoimage')->with(compact('post'));
});

Route::get('/download/{id}', function ($id) {
	$post = Post::find($id);
    $pathToFile= base_path() . '/public/Uploads'.$post->file;
    return response()->download($pathToFile);
});

Route::get('/type/{type}', function ($type) {
	$posts = Post::where('type', $type)->simplePaginate(10);
    return view('index', compact('posts'));
});

Route::get('/mimes/{type}', function ($type) {
    $posts = Post::where('mimes', $type)->simplePaginate(10);
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

