<?php

namespace App\Http\Controllers;

use App\Http\Live\LiveChannel;

use App\Http\Live\Apilivechannel;
use App\Http\Requests\ChannelCreateRequest;
use App\Http\Requests\CountdownRequest;
use App\Http\Tools\File;
use App\Models\Channel;
use App\Models\Problem;
use App\Models\Wenjuan;
use App\Models\Count;
use App\Models\Image;
use App\Models\Message;
use App\Models\User;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SimpleSoftwareIO\QrCode\BaconQrCodeGenerator;  
use Illuminate\Support\Facades\Config;

class ApiChannelController extends Controller
{

	
 public function postchannelcreate($uid,$companyid,$ak,$as,Request $request)
    {

        $name=$request->name;
        $screen=$request->screen;
         $manager_id=$request->manager_id;
        $suffix = str_random(5);
        $res = Apilivechannel::liveCreateChannel($name . $suffix,$ak,$as);
        // $res=json_encode($res);
        $res=json_decode($res,true);
        $res=(array)json_decode($res,true);
// var_dump($res['result']['outputGroupList'][0]['id']);die;
        if (($res['code']== 0 && $screen == 1) || ($res['code']== 0 && $screen == 2 )) {
            $ima=$request->logo_file;
            $savepath= Apilivechannel::img($ima, $suffix);
            Channel::create([
                'company_id' => $companyid,
                'user_id' => $uid,
                'screen' => $screen,
                'name' => $name,
                'manager_id' => $manager_id,
                'channel_id' => $res['result']['id'],
                'logo' =>$savepath ,
                'live_image' => '/image/h.png',
                'suffix' => $suffix,
                'outputgroupId'=>$res['result']['outputGroupList'][0]['id']
            ]);
            $msg['code']=0;
            $msg['data']='create channel success';
           return $msg;

        }
        
        else{

              $msg['code']=2221;
            $msg['data']='flase';
           return $msg;
        }

        }


        public function channelalert( $channel_id ,Request $request){
            $data['name']=$request->name;
            $data['manager_id']=$request->manager_id;
            if(!empty($request->logo_file)){
                 $ima=$request->logo_file;
            $suffix = str_random(5);
            $savepath= Apilivechannel::img($ima, $suffix);
            $data['logo'] = $savepath;
            }
           
        $alert=Channel::where('id',$channel_id)->update($data);
        if($alert){
            // @unlink ();
               $msg['code']=0;
            $msg['data']='success';
           return $msg;
        }
           else{

              $msg['code']=1;
            $msg['data']='flase';
           return $msg;
        }


        }
        public function channelguideinfo($channel_id){
            $guide=Channel::select('live_logo','guide_image','live_logo_open')->where('id',$channel_id)->get();
            return $guide;
        }
        public function channelguide($channel_id,Request $request){
            $suffix = str_random(5);
            if(!empty($request->live_logo)){
             $ima=$request->live_logo;
         $savepath_live_logo= Apilivechannel::img($ima, $suffix);
      $guide= Channel::where('id',$channel_id)->update([
            'live_logo'=>$savepath_live_logo]);}
            if(!empty($request->guide_image)){
                $gima=$request->guide_image;
                  $suffix = str_random(5);
             $savepath_guide_image= Apilivechannel::img($gima, $suffix);
               $guide= Channel::where('id',$channel_id)->update([
            'guide_image'=>$savepath_guide_image
            ]);
            }      
     
       if($guide){
            $msg['code']=0;
            $msg['data']='success';
           return $msg;

       }
        else{

              $msg['code']=1;
            $msg['data']='flase';
           return $msg;
        }
        }


        public function userslist(Request $request){
            $users=User::select('id','name')->get();
            $msg['code']=0;
            $msg['data']=$users;
            return $msg;

        }


        public function qr($channel_id){
            $qrcode = new BaconQrCodeGenerator; 
            return Config::get('app.url') ."/live/" . $channel_id;die;
            return $qrcode->size(200)->generate(Config::get('app.url') ."/live/" . $channel_id) ;  
        }

    public function apicontrol($channel_id,$ak,$as)
    {
        // $a=$_SERVER['REQUEST_URI'];//
       
        $action = \Route::current()->getActionName();  
        list($class, $method) = explode('Controllers\\', $action); //路由完整
 
     // $a=session('uid');
    // print_r($method);

        $channel = Channel::find($channel_id);
        $messages = Message::where('channel_id', $channel_id)->orderBy('id', 'desc')->get();

        $res = Apilivechannel::liveGetChannelById($channel->channel_id,$ak,$as);
        $res=json_decode($res);
        $msg['code']=0;
        $msg['video_src']=$res->result->publicPlayUrl;
        $msg['live_url' ]= $res->result->pushUrl;
        $msg['channeldata']=$channel;
        $msg['messages']=$messages;
        return $msg;
        // return view('channel.control', [
        //     'channel' => $channel,
        //     'video_src' => $res->result->publicPlayUrl,
        //     'live_url' => $res->result->pushUrl,
        //     'messages' => $messages
        // ]);

    }


    public function apicount($id)
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

        return json_encode( [
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

    public function start($channel_id,$ak,$as)
    {

        $channel = Channel::find($channel_id);
        $res = ApiliveChannel::liveStartChannel($channel->channel_id,$ak,$as);
    $res=(array)json_decode($res,true);
        if ($res['code']) return ['code' => -1, 'message' => '直播开启失败' . $res['message']];

        $channel->update(['open' => 1]);

        return ['code' => 0, 'message' => '直播开启成功'];

    }

    public function stop($channel_id,$ak,$as)
    {

        $channel = Channel::find($channel_id);

        $channel->open = 0;

        if ($channel->record == 1) {

            $res = ApiliveChannel::liveStopChannelRecord($channel->channel_id, $channel->outputgroupId,$ak,$as);
$res=(array)json_decode($res,true);
            if ($res['code'] == 0) {

                $video_res = \App\Http\Live\Video::getVideoInfo($res['result']['videoId']);

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

        $res = ApiliveChannel::liveStopChannel($channel->channel_id,$ak,$as);
$res=(array)json_decode($res,true);
        if ($res['code'] != 0) return ['code' => -1, 'message' => '直播停止失败' . $res['message']];

        return ['code' => 0, 'message' => '直播停止成功'];

    }

    public function delete($channel_id,$ak,$as)
    {


        $channel = Channel::find($channel_id);

        
    
        $res = ApiliveChannel::liveDelete($channel->channel_id,$ak,$as);
// $res=json_decode($res,true);
        $res=(array)json_decode($res,true);

        if ($res['code']== 0) {

            try {

                $channel->delete();

            } catch (\Exception $e) {

                return back()->withErrors('删除失败');

            }

        } else {

            return back()->withErrors('删除失败');


        }
$msg['code']=0;
$msg['data']='success';
        return $msg;



    }


public function apilogoopen($channel_id){
    $data['live_logo_open']=1;
    $open=Channel::where('id',$channel_id)->update($data);
    if($open){
        $msg['code']=0;
        $msg['data']='success';
        return $msg;

    }
    else{
         $msg['code']=1;
        $msg['data']='false';
        return $msg;
    }

}
public function apilogoclose($channel_id){
    $data['live_logo_open']=0;
    $open=Channel::where('id',$channel_id)->update($data);
    if($open){
        $msg['code']=0;
        $msg['data']='success';
        return $msg;

    }
    else{
         $msg['code']=1;
        $msg['data']='false';
        return $msg;
    }

}
public function channeldata($channel_id){
    $data=Channel::select('name','manager_id','logo','id')->where('id',$channel_id)->get();
     if($data){
        $msg['code']=0;
        $msg['data']=$data;
        return $msg;

    }
    else{
         $msg['code']=1;
        $msg['data']='false';
        return $msg;
    }
}
//问卷列表
public function channelproblemlist($channel_id){
 $wenjuan= DB::table('wenjuan')
         ->select('wenjuan.name','wenjuan.id')
            ->where('wenjuan.channel_id',$channel_id)
            ->get();
         foreach ($wenjuan as $key => $value) {
             $problem[$value->name]=DB::table('problem')->select('id','problem','a','b','c','d','name_id')
                    ->where('name_id',$value->id)
                    ->get();
                   
         }

            return $problem;

}
//问卷添加
public function channelproblem($channel_id,Request $request){
 $data=$request->getContent(); 
   // $data=json_decode($data,true);
   $data=(array)json_decode($data,true);

   $wenjuan=Wenjuan::create([
        'name'=>$data['name'],
        'channel_id'=>$channel_id
        ]);

    $wenjuan_id= $wenjuan->id;

  foreach ($data['QItems'] as $k=> $v) {

     Problem::create([
        'problem'=>$v['QTitle'],
        'a'=>$v['A1'],
        'b'=>$v['A2'],
        'c'=>$v['A3'],
        'd'=>$v['A4'],
        'name_id'=>$wenjuan_id
        ]);
  }
    $msg['code']=0;
    $msg['data']='success';
    return $msg;

}
public function channelproblemdata($wenjuan_id){
    $name=DB::table('wenjuan')
        ->select('name','id')
        ->where('id',$wenjuan_id)
        ->get();
    $wenjuan=DB::table('problem')
            ->select('id','problem','a','b','c','d')
            ->where('name_id',$wenjuan_id)
            ->get();
            $msg['wenjuan_name']=$name;
            $msg['problem']=$wenjuan;
            return $msg;

}

public function channelproblemalert($wenjuan_id,$problem_id){
   return $msg;
}

//弹幕开启
public function channeldanmuopen($channel_id){
    $data['chatroom_danmu']=1;
     $open=Channel::where('id',$channel_id)->update($data);
       if($open){
        $msg['code']=0;
        $msg['data']='success';
        return $msg;

    }
    else{
         $msg['code']=1;
        $msg['data']='false';
        return $msg;
    }

}
public function channeldanmuclose($channel_id){
    $data['chatroom_danmu']=0;
     $close=Channel::where('id',$channel_id)->update($data);
       if($close){
        $msg['code']=0;
        $msg['data']='success';
        return $msg;

    }
    else{
         $msg['code']=1;
        $msg['data']='false';
        return $msg;
    }

}
public function channeldanmuchecko($channel_id){
     $data['danmu_check']=1;
     $open=Channel::where('id',$channel_id)->update($data);
       if($open){
        $msg['code']=0;
        $msg['data']='success';
        return $msg;

    }
    else{
         $msg['code']=1;
        $msg['data']='false';
        return $msg;
    }


}
public function channeldanmucheckc($channel_id){
     $data['danmu_check']=0;
     $open=Channel::where('id',$channel_id)->update($data);
       if($open){
        $msg['code']=0;
        $msg['data']='success';
        return $msg;

    }
    else{
         $msg['code']=1;
        $msg['data']='false';
        return $msg;
    }


}

public function privatemessageo($channel_id){
     $data['private_message']=1;
     $open=Channel::where('id',$channel_id)->update($data);
       if($open){
        $msg['code']=0;
        $msg['data']='success';
        return $msg;

    }
    else{
         $msg['code']=1;
        $msg['data']='false';
        return $msg;
    }


}
public function privatemessagec($channel_id){
     $data['private_message']=0;
     $open=Channel::where('id',$channel_id)->update($data);
       if($open){
        $msg['code']=0;
        $msg['data']='success';
        return $msg;

    }
    else{
         $msg['code']=1;
        $msg['data']='false';
        return $msg;
    }


}
public function danmupass($message_id){
    $data['state']=1;
    $message=Message::where('id',$message_id)->update($data);
    if($message){
        $msg['code']=0;
        $msg['data']='success';
        return $msg;
    }
    else{
         $msg['code']=1;
        $msg['data']='false';
        return $msg;
    }

}
public function danmunot($message_id){
    $data['state']=0;
    $message=Message::where('id',$message_id)->update($data);
    if($message){
          $msg['code']=0;
        $msg['data']='success';
        return $msg;
    }
    else{
          $msg['code']=1;
        $msg['data']='false';
        return $msg;
       
    }

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

        return json_encode('success');

    }

    public function changeChatRoom($id)
    {

        $channel = Channel::find($id);

        if ($channel->chatroom == 1) $channel->chatroom = 0;
        else if ($channel->chatroom == 0) $channel->chatroom = 1;

        $channel->save();

        return redirect('/channel/' . $id . '/control');

    }

    public function startRecord($channel_id,$ak,$as)
    {

        $channel = Channel::find($channel_id);

        $res = Apilivechannel::liveStartChannelRecord($channel->channel_id, $channel->outputgroupId,$ak,$as);
        $res=(array)json_decode($res);

        if ($res['code'] == 0) {

            $channel->record = 1;

            $channel->save();

           return $res;

        }

        return $res;

    }

    public function stopRecord($uid,$company_id,$channel_id,$ak,$as)
    {

        $channel = Channel::find($channel_id);

        $res = Apilivechannel::liveStopChannelRecord($channel->channel_id, $channel->outputgroupId,$ak,$as);
        $res=(array)json_decode($res);
        if ($res['code'] == 0) {

            $channel->record = 0;

            $channel->save();

            $video_res = \App\Http\Live\Video::getVideoInfo($res['result']['videoId'],$ak,$as);
            $video_res=(array)json_decode($video_res);
            if ($video_res['code'] = 0) {

                Video::create([
                    'company_id' => $company_id,
                    'title' => $video_res['result']['title'],
                    'video_id' => $video_res['result']['id'],
                    'user_id' => $uid,
                    'thumbnail' => $video_res['result']['thumbnail'],
                    'date' => $video_res->result->date,
                    'duration' => $video_res['result']['duration'],
                    'htmlEmbedCode' => $video_res['result']['playCode']['htmlEmbedCode'],
                    'flashUrl' => $video_res['result']['playCode']['flashUrl'],
                    'previewUrl' => $video_res['result']['playCode']['previewUrl'],
                ]);

            }
            $msg['code']=0;
            $msg['data']='success';
            return $msg;
        }

         $msg['code']=1;
            $msg['data']='flase';
            return $msg;

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
