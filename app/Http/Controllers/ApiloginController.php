<?php
use Ramsey\Uuid\Uuid;
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Live\HongUser;
use App\Http\Live\SMS;
use App\Http\Requests\PasswordRequest;
use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegRequest;
use App\Http\Tools\Captcha;
use App\Models\User;
use App\Models\Token;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\Test;
use Redis;
class ApiloginController extends LoginController
{


  public function db(Request $request) {

    $a=$_SERVER['REQUEST_URI'];//获取当前路由
    $action = \Route::current()->getActionName();  
    list($class, $method) = explode('Controllers\\', $action); //路由完整
    $request->url(); //完整url // echo url()->current(); echo url()->full(); echo url()->previous();
// // $a=Auth::user()->id;
// $a=session('captcha');

$udata=User::where(['email'=>'860192152@qq.com','password'=>md5('123456')])->get();


    //根据键名获取键值
 

  return view('channel.db');

    }

    public function yzm()
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
        $builder->build($width = 180, $height = 80, $font = null);
        //获取验证码的内容
        $phrase = $builder->getPhrase();

        //把内容存入session
        // Session::put('captcha', $phrase);
        Redis::set('captcha',$phrase);
        //生成图片
        header("Cache-Control: no-cache, must-revalidate");
        header('Content-Type: image/jpeg');
        $builder->output();
    }
 public function apilogin(Request $request)
    { 
$res = HongUser::login($request->name , $request->password);
if($res->code == 0){
    User::updateOrCreate ([
                'name' => $request->name,
            ],[
                    'name' => $request->name,
                    'email' => $res->result->account,
                    'password' => Hash::make($request->password),
                    'hong_userId' => $res->result->id,
                    'accessKey' => $res->result->accessKey,
                    'accessSecret' => $res->result->accessSecret,
                    'type' => $res->result->type,
                ]);
            $udata=User::where('email',$request->name)->get();
            foreach ($udata as $key => $value) {
            }
            $password=$value->password;
            if(!Hash::check($password,$request->password )){
                foreach ($udata as $key => $value) {
                $uesr_id=$value->id;
            $cdata=Company::where('user_id',$uesr_id)->get();
            foreach ($cdata as $key1 => $value1) {}
            $value->company_id=$value1->id;#
            }
            $token=md5(uniqid(microtime(true),true));
            $data['id']=$value->id;
            $data['token']=$token;
            $data['live']=86400;
            $data['addtime']=time();
            $to=Token::where('id',$value->id)->get();
           $ut=Token::updateOrCreate(['id'=>$value->id],$data);
           if($ut){
             // $key = 'user_id'.$value->id;
    //将用户名存储到Redis中
                Redis::set($token,$token);
                }
            $msg['code']=0;
            $msg['token']=$token;
            $msg['data']=$udata;

           


                return json_encode($msg);
            }
            
            
            else{
                $msg['code']=1;
                $msg['data']='用户名或密码错误！';
                return json_encode($msg);
            }
            
            
        
       
    }
    else{
       return json_encode($res) ;
    }
}

}
