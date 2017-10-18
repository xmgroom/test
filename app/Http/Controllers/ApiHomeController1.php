<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Mongodb;
class ApiHomeController extends Controller
{

public function list(Request $request){
    $of=4*($request->page-1);
    $channels = Channel::select('name','user_id','updated_at','open','screen','id','logo','manager_id')->where('company_id', $request->companyid)->where('user_id',$request->userid)->offset($of)->limit(4)->get();
    if($channels->isEmpty()){
   
          $data['code']=1;
        }
        else{
                 $data['code']=0;
          
        }
     $channels_data = Channel::where('company_id', $request->companyid)->where('user_id',$request->userid)->get();
     foreach ($channels as $key => $value) {
         $udata=User::where('id',$value->manager_id)->get();
         foreach ($udata as $key1 => $value1) {
         }
         $value->username=$value1->name;
     }
      $videos_count = Video::where('company_id', $request->companyid)->where('user_id',$request->userid)->count();
     $num=count($channels_data);
    $data['msg']= $channels;
    $data['num']=$num;
    $data['videos_count']=$videos_count;
    return $data;
   

}


public function listtime(Request $request){
   $time=$request->da;
   if($time==1){
   	$da='asc';
   }
   else{
   	$da='desc';
   }
    $channels = Channel::select('name','user_id','updated_at','open','screen','id','logo','manager_id')->where('company_id', $request->companyid)->where('user_id',$request->userid)->orderBy('updated_at',$da)->get();
    if($channels->isEmpty()){
   
          $data['code']=1;
        }
        else{
                 $data['code']=0;
          
        }
     $channels_data = Channel::where('company_id', $request->companyid)->where('user_id',$request->userid)->get();
     foreach ($channels as $key => $value) {
         $udata=User::where('id',$value->manager_id)->get();
         foreach ($udata as $key1 => $value1) {
         }
         $value->username=$value1->name;
     }
      $videos_count = Video::where('company_id', $request->companyid)->where('user_id',$request->userid)->count();
     $num=count($channels_data);
    $data['msg']= $channels;
    $data['num']=$num;
    $data['videos_count']=$videos_count;
    return $data;
   

}


public function listsearch(Request $request){
    // $where=$request->where;
     $of=4*($request->page-1);
    $channels = Channel::select('name','user_id','updated_at','open','screen','id','logo','manager_id')->where('company_id', $request->companyid)->where('user_id',$request->userid)->offset($of)->limit(4)->get();
    if($channels->isEmpty()){
   
          $data['code']=1;
        }
        else{
                 $data['code']=0;
          
        }
     $channels_data = Channel::where('company_id', $request->companyid)->where('user_id',$request->userid)->get();
     foreach ($channels as $key => $value) {
         $udata=User::where('id',$value->manager_id)->get();
         foreach ($udata as $key1 => $value1) {
         }
         $value->username=$value1->name;
     }
      $videos_count = Video::where('company_id', $request->companyid)->where('user_id',$request->userid)->count();
     $num=count($channels_data);
    $data['msg']= $channels;
    $data['num']=$num;
    $data['videos_count']=$videos_count;
    return $data;
   

}

public function listnopage(Request $request){
$u = Mongodb::connectionMongodb('test');
// $u->insert(['title' => 'email', 'article' => 'john@example.com','time' => time()]);
  // $a=$u->where('name', '小明')->update(['name' => 'lichuang']);
  // $u->where('title', 'email')->delete();
  
  // $u = Mongodb::connectionMongodb('test');
  // dd($u->where('age','18')->get());die;
  
    $channels = Channel::select('name','user_id','id')->where('company_id', $request->companyid)->where('user_id',$request->userid)->get();
  
    if($channels->isEmpty()){
   
          $data['code']=1;
        }
        else{
                 $data['code']=0;
          
        }
  
    $data['msg']= $channels;
    return $data;
   

}
  

    public function index()
    {
        $sid=Session::getId();
        $name=Session::get('uid');
        // print_r($sid);
        $channels = Channel::where('company_id', Session::get('company:id'))->where('user_id',Auth::user()->id)->orderBy('id', 'desc')->get();
        $user=User::where('id',Auth::user()->id)->get();
        $user=$user[0]->name;
        $num=count($channels);

        Session::put('channels', $channels);
        if (count($channels) > 0) {

            Session::put('channel:id', $channels[0]->id);

        } else {
            Session::put('channel:id', 0);

        }

        return view('channel.main', ['channels' => $channels, 'date' => '','user'=>$user]);

    }


    public function type($type = 1)
    {

        $channels = Channel::where('company_id', Session::get('company:id'))->where('user_id',Auth::user()->id);

        if ($type == 2) {

            $channels = $channels->orderBy('open', 'desc');


        }
        if ($type == 3) $channels = $channels->orderBy('id', 'desc');
        if ($type == 4) $channels = $channels->orderBy('id', 'asc');

        $channels = $channels->get();

        Session::put('channels', $channels);

        if (count($channels) > 0) Session::put('channel:id', $channels[0]->id);

        else Session::put('channel:id', 0);

        return view('channel.main', ['channels' => $channels, 'date' => '','type'=>$type]);

    }

    public function search(Request $request)
    {

        Session::put('home.search', $request->search);
        Session::put('home.date', $request->date);

        $channels = Channel::where('company_id', Session::get('company:id'))->where('name', 'LIKE', '%' . $request->search . '%')->where('user_id',Auth::user()->id);

        if ($request->has('date')) {

            $dates = explode('-', $request->date);
            $channels->where('updated_at', '>=', date("Y/m/d H:i:s", strtotime($dates[0])))->where('updated_at', '<=', date("Y/m/d H:i:s", strtotime('+1 days', strtotime($dates[1]))));

        }

        return view('channel.main', ['channels' => $channels->get(),'date' => $request->date]);

    }
}
