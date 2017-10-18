<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Redis;
use Illuminate\Support\Facades\DB;
use App\Models\Token;
use Illuminate\Http\Request;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
     public function __construct(Request $request)
    {
    	$token=$request->token;
    	
    	

 if(Redis::exists((string)$token)){
    //根据键名获取键值
    
    $rtoken=Redis::get((string)$token);
   
    if($token == $rtoken ){
    	$ut=Token::select('id','live','addtime')->where('token','=',$rtoken)->get();

    	foreach ($ut as $key => $v) {	
    	}
    	$now=time();
    	$endtime=($v->addtime)+($v->live);
    	$islive=$endtime-$now;
        $uid=$v->id;
       
    	if($islive<0){
    		$msg['code']=4;
    		$msg['data']='token miss';
			die(json_encode($msg));
    	}
        $action=\Route::current()->getActionName();
        list($class, $method) = explode('Controllers\\', $action);
        $method_name=$method;

         $users = DB::table('users')
         ->select('privilege.pri_name')
            ->leftJoin('users_role', 'users.id', '=', 'users_role.admin_id')
             ->leftJoin('role', 'role.id', '=', 'users_role.role_id')
              ->leftJoin('role_privilege', 'role_privilege.role_id', '=', 'role.id')
              ->leftJoin('privilege', 'privilege.id', '=', 'role_privilege.pri_id')
              ->where('privilege.method_name','=',$method_name)
              ->where('users.id','=',$uid)
            ->get();
            
            if($users->isEmpty()){
                $msg['code']=3;
    $msg['data']='no this privilege';
    die(json_encode($msg));
            }

    	

}
else{
	$msg['code']=4;
	$msg['data']='token false';
	die(json_encode($msg));

}

}
else{
	$msg['code']=4;
	$msg['data']='token false';
	die(json_encode($msg));

}

}
   
    
}
