<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Live\SMS;
use App\Models\User;
use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{


    public function captcha($tmp)
    {
        //生成验证码图片的Builder对象，配置相应属性
        $phrase = new PhraseBuilder();
        $code = $phrase->build(4);

        $builder = new CaptchaBuilder($code, $phrase);
        //可以设置图片宽高及字体
        $builder->setBackgroundColor(220, 210, 230);
        $builder->setMaxAngle(25);
        $builder->setMaxBehindLines(0);
        $builder->setMaxFrontLines(0);
        $builder->build($width = 100, $height = 40, $font = null);
        //获取验证码的内容
        $phrase = $builder->getPhrase();

        //把内容存入session
        Session::put('captcha', $phrase);
        //生成图片
        header("Cache-Control: no-cache, must-revalidate");
        header('Content-Type: image/jpeg');
        $builder->output();
    }

    public function sendVerifyCode(Request $request){

        if(!$request->mobile)return ['code'=> -1 ,'message'=>"请输入手机号码"];
        if(User::where('name',$request->mobile)->first()) return ['code'=>-1,'message'=>'手机号码已存在'];
        if(strlen($request->mobile) != 11)return ['code'=> -1 ,'message'=>"请输入正确的手机号码"];
        $res = SMS::sendVerifyCode($request->mobile);
        if($res->code==200) return ['code'=> 0 ,'message'=>"验证码获取成功"];
        return ['code'=> -1 ,'message'=>"验证码获取失败"];
    }

}
