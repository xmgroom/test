<?php
use Ramsey\Uuid\Uuid;
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Live\HongUser;
use App\Http\Live\SMS;
use App\Http\Requests\PasswordRequest;
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
use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;
use Redis;
class AuthController extends LoginController
{
public function __construct()
    {
    	$a=session('id');
    	
    print_r($a);
    // print_r(123);
  $action = \Route::current()->getActionName(); 
  list($class, $method) = explode('Controllers\\', $action); 
  $users = DB::table('users')
            ->leftJoin('users_role', 'users.id', '=', 'users_role.admin_id')
             ->leftJoin('role', 'role.id', '=', 'users_role.role_id')
              ->leftJoin('role_privilege', 'role_privilege.role_id', '=', 'role.id')
              ->leftJoin('privilege', 'privilege.id', '=', 'role_privilege.pri_id')
              ->where('users.id','=',17)
            ->get();
// print_r($users);
  // echo $class;
  // echo $method;
    }

 public function db(Request $request) {

 	$a=$_SERVER['REQUEST_URI'];//获取当前路由
    $action = \Route::current()->getActionName();  
    list($class, $method) = explode('Controllers\\', $action); //路由完整
    $request->url(); //完整url // echo url()->current(); echo url()->full(); echo url()->previous();
// // $a=Auth::user()->id;
// $a=session('captcha');

$udata=User::where(['email'=>'860192152@qq.com','password'=>md5('123456')])->get();



// if(empty($a)){
// 	echo '失败！';
// }

	
// print_r(md5('123456'));

  return view('auth.db');

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
        Session::put('captcha', $phrase);
        //生成图片
        header("Cache-Control: no-cache, must-revalidate");
        header('Content-Type: image/jpeg');
        $builder->output();
    }
 public function apilogin(Request $request)
    { 

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
            $data['token']=$token;
            $data['live']=86400;
            $data['addtime']=time();
            $ut=Token::insert($data);
            $msg['code']=0;
            $msg['token']=$token;
            $msg['data']=$udata;

            $key = 'user_id'.$value->id;


// $user = User::find(17);

if($ut){
    //将用户名存储到Redis中
    Redis::set($key,$token);
}

//判断指定键是否存在
if(Redis::exists($key)){
    //根据键名获取键值
    dd(Redis::get($key));
}
            
                return json_encode($msg);
            }
    		
    		
			else{
    			$msg['code']=1;
    			$msg['data']='用户名或密码错误！';
    			return json_encode($msg);
    		}
    		
    		
    	
       
    }




    public function showLogin()
    {
        return view('auth.login');
    }

    public function showSign()
    {

        return view('auth.sign');

    }

    public function showForget()
    {

        return view('auth.forget');

    }

    public function showForgetChange()
    {

        return view('auth.forget_change');

    }

    public function showChangePassword()
    {
        return view('auth.change_password');
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/login');
    }

    public function law()
    {

        return view('auth.law');

    }

    public function login(LoginRequest $request)
    {

        // if (!Captcha::check()) return back()->withErrors('验证码输入错误');


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

           Auth::attempt(['name' => $request->name, 'password' => $request->password]);
         $id=Auth::user()->id;
          $ak= Auth::user()->accessKey;
		Session::put('uid',$id);
		Session::put('ak',$ak);
		$as = Auth::user()->accessSecret;
		Session::put('as',$as);
        
         



                return redirect('/company');

        }

        return $this->logout();

    }

    public function sign(RegRequest $request)
    {

        if (!Captcha::check()) return back()->withErrors('验证码输入错误');

        if (!SMS::checkVerifyCode($request->name, $request->verifyCode)) return back()->withErrors('手机验证码输入错误');

        //虹直播注册

        $res = HongUser::register($request->email, $request->password, $request->name, $request->verifyCode);

        if ($res->code != 0) return back()->withErrors('注册失败,邮箱或手机号码已经存在');

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'hong_userId' => $res->result->id
        ]);

        if (Auth::attempt(['name' => $request->name, 'password' => $request->password])) return redirect('/login')->withErrors('注册成功!请登录');

        return back()->withErrors('注册失败！');
    }

    public function forget(Request $request)
    {

        if (!Captcha::check()) return back()->withErrors('验证码输入错误');

        if (SMS::checkVerifyCode($request->name, $request->verifyCode)) return back()->withErrors('手机验证码输入错误');

        Session::put('forget_verify', $request->name);

        return redirect('/forget_change');

    }

    public function forgetChange(PasswordRequest $request)
    {

        if (Session::has('forget_verify')) {

            $forget_verify = Session::get('forget_verify');

            User::where('name', $forget_verify)->update(['password' => Hash::make($request->password)]);

        }

        return redirect('/login')->withErrors('无效操作');

    }

    public function changePassword(PasswordRequest $request)
    {


        if (!Hash::check($request->oldpassword, Auth::user()->password)) return back()->withErrors('原密码输入不正确');
        Auth::user()->update(['password' => Hash::make($request->password)]);

        return redirect('/')->withErrors('密码修改成功');

    }

}
