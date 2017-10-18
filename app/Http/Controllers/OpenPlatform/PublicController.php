<?php

namespace App\Http\Controllers\OpenPlatform;

use App\Http\Controllers\Controller;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class PublicController extends Controller
{


    public function binding(Request $request)
    {

        $app = app('wechat');

        $openPlatform = $app->open_platform;

        $authorizationInfo = $openPlatform->getAuthorizationInfo()->authorization_info ;

        $authorizerInfo = $openPlatform->getAuthorizerInfo($authorizationInfo['authorizer_appid'])->authorizer_info;

        if(Company::where('authorizer_appid',$authorizationInfo["authorizer_appid"])->first()) return back()->withErrors('绑定失败 已存在该组织');

        if(!$company = Company::create([
            'name'=>$authorizerInfo["nick_name"],
            'logo'=>$authorizerInfo["head_img"],
            'user_id'=> Auth::user()->id,
            'authorizer_appid'=>$authorizationInfo["authorizer_appid"],
            'authorizer_access_token'=>$authorizationInfo["authorizer_access_token"],
            'authorizer_refresh_token'=>$authorizationInfo["authorizer_refresh_token"],
        ])){

            return back()->withErrors('绑定失败');
        }

        return redirect('/company')->withErrors('绑定成功');

    }

}
