<?php

namespace App\Http\Live;


use Illuminate\Support\Facades\Config;

class Apilivechannel
{
      public static function img($ima, $suffix ){
           

return Apitools::img($ima, $suffix );
    }

    public static function liveCreateChannel($name,$ak,$as)
    {


        $arr['action'] = 'liveCreateChannel';

        $arr['isThumbnail'] = '1';
        $arr['thumbnailInterval'] = 10;
        $arr['name'] = $name;
        $arr['signalType'] = '1';
        $arr['outputGroupList'] = [
            [
                "id"=>"",
                'isRecord'=>1,
                'protocolType' => '1',
                'isTimeShift' => '1',
                'timeShiftDuration' => '1',
                'outputTemplateList' => [
                    [
                        "templateId" => "786ee85ebb7c4dad8e75bc836a55a948",
                        "isDefault" => "0"
                    ]
                ]
            ]
        ];
        
    
        return Apitools::live_api_create_channel(Config::get('hongshiyun.url') . "/rest", $arr,$ak,$as);

    }


    public static function liveStartChannel($id,$ak,$as)
    {

        $arr['id'] = $id;

        $arr['action'] = 'liveStartChannel';

        return Apitools::live_api(Config::get('hongshiyun.url') . "/rest", $arr,$ak,$as);

    }

    public static function liveStopChannel($id,$ak,$as)
    {

        $arr['id'] = $id;

        $arr['action'] = 'liveStopChannel';

        return Apitools::live_api(Config::get('hongshiyun.url') . "/rest", $arr,$ak,$as);

    }

    public static function liveGetChannelById($id,$ak,$as)
    {

        $arr['id'] = $id;

        $arr['action'] = 'liveGetChannelById';

        return Apitools::live_api(Config::get('hongshiyun.url') . "/rest", $arr,$ak,$as);

    }

    public static function liveGetChannelByIdWithWeChat($id,$channel_id,$ak,$as){

        $arr['id'] = $id;

        $arr['action'] = 'liveGetChannelById';

        return Apitools::live_api_wechat(Config::get('hongshiyun.url') . "/rest", $arr,$channel_id,$ak,$as);

    }

    public static function liveGetChannelsByPage($id)
    {

        $arr['id'] = $id;

        $arr['action'] = 'liveGetChannelsByPage';

        return Tools::live_api(Config::get('hongshiyun.url') . "/rest", $arr);

    }

    public static function liveGetChannelsPlayUrl($id)
    {

        $arr['id'] = $id;

        $arr['action'] = 'liveGetChannelsPlayUrl';

        return Tools::live_api(Config::get('hongshiyun.url') . "/rest", $arr);

    }

    public static function liveStartChannelRecord($channelId, $outputGroupId,$ak,$as)
    {

        $arr['channelId'] = $channelId;

        $arr['outputGroupId'] = $outputGroupId;

        $arr['action'] = 'liveStartChannelRecord';

        return Apitools::live_api(Config::get('hongshiyun.url') . "/rest", $arr,$ak,$as);

    }

    public static function liveStopChannelRecord($channelId, $outputGroupId,$ak,$as)
    {

        $arr['channelId'] = $channelId;

        $arr['outputGroupId'] = $outputGroupId;

        $arr['action'] = 'liveStopChannelRecord';

//        return Tools::live_api_stop_record(Config::get('hongshiyun.url') . "/rest", $arr);
        return Apitools::live_api(Config::get('hongshiyun.url') . "/rest", $arr,$ak,$as);

    }

    public static function liveDelete($id,$ak,$as)
    {

        $arr['ids'] = $id;

        $arr['action'] = 'liveDelete';

        return Apitools::live_api(Config::get('hongshiyun.url') . "/rest", $arr,$ak,$as);

    }

    public static function getChannelLogWithNoPage($keyword){

        $arr['action'] = 'getChannelLogWithNoPage';

        $arr['keyword'] = $keyword;

        return Tools::live_api(Config::get('hongshiyun.url') . "/rest", $arr);

    }


}