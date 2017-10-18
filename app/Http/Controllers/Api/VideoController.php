<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Live\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VideoController extends Controller
{

    public function deleteVideos(Request $request)
    {


        $res = Video::deleteVideo($request->videos);

        if ($res->code == 0) {

            $videoIds = explode(',', $request->videos);

            $videos = \App\Models\Video::whereIn('video_id', $videoIds)->get();

            foreach ($videos as $video) $video->delete();

            return ['code' => 0, 'message'=>'删除成功'];
        }

        return ['code' => -1, 'message' => '删除失败'];

    }

    public function preview($id)
    {
        $video = \App\Models\Video::find($id);

        return $video->htmlEmbedCode;
    }

    public function upload_info(Request $request,$id,$user_id)
    {

        $res = Video::getVideoInfo($request->videoId);

        Log::info(json_encode($res));

        if ($res->code == 0) {

            \App\Models\Video::create([
                'company_id'=>$id,
                'user_id'=>$user_id,
                'title'=> $res->result->title,
                'video_id' => $res->result->id,
                'thumbnail' => $res->result->thumbnail,
                'date' => $res->result->date,
                'duration' => $res->result->duration,
                'htmlEmbedCode' => $res->result->playCode->htmlEmbedCode,
                'flashUrl' => $res->result->playCode->flashUrl,
                'previewUrl' => $res->result->playCode->previewUrl,
            ]);


            return ['code' => 0, 'message'=>'上传成功！'];
        }

        return ['code' => -1, 'message'=>'上传失败'];

    }

    public function upload_callback(Request $request){

        if($video = \App\Models\Video::where('video_id',$request->videoId)->first()){

            $res = Video::getVideoInfo($video->video_id);

            if($res->code==0){

                $video->duration = $res->result->duration;

                $video->save();

            }

        }

    }


    public function test(Request $request)
    {
        
if($video = \App\Models\Video::where('video_id',$request->videoId)->first()){

            $res = Video::getVideoInfo($video->video_id);

            if($res->code==0){

                $video->duration = $res->result->duration;

                $video->save();

            }

        }
    }

}
