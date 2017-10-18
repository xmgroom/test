<?php

namespace App\Http\Controllers;

use App\Http\Live\LiveChannel;
use App\Http\Live\Apilivechannel;
use App\Http\Requests\ChannelCreateRequest;
use App\Http\Requests\CountdownRequest;
use App\Http\Tools\File;
use App\Models\Channel;
use App\Models\Count;
use App\Models\Image;
use App\Models\Message;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SimpleSoftwareIO\QrCode\BaconQrCodeGenerator;  
use Illuminate\Support\Facades\Config;

class ChannelController extends AuthController
{
  public function apicreate(Request $request)
    {
   
        $suffix = str_random(5);

        $res = Apilivechannel::liveCreateChannel($request->name . $suffix);
        

        if ($res->code == 0 && $request->screen == 1 ) {

            Channel::create([
                'company_id' => Session::get('company:id'),
                'user_id' => session('uid'),
                'screen' => $request->screen,
                'name' => $request->name,
                'channel_id' => $res->result->id,
                'logo' => '/image/micro_logo.png',
                'live_image' => '/image/h.png',
                'suffix' => $suffix,
                'outputgroupId'=>$res->result->outputGroupList[0]->id
            ]);
            $msg['code']=0;
            $msg['data']='create success';
           return json_encode($msg);

        }
        else{
              $msg['code']=1;
            $msg['data']='flase';
           return json_encode($msg);
        }

        }

        public function qr(Request $request){
$qrcode = new BaconQrCodeGenerator;  
return $qrcode->size(200)->generate(Config::get('app.url') ."/live/" . $request->id) ;  
        }

    public function apicontrol($id)
    {
        // $a=$_SERVER['REQUEST_URI'];//
           $action = \Route::current()->getActionName();  
    list($class, $method) = explode('Controllers\\', $action); //路由完整

     // $a=session('uid');
    // print_r($method);

        $channel = Channel::find($id);
        $messages = Message::where('channel_id', $id)->orderBy('id', 'desc')->get();
        Session::put('channel:id', $id);
        Session::put('channel:name', $channel->name);

        $res = Apilivechannel::liveGetChannelById($channel->channel_id);
        $msg['code']=0;
        $msg['data']=$res;
        $msg['channeldata']=$channel;
        $msg['messages']=$messages;
        return json_encode($msg);
        // return view('channel.control', [
        //     'channel' => $channel,
        //     'video_src' => $res->result->publicPlayUrl,
        //     'live_url' => $res->result->pushUrl,
        //     'messages' => $messages
        // ]);

    }



    public function create(ChannelCreateRequest $request)
    {
       
        $suffix = str_random(5);
        $res = LiveChannel::liveCreateChannel($request->name . $suffix);

        if ($res->code == 0 && $request->screen == 1 ) {

            Channel::create([
                'company_id' => Session::get('company:id'),
                'user_id' => Auth::user()->id,
                'screen' => $request->screen,
                'name' => $request->name,
                'channel_id' => $res->result->id,
                'logo' => '/image/micro_logo.png',
                'live_image' => '/image/h.png',
                'suffix' => $suffix,
                'outputgroupId'=>$res->result->outputGroupList[0]->id
            ]);

            return redirect('/')->withErrors('创建频道成功');

        }
        if($res->code == 0 && $request->screen == 2){
            
            Channel::create([
                'company_id' => Session::get('company:id'),
                'user_id' => Auth::user()->id,
                'screen' => $request->screen,
                'name' => $request->name,
                'channel_id' => $res->result->id,
                'logo' => '/image/micro_logo.png',
                'live_image' => '/image/background_bg1.png',
                'suffix' => $suffix,
                'outputgroupId'=>$res->result->outputGroupList[0]->id
            ]);

            return redirect('/')->withErrors('创建频道成功');
        }

        
        return back()->withErrors($res->message);

    }


    public function control($id)
    {
        // $a=$_SERVER['REQUEST_URI'];//
           $action = \Route::current()->getActionName();  
    list($class, $method) = explode('Controllers\\', $action); //路由完整

     // $a=session('uid');
    print_r($method);

        $channel = Channel::find($id);
        $messages = Message::where('channel_id', $id)->orderBy('id', 'desc')->get();

        Session::put('channel:id', $id);
        Session::put('channel:name', $channel->name);

        $res = LiveChannel::liveGetChannelById($channel->channel_id);

        return view('channel.control', [
            'channel' => $channel,
            'video_src' => $res->result->publicPlayUrl,
            'live_url' => $res->result->pushUrl,
            'messages' => $messages
        ]);

    }
   public function mush()
    {
        print_r('11111');
    }

    public function count($id)
    {

//        $channel = Channel::find($id);
//
//        $res = LiveChannel::getChannelLogWithNoPage($channel->name . $channel->suffix);

        $count = Count::where('channel_id', $id);
        $all = $count->count();

        $ip = $count->groupBy('ip')->count();
        $pv = $this->get_pv($id);
        $uv = $count->groupBy('ua','openid')->count();

        $direct = $count->where('source', 1)->count();
        $indirect = $count->where('source', 2)->count();
        $other_source = $all - $direct - $indirect;

        $android = $count->where('ua', 'LIKE', '%android%')->count();
        $ios = $count->where('ua', 'LIKE', '%android%')->count();
        $other_platform = $all - $android - $ios;

        $safari = $count->where('ua', 'LIKE', '%Chrome%')->count();
        $chrome = $count->where('ua', 'LIKE', '%Safari%')->where('ua', 'NOT LIKE', '%Chrome%')->count();
        $other_browser = $all - $safari - $chrome;

        $province = DB::select('select province as name,count(province) as value from `counts` WHERE `channel_id` = '.$id.' GROUP BY province');
        $_00_03 = DB::select("SELECT COUNT(id) as count FROM `counts` WHERE DATE_FORMAT(created_at,'%H:%i:%S')>='00:00:00' and DATE_FORMAT(created_at,'%H:%i:%S')<='03:00:00' and `channel_id` =".$id)[0]->count;
        $_03_06 = DB::select("SELECT COUNT(id) as count FROM `counts` WHERE DATE_FORMAT(created_at,'%H:%i:%S')>='03:00:00' and DATE_FORMAT(created_at,'%H:%i:%S')<='06:00:00' and `channel_id` =".$id)[0]->count;
        $_06_09 = DB::select("SELECT COUNT(id) as count FROM `counts` WHERE DATE_FORMAT(created_at,'%H:%i:%S')>='06:00:00' and DATE_FORMAT(created_at,'%H:%i:%S')<='09:00:00' and `channel_id` =".$id)[0]->count;
        $_09_12 = DB::select("SELECT COUNT(id) as count FROM `counts` WHERE DATE_FORMAT(created_at,'%H:%i:%S')>='09:00:00' and DATE_FORMAT(created_at,'%H:%i:%S')<='12:00:00' and `channel_id` =".$id)[0]->count;
        $_12_15 = DB::select("SELECT COUNT(id) as count FROM `counts` WHERE DATE_FORMAT(created_at,'%H:%i:%S')>='12:00:00' and DATE_FORMAT(created_at,'%H:%i:%S')<='15:00:00' and `channel_id` =".$id)[0]->count;
        $_15_18 = DB::select("SELECT COUNT(id) as count FROM `counts` WHERE DATE_FORMAT(created_at,'%H:%i:%S')>='15:00:00' and DATE_FORMAT(created_at,'%H:%i:%S')<='18:00:00' and `channel_id` =".$id)[0]->count;
        $_18_21 = DB::select("SELECT COUNT(id) as count FROM `counts` WHERE DATE_FORMAT(created_at,'%H:%i:%S')>='18:00:00' and DATE_FORMAT(created_at,'%H:%i:%S')<='21:00:00' and `channel_id` =".$id)[0]->count;
        $_21_24 = DB::select("SELECT COUNT(id) as count FROM `counts` WHERE DATE_FORMAT(created_at,'%H:%i:%S')>='21:00:00' and DATE_FORMAT(created_at,'%H:%i:%S')<='24:00:00' and `channel_id` =".$id)[0]->count;

        $province = json_encode($province);

        return view('channel.count', [
            'ip' => $ip,
            'pv' => $pv,
            'uv' => $uv,
            'android' => $android,
            'ios' => $ios,
            'other_platform' => $other_platform,
            'safari' => $safari,
            'chrome' => $chrome,
            'other_browser' => $other_browser,
            'direct' => $direct,
            'indirect' => $indirect,
            'other_source' => $other_source,
            'province' => $province,
            '_00_03' => $_00_03,
            '_06_09' => $_06_09,
            '_03_06' => $_03_06,
            '_09_12' => $_09_12,
            '_12_15' => $_12_15,
            '_15_18' => $_15_18,
            '_18_21' => $_18_21,
            '_21_24' => $_21_24,
            'id' => $id
        ]);

    }

    public function get_pv($id)
    {
        if(! $channel = Channel::find($id) ) return $pv = '数据获取失败';
        $pv = $channel->pv;
        return $pv;
    }

    public function start($id)
    {

        $channel = Channel::find($id);

        $res = LiveChannel::liveStartChannel($channel->channel_id);

        if ($res->code) return ['code' => -1, 'message' => '直播开启失败' . $res->message];

        $channel->update(['open' => 1]);

        return ['code' => 0, 'message' => '直播开启成功'];

    }

    public function stop($id)
    {

        $channel = Channel::find($id);

        $channel->open = 0;

        if ($channel->record == 1) {

            $res = LiveChannel::liveStopChannelRecord($channel->channel_id, $channel->outputgroupId);

            if ($res->code == 0) {

                $video_res = \App\Http\Live\Video::getVideoInfo($res->result->videoId);

                if ($video_res->code == 0) {

                    Video::create([
                        'company_id' => Session::get('company:id'),
                        'title' => $video_res->result->title,
                        'video_id' => $video_res->result->id,
                        'thumbnail' => $video_res->result->thumbnail,
                        'date' => $video_res->result->date,
                        'duration' => $video_res->result->duration,
                        'htmlEmbedCode' => $video_res->result->playCode->htmlEmbedCode,
                        'flashUrl' => $video_res->result->playCode->flashUrl,
                        'previewUrl' => $video_res->result->playCode->previewUrl,
                        'user_id' => Auth::user()->id
                    ]);
                }

                $channel->record = 1;
            }

        }

        $channel->save();

        $res = LiveChannel::liveStopChannel($channel->channel_id);

        if ($res->code != 0) return ['code' => -1, 'message' => '直播停止失败' . $res->message];

        return ['code' => 0, 'message' => '直播停止成功'];

    }

    public function delete($id)
    {


        $channel = Channel::find($id);
if($channel->delete()){
return redirect('/')->withErrors('删除成功');
}
        $res = LiveChannel::liveDelete($channel->channel_id);

        if ($res->code == 0) {

            try {

                $channel->delete();

            } catch (\Exception $e) {

                return back()->withErrors('删除失败');

            }

        } else {

            return back()->withErrors('删除失败');


        }

        return redirect('/')->withErrors('删除成功');


    }

    public function showCountdown($id)
    {

        $channel = Channel::find($id);

        return view('channel.countdown', ['channel' => $channel]);

    }

    public function countdown($id, CountdownRequest $request)
    {

        try {

            Channel::find($id)->update($request->all());

        } catch (\Exception $e) {

            return back()->withErrors('设置失败！');
        }

        return redirect('/channel/' . $id . '/countdown')->withErrors('设置成功！');

    }

    public function showGuide($id)
    {

        $channel = Channel::find($id);

        $images = Image::where('channel_id', $id)->where('type', 'guide')->get();

        return view('channel.guide', ['channel' => $channel, 'images' => $images]);

    }

    public function guide($id, Request $request)
    {

        $data = $request->except('guide_image_file');

        if ($image = File::mvFile('guide_image_file')) {

            Image::create(['type' => 'guide', 'channel_id' => $id, 'url' => $image]);

            $data['guide_image'] = $image;
        }

        try {

            Channel::find($id)->update($data);

        } catch (\Exception $e) {

            return back()->withErrors('设置失败！');

        }


        return redirect('/channel/' . Session::get('channel:id') . '/guide')->withErrors('设置成功！');

    }

    public function showBackground($id)
    {

        $channel = Channel::find($id);

        $images = Image::where('channel_id', $id)->where('type', 'background')->get();

        return view('channel.background', ['channel' => $channel, 'images' => $images]);

    }

    public function background($id, Request $request)
    {
        $data = $request->except('live_image_file');

        if ($image = File::mvFile('live_image_file')) {

            Image::create(['type' => 'background', 'channel_id' => $id, 'url' => $image]);

            $data['live_image'] = $image;
        }

        try {

            Channel::find($id)->update($data);

        } catch (\Exception $e) {

            return back()->withErrors('设置失败！');
        }


        return redirect('/channel/' . Session::get('channel:id') . '/background')->withErrors('设置成功！');

    }

    public function showLive_logo($id)
    {

        $channel = Channel::find($id);

        return view('channel.live_logo', ['channel' => $channel]);

    }

    public function live_logo($id, Request $request)
    {
        $data = $request->except('live_logo_file');

        if ($image = File::mvFile('live_logo_file')) $data['live_logo'] = $image;

        try {

            Channel::find($id)->update($data);

        } catch (\Exception $e) {

            return back()->withErrors('设置失败！');
        }

        return redirect('/channel/' . Session::get('channel:id') . '/live_logo')->withErrors('设置成功！');

    }

    public function show_logo($id)
    {

        $channel = Channel::find($id);
        return view('channel.logo', ['channel' => $channel]);

    }

    public function logo($id, Request $request)
    {

        if (!$image = File::mvFile('logo_file')) return back()->withErrors('请上传图片');
       

        $data['logo'] = $image;

        \Intervention\Image\Facades\Image::make(public_path($image))->crop((int)$request->width, (int)$request->height, (int)$request->x, (int)$request->y)->save();

        try {

            Channel::find($id)->update($data);

        } catch (\Exception $e) {

            return back()->withErrors('设置失败！');

        }

        return redirect('/channel/' . Session::get('channel:id') . '/logo')->withErrors('设置成功！');

    }

    public function changeChatRoom($id)
    {

        $channel = Channel::find($id);

        if ($channel->chatroom == 1) $channel->chatroom = 0;
        else if ($channel->chatroom == 0) $channel->chatroom = 1;

        $channel->save();

        return redirect('/channel/' . $id . '/control');

    }

    public function startRecord($id)
    {

        $channel = Channel::find($id);

        $res = LiveChannel::liveStartChannelRecord($channel->channel_id, $channel->outputgroupId);

        if ($res->code == 0) {

            $channel->record = 1;

            $channel->save();

            return redirect('/channel/' . Session::get('channel:id') . '/control')->withErrors('录制开启成功');

        }

        return back()->withErrors('录制开启失败' . $res->message);

    }

    public function stopRecord($id)
    {

        $channel = Channel::find($id);

        $res = LiveChannel::liveStopChannelRecord($channel->channel_id, $channel->outputgroupId);

        if ($res->code == 0) {

            $channel->record = 0;

            $channel->save();

            $video_res = \App\Http\Live\Video::getVideoInfo($res->result->videoId);

            if ($video_res->code = 0) {

                Video::create([
                    'company_id' => Session::get('company:id'),
                    'title' => $video_res->result->title,
                    'video_id' => $video_res->result->id,
                    'user_id' => Auth::user()->id,
                    'thumbnail' => $video_res->result->thumbnail,
                    'date' => $video_res->result->date,
                    'duration' => $video_res->result->duration,
                    'htmlEmbedCode' => $video_res->result->playCode->htmlEmbedCode,
                    'flashUrl' => $video_res->result->playCode->flashUrl,
                    'previewUrl' => $video_res->result->playCode->previewUrl,
                ]);

            }

            return redirect('/channel/' . Session::get('channel:id') . '/control')->withErrors('录制停止成功');
        }

        return back()->withErrors($res->message);

    }

    public function removeImage(Request $request)
    {

        //判断频道是否存在
        if (!$channel = Channel::find($request->channel_id)) return ['code' => -2, 'message' => '频道不存在'];

        //判断image表里的数据是否存在
        if (!$image = Image::find($request->id)) return ['code' => -3, 'message' => '该图不存在'];

        //判断该图是否是频道设置图(引导图，背景图)

        if ($request->type == 'guide') {

            if ($image->url == $channel->guide_image) $channel->update(['guide_image' => '/image/guide1.png']);

        } else {

            if ($image->url == $channel->live_image) $channel->update(['live_image' => '/image/background_bg1.png']);

        }

        if (!$image->delete()) return ['code' => -1, 'message' => '删除失败！'];

        return ['code' => 0, 'message' => '删除成功！'];

    }

}
