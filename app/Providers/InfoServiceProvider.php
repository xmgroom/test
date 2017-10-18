<?php

namespace App\Providers;

use App\Http\Live\HongUser;
use App\Models\Channel;
use App\Models\Combos;
use App\Models\Video;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;

class InfoServiceProvider extends ServiceProvider
{

    protected $totalSpace = 0;
    protected $usedSpace = 0;


    public function boot()
    {


        view()->composer('channel.main', function ($view) {
            $this->getUserSpace();

            $view->with('videos_count', $this->getVideoCount());
            $view->with('channels_count', $this->getChannelCount());
            $view->with('combo', $this->getCombos());
            $view->with('totalSpace', $this->totalSpace);
            $view->with('usedSpace', $this->usedSpace);

        });

        view()->composer('material.material', function ($view) {
            $this->getUserSpace();

            $view->with('videos_count', $this->getVideoCount());
            $view->with('channels_count', $this->getChannelCount());
            $view->with('combo', $this->getCombos());
            $view->with('totalSpace', $this->totalSpace);
            $view->with('usedSpace', $this->usedSpace);
            $view->with('token', $this->getUploadToken());


        });


    }

    public function getUploadToken(){

        $token = \App\Http\Live\Video::getToken();

        return $token->result->accessToken;

    }


    public function getUserSpace()
    {

        $res = HongUser::getUserSpace(Auth::user()->hong_userId);

        if ($res->code == 0) {

            $this->totalSpace = round($res->result->totalSpace / 1024 / 1024.0, 2);
            $this->usedSpace = round($res->result->usedSpace / 1024 / 1024.0, 2);

        }

    }

    public function getCombos()
    {

//        $combos = Combos::find(Auth::user()->combo_id);
//        if ($combos) return $combos->name;
//        return "";

        $combos = HongUser::getPackgeInfo(Auth::user()->hong_userId);
        
        if( $combos == 0 ) return '套餐获取失败';
        
        return $combos->result->name;

    }

    public function getChannelCount()
    {
        $channels_count = Channel::where('company_id', Session::get('company:id'))->where('user_id',Auth::user()->id)->count();
        return $channels_count;
    }

    public function getVideoCount()
    {
        $videos_count = Video::where('company_id', Session::get('company:id'))->where('user_id',Auth::user()->id)->count();
        return $videos_count;
    }
}
