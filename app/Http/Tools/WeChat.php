<?php

namespace App\Http\Tools;

use Illuminate\Support\Facades\Config;

class WeChat
{

    public static function authorization()
    {

        $app = app('wechat');

        $openPlatform = $app->open_platform;

        $response = $openPlatform->pre_auth->redirect(Config::get('app.url') . '/public/binding');

        return $response->getTargetUrl();

    }

}