<?php
/**
 * Created by PhpStorm.
 * User: Sky
 * Date: 2017/3/14
 * Time: 10:55
 */

namespace App\Http\Live;


use Illuminate\Support\Facades\Config;

class Video
{


    public static function getVideoInfo($videoId,$ak,$as)
    {

        $arr['videoId'] = $videoId;

        $arr['action'] = 'getVideoInfo';

        return Apitools::live_api(Config::get('hongshiyun.url') . "/rest", $arr,$ak,$as);

    }

    public static function queryVideo($sort,$searchType = null,$keyword = null){

        $arr['action'] = 'queryVideo';

        $arr['start'] = 0;

        if($searchType) $arr['searchType'] = $searchType;

        $arr['sort'] = $sort;

        $arr['number'] = 32;

        if($keyword) $arr['keyword'] = $keyword;

        $arr['order'] = 'asc';

        return Tools::live_api_get(Config::get('hongshiyun.url') . "/rest", $arr);


    }

    public static function deleteVideo($videoIds){

        $arr['action'] = 'deleteVideo';

        $arr['videoIds'] = $videoIds;

        return Tools::live_api(Config::get('hongshiyun.url') . "/rest", $arr);

    }

    public static function getVideoCount(){
        
        $arr['action'] = 'getVideoCount';

        return Tools::live_api(Config::get('hongshiyun.url') . "/rest", $arr);

    }

    public static function getToken(){

        $arr['action'] = 'getToken';

        return Tools::live_api_get(Config::get('hongshiyun.url') . "/rest", $arr);

    }

    public static function setCallbackUrl(){

        $arr['action'] = 'setCallbackUrl';

        $arr['callbackUrl'] = 'http://wei.hongshiyun.net/api/video/upload_callback';

        return Tools::live_api(Config::get('hongshiyun.url') . "/rest", $arr);

    }



}