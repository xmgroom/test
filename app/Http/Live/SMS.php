<?php

namespace App\Http\Live;

use Illuminate\Support\Facades\Config;

class SMS
{

    public static function sendVerifyCode($cellphone)
    {

        $arr['cellphone'] = $cellphone;

        $arr['action'] = 'sendVerifyCode';

        return Tools::live_api_auth(Config::get('hongshiyun.url') . "/rest", $arr);

    }


    public static function checkVerifyCode($cellphone, $code)
    {

        $arr['cellphone'] = $cellphone;
        $arr['code'] = $code;

        $arr['action'] = 'checkVerifyCode';

        $res =  Tools::live_api_auth(Config::get('hongshiyun.url') . "/rest", $arr);

        if ($res->code == 0) return true;

        return false;

    }
}