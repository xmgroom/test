<?php

namespace App\Http\Live;


use Illuminate\Support\Facades\Config;

class LiveChannel
{

    public static function liveCreateChannel($name)
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
        return Tools::live_api_create_channel(Config::get('hongshiyun.url') . "/rest", $arr);

    }


    public static function liveStartChannel($id)
    {

        $arr['id'] = $id;

        $arr['action'] = 'liveStartChannel';

        return Tools::live_api(Config::get('hongshiyun.url') . "/rest", $arr);

    }

    public static function liveStopChannel($id)
    {

        $arr['id'] = $id;

        $arr['action'] = 'liveStopChannel';

        return Tools::live_api(Config::get('hongshiyun.url') . "/rest", $arr);

    }

    public static function liveGetChannelById($id)
    {

        $arr['id'] = $id;

        $arr['action'] = 'liveGetChannelById';

        return Tools::live_api(Config::get('hongshiyun.url') . "/rest", $arr);

    }

    public static function liveGetChannelByIdWithWeChat($id,$channel_id){

        $arr['id'] = $id;

        $arr['action'] = 'liveGetChannelById';

        return Tools::live_api_wechat(Config::get('hongshiyun.url') . "/rest", $arr,$channel_id);

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

    public static function liveStartChannelRecord($channelId, $outputGroupId)
    {

        $arr['channelId'] = $channelId;

        $arr['outputGroupId'] = $outputGroupId;

        $arr['action'] = 'liveStartChannelRecord';

        return Tools::live_api(Config::get('hongshiyun.url') . "/rest", $arr);

    }

    public static function liveStopChannelRecord($channelId, $outputGroupId)
    {

        $arr['channelId'] = $channelId;

        $arr['outputGroupId'] = $outputGroupId;

        $arr['action'] = 'liveStopChannelRecord';

//        return Tools::live_api_stop_record(Config::get('hongshiyun.url') . "/rest", $arr);
        return Tools::live_api(Config::get('hongshiyun.url') . "/rest", $arr);

    }

    public static function liveDelete($id)
    {

        $arr['ids'] = $id;

        $arr['action'] = 'liveDelete';

        return Tools::live_api(Config::get('hongshiyun.url') . "/rest", $arr);

    }

    public static function getChannelLogWithNoPage($keyword){

        $arr['action'] = 'getChannelLogWithNoPage';

        $arr['keyword'] = $keyword;

        return Tools::live_api(Config::get('hongshiyun.url') . "/rest", $arr);

    }


}