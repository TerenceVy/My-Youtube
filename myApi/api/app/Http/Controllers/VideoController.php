<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Video;
use App\Models\VideoFormat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VideoController extends Controller
{
    public function create(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'source' => 'required|mimes:mp4',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first(),
            ], 400);
        }

        $getID3 = new \getID3;

        $file = $getID3->analyze($request->file('source'));
        $extension = '.' . $file['fileformat'];
        $duration = (int) $file['playtime_seconds'];

        $file = $request->file('source');
        $path = public_path().'/uploads/';

        if (is_file($path . $request->get('name') . $extension)) {
        	$name = date('Y_m_d-H-i-s') . $request->get('name');
        } else {
        	$name = $request->get('name');
        }

        $file->move($path, $name . $extension);

        $video = Video::create([
        	'name' => $name,
        	'duration' => $duration,
        	'source' => $path . $name . $extension,
        	'user_id' => Auth::user()->id
        ]);

        VideoFormat::create(['video_id' => $video->id]);

        $format = VideoFormat::where('video_id', $video->id)->first();

        $video['user'] = [
            'id' => $video['user_id'],
            'username' => $video['username'],
            'pseudo' => $video['pseudo'],
            'created_at' => $video['user_create'],
        ];

        $video['format'] = $format;

        // $url = 'http://127.0.0.1:8001/api/encode';
        // $client = new \GuzzleHttp\Client(['http_errors' => false]);
        // $response = $client->request('GET', $url);

        return response()->json([
            "message" => "Ok",
            "data" => $video
        ], 201);
    }

    public function getVideos(Request $request) {
        $perPage = $request->get('perPage') == null ? 5 : $request->get('perPage');
        $page = $request->get('page') == null ? 1 : $request->get('page');

        $videoList = User::join('video', 'video.user_id', '=', 'user.id')
                ->where([
                        ['video.duration', 'like','%' . $request->get('duration') . '%'],
                        ['video.name', 'like','%' . $request->get('name') . '%'],
                        ['user.username', 'like','%' . $request->get('user') . '%'],
                        ['user.id', 'like','%' . $request->get('user') . '%']
                    ])
                ->select('user.*', 'user.created_at as user_create', 'video.*')
                ->paginate($perPage, ['*'], 'page', $page);

        if (empty($videoList->items())) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        foreach ($videoList as $video) {
            $format = VideoFormat::where('video_id', $video->id)->first();
            $video['user'] = [
                'id' => $video['user_id'],
                'username' => $video['username'],
                'pseudo' => $video['pseudo'],
                'created_at' => $video['user_create'],
            ];
            $video['format'] = $format;
            unset($video['username'], $video['pseudo'], $video['email'], $video['user_create'], $video['user_id']);
        }

        $pager[] = [
            'current' => $videoList->currentPage(),
            'total' => $videoList->lastPage()
        ];

        if ($page > $videoList->lastPage()) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }
        
        return response()->json([
            'message' => 'Ok', 
            'data' => $videoList->items(), 
            'pager' => $pager
        ], 200);
    }

    public function getVideosByUserId($id, Request $request) {
        $perPage = $request->get('perPage') == null ? 5 : $request->get('perPage');
        $page = $request->get('page') == null ? 1 : $request->get('page');

        $videoList = User::join('video', 'video.user_id', '=', 'user.id')
            ->where('user_id', $id)
            ->select('user.*', 'user.created_at as user_create', 'video.*')
            ->paginate($perPage, ['*'], 'page', $page);

        if (empty($videoList->items())) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        foreach ($videoList as $video) {
            $format = VideoFormat::where('video_id', $video->id)->first();

            $video['user'] = [
                'id' => $video['user_id'],
                'username' => $video['username'],
                'pseudo' => $video['pseudo'],
                'created_at' => $video['user_create'],
            ];
            $video['format'] = $format;
            unset($video['username'], $video['pseudo'], $video['email'], $video['user_create'], $video['user_id']);
        }

        $pager[] = [
            'current' => $videoList->currentPage(),
            'total' => $videoList->lastPage()
        ];

        if ($page > $videoList->lastPage()) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }
        
        return response()->json(['message' => 'Ok', 
            'data' => $videoList->items(), 
            'pager' => $pager
        ], 200);  
    }

    public function encodeVideo($id, Request $request) {
        $validator = Validator::make($request->all(), [
            'format' => 'required|in:144,240,360,480,720,1080',
            'file' => 'required|string|max:255',
            'file_name' => 'required|string'
        ]);

        if (file_exists($request->file)) {

            if ($validator->fails()) {
                return response()->json([
                    "message" => $validator->errors()->first(),
                ], 400);
            }

            Video::where('id', $id)->update(['enabled' => TRUE]);
            $video = User::join('video', 'video.user_id', '=', 'user.id')
                ->where('video.id', $id)
                ->select('video.*', 'user.*', 'user.created_at as user_create')
                ->first();

            if (!isset($video)) {
                return response()->json([
                    'message' => "Not found"
                ], 404);
            }

            shell_exec('mv ' . $request->file . ' ' . public_path() . '/uploads');


            VideoFormat::where('video_id', $id)->update(['p' . $request->format => public_path() . '/uploads/' . $request->file_name]);


            $format = VideoFormat::where('video_id', $id)->first();

            $video['user'] = [
                'id' => $video['user_id'],
                'username' => $video['username'],
                'pseudo' => $video['pseudo'],
                'created_at' => $video['user_create'],
            ];

            $video['format'] = $format;

            unset($video['username'], $video['pseudo'], $video['email'], $video['user_create'], $video['user_id']);

            return response()->json([
                'message' => 'Ok',
                'data' => $video
            ], 200);
        }
        return response()->json([
            'message' => "The file doesn't exist"
        ], 400);
    }

    public function updateVideo($id, Request $request) {

        $validator = Validator::make($request->all(), [
            'user' => 'int|exists:user,id',
            'name' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first(),
            ], 400);
        }

        $user = Auth::user();
        $video = Video::where('id', $id)->first();
        $extension = [];
        $path = public_path().'/uploads/';

        if (!isset($video)) {
            return response()->json([
                'message' => "Not found"
            ], 404);
        } elseif ($user->id !== $video['user_id']) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        } else {
            if ($request->get('user') !== null) {
                $user_id = $request->get('user');
            } 
            if ($request->get('name') !== null) {
                preg_match('/(?=\.).*/', $video->source, $extension);
                $name = $video->name;
                $user_id = $video->user_id;
                if (is_file($path . $request->get('name') . $extension[0]))
                    $name = date('Y_m_d-H-i-s') . $request->get('name');
                else
                    $name = $request->get('name');

                shell_exec('mv ' . $video->source . ' ' . $path . $name . $extension[0]);
            }
        }
        Video::where('id', $id)->update(['user_id' => $user_id, 'source' => $path . $name . $extension[0], 'name' => $name]);

        $video = Video::where('id', $id)->first();

        $user = User::where('id', $user_id)->first();

        $format = VideoFormat::where('video_id', $video->id)->first();

        $video['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'pseudo' => $user['pseudo'],
            'created_at' => $user['user_create'],
        ];

        $video['format'] = $format;

        return response()->json([
            'message' => 'Ok',
             'data' => $video
         ], 200);    
    }

    public function deleteVideo($id) {
        $user = Auth::user();

        $video = Video::where('id', $id)->first();

        if (!isset($video)) {
            return response()->json([
                'message' => "Not found"
            ], 404);
        } elseif ($user->id !== $video['user_id']) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $video->delete();

        shell_exec('rm ' . $video->source);

        return response()->json([
            'message' => 'Ok', 
            'data' => $video->source
        ], 200);
    }

    public function needEncoding() {
        $videos = Video::join('video_format', 'video_format.video_id', '=','video.id')
        ->join('user', 'user.id', 'video.user_id')
        ->select('source', 'video_id', 'name', 'email')
        ->where([['video_format.p144', null]])->get();

        return response()->json([
            'message' => 'Ok', 
            'data' => $videos
        ], 200);
    }
}
