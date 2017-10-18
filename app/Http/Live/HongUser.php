<?php

namespace App\Http\Live;

use Illuminate\Support\Facades\Config;

class HongUser
{

    public static function register($email,$password,$mobile,$verifyCode){

        $arr['action'] = 'register';
        $arr['account'] = $email;
        $arr['password'] = $password;
        $arr['passwordRepeat'] = $password;
        $arr['cellphone'] = $mobile;
        $arr['verifyCode'] = $verifyCode;
        $arr['source'] = '1';

        return Tools::live_api_auth(Config::get('hongshiyun.url') . "/rest", $arr);

    }

    public static function login($email,$password){

        $arr['action'] = 'login';
        $arr['account'] = $email;
        $arr['password'] = $password;

        return Tools::live_api_auth(Config::get('hongshiyun.url') . "/rest", $arr);

    }

    public static function getUserSpace($userId){

        $arr['action'] = 'getUserSpace';
        $arr['userId'] = $userId;

        return Tools::live_api_get(Config::get('hongshiyun.url') . "/rest", $arr);


    }


    public static function getPackgeInfo($userId)
    {
        if($userId) return Tools::live_api_auth_packgeinfo(Config::get('hongshiyun.url') . "/rest", $userId);

        return 0;
    }


}