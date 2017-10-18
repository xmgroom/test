<?php

namespace App\Http\Controllers;

use App\Http\Live\LiveChannel;
use App\Http\Live\Apilivechannel;
use App\Models\Channel;
use App\Models\Company;
use App\Models\Count;
use App\Models\Lists;
use App\Models\Message;
use App\Models\Video;
use App\Models\WeChatUser;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Zhuzhichao\IpLocationZh\Ip;

class WechatPageController extends Controller
{

    public function __construct()
    {
        if (Config::get('app.debug')) {

            $this->middleware('wechat.oauth');

        }
    }

    public function live($id,$ak,$as)
    {


        $this->pv($id);

        if (!$channel = Channel::find($id)) return view('wechat.error', ['message' => "未找到该频道"]);

        if ($channel->guide == 1) {

            if ($channel->countdown == 1) {

                if(strtotime($channel->countdown_time)  < strtotime(date('Y-m-d H:m:s'))) return $this->channel($id);

                return view('wechat.notLive.countdown_background', compact('channel'));

            } else {

                return view('wechat.notLive.background', compact('channel'));

            }

        } else {

            if ($channel->countdown == 1) {

                if(strtotime($channel->countdown_time)  < strtotime(date('Y-m-d H:m:s'))) return $this->channel($id);

                return view('wechat.notLive.countdown', compact('channel'));

            } else {

                return $this->channel($id,$ak,$as);

            }

        }

    }


    public function pv($id)
    {

        if(! $channel = Channel::find($id)) return view('wechat.error', ['message' => "未找到该频道"]);

        if(! $channel->update(['pv' => $channel->pv+1])) return view('wechat.error', ['message' => "未找到该频道,code:201"]);

    }


    public function channel($id,$ak,$as)
    {

        if (Config::get('app.debug')) {

            $wechat = app('wechat');

            $user = session('wechat.oauth_user');

            if (!$channel = Channel::find($id)) return view('wechat.error', ['message' => "未找到该频道"]);


        } else {

            if(!$channel = Channel::find($id)) return view('wechat.error', ['message' => '未找到该频道']);

            if($channel->company_id==0){

                $wechat = app('wechat')
                    ->open_platform
                    ->createAuthorizerApplication(Config::get('hongshiyun.authorizer_appid'), Config::get('hongshiyun.authorizer_refresh_token'));

            }else{

                $company = Company::find($channel->company_id);

                $wechat = app('wechat')
                    ->open_platform
                    ->createAuthorizerApplication($company->authorizer_appid, $company->authorizer_refresh_token);

            }

            if (!Request::has('auth')) {

                Session::put('target_url', Config::get('app.url') . '/channel/' . $id . '?auth=1');

                Session::put('company_id', $channel->company_id);

                return $wechat->oauth->scopes(['snsapi_userinfo'])->redirect();

            }

            $user = Session::get('wechat_user');

        }

        $video = ApiliveChannel::liveGetChannelByIdWithWeChat($channel->channel_id,$id,$ak,$as);
if(!empty($video)){
    

        if ($video->code == 0) {

            if ($channel->open == 0) return view('wechat.error', ['message' => "未开启直播"]);

            $ip = $_SERVER["REMOTE_ADDR"];

            if (!WeChatUser::where('openid', $user["id"])->first())
                WeChatUser::create([
                    'openid' => $user["id"],
                    'nickname' => $user["nickname"],
                    'email' => $user["email"],
                    'sex' => $user["original"]["sex"],
                    'province' => $user["original"]["province"],
                    'city' => $user["original"]["city"],
                    'country' => $user["original"]["country"],
                    'headimgurl' => $user["original"]["headimgurl"],
                ]);

            Count::create([
                'channel_id' => $channel->id,
                'openid' => $user["id"] ?: "未知",
                'ip' => $ip,
                'province' => Ip::find($ip)[1],
                'ua' => $_SERVER['HTTP_USER_AGENT'],
                'source' => 1
            ]);
            $view = 'wechat.live_vertical';
            if ($channel->screen == 1) $view = 'wechat.live_horizontal';
           $msg['channel']=$channel;
           $msg['play_url']=$video->result->outputGroupList[0]->liveOutputs[0]->publicOutputUrl;
           $msg['play_img']=$video->result->thumbnailUrl;
           $msg['js']=$wechat->js;
           $msg['user']=$user;
           return $msg;
            // return view(
            //     $view, [
            //     'channel' => $channel,
            //     'video_src' => $video->result->outputGroupList[0]->liveOutputs[0]->publicOutputUrl,
            //     'video_img' => $video->result->thumbnailUrl,
            //     'js' => $wechat->js,
            //     'user' => $user
            // ]);

        } else {

            return view('wechat.error', ['message' => $video->message]);

        }
        }
        $msg['code']=1;
        $msg['data']='没有此频道相关信息！';
        return $msg;

    }

    public function lists($id)
    {

        $lists = Lists::find($id);

        $lists->lists = json_decode($lists->lists);

        return view('wechat.lists', compact('lists'));

    }

    public function play($id)
    {

        if (!$video = Video::find($id)) return view('wechat.error', ['message' => "未找到该视频"]);

        return view('wechat.play', ['htmlEmbedCode' => $video->htmlEmbedCode,'title'=>$video->title]);

    }

    public function callback()
    {

        if(Session::get('company_id')==0){

            $app = app('wechat')
                ->open_platform
                ->createAuthorizerApplication(Config::get('hongshiyun.authorizer_appid'), Config::get('hongshiyun.authorizer_refresh_token'));

        }else{

            $company = Company::find(Session::get('company_id'));

            $app = app('wechat')
                ->open_platform
                ->createAuthorizerApplication($company->authorizer_appid, $company->authorizer_refresh_token);

        }

        $oauth = $app->oauth;
        $user = $oauth->user();

        Session::put('wechat_user', $user->toArray());

        return redirect(Session::pull('target_url'));

    }

}
