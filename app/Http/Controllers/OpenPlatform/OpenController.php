<?php

namespace App\Http\Controllers\OpenPlatform;

use App\Http\Controllers\Controller;
use App\Models\Company;
use EasyWeChat\OpenPlatform\Guard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OpenController extends Controller
{

    public function messages(){

        $app = app('wechat');

        $openPlatform = $app->open_platform;

        $server = $openPlatform->server;

        $server->setMessageHandler(function($event) use ($openPlatform) {

            switch ($event->InfoType) {
                case Guard::EVENT_AUTHORIZED: // 授权成功
                    $authorizationInfo = $openPlatform->getAuthorizationInfo($event->AuthorizationCode)->authorization_info;
                    Company::where('authorizer_appid',$authorizationInfo["authorizer_appid"])->update([
                        "authorizer_access_token"=>$authorizationInfo["authorizer_access_token"],
                        "authorizer_refresh_token"=>$authorizationInfo["authorizer_refresh_token"]
                    ]);
                // 保存数据库操作等...
                case Guard::EVENT_UPDATE_AUTHORIZED: // 更新授权

                case Guard::EVENT_UNAUTHORIZED: // 授权取消

            }
        });
        $response = $server->serve();
        return $response;


    }

}
