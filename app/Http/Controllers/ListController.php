<?php

namespace App\Http\Controllers;

use App\Http\Tools\File;
use App\Models\Channel;
use App\Models\Lists;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ListController extends Controller
{

    public function index()
    {
        $lists = Lists::where('user_id',Auth::user()->id)->get();

        $channels = Channel::where('company_id', Session::get('company:id'))->where('user_id',Auth::user()->id)->get();

        $videos = Video::where('company_id', Session::get('company:id'))->where('user_id',Auth::user()->id)->get();

        return view('list.create', ['lists' => $lists, 'channels' => $channels, 'videos' => $videos]);
    }

    public function show($id)
    {
        $lists = Lists::where('user_id',Auth::user()->id)->get();

        $list = Lists::find($id);

        $channels = Channel::where('company_id', Session::get('company:id'))->where('user_id',Auth::user()->id)->get();

        $videos = Video::where('company_id', Session::get('company:id'))->where('user_id',Auth::user()->id)->get();

        return view('list.show', ['lists' => $lists, 'list' => $list, 'id' => $id, 'channels' => $channels, 'videos' => $videos]);

    }


    public function store(Request $request)
    {

        $data = ['name' => $request->name, 'lists' => $request->lists,'background_color'=>$request->background_color,'item_color'=>$request->item_color];

        if ($head_img = File::mvFile('head_img_file')) $data['head_img'] = $head_img;
        else $data['head_img']=$request['head_img'];
        if ($foot_img = File::mvFile('foot_img_file')) $data['foot_img'] = $foot_img;
        else $data['foot_img']=$request['foot_img'];

        $data['user_id'] = Auth::user()->id;

        try {

            $lists = Lists::create($data);

        } catch (\Exception $e) {

            return back()->withErrors('列表创建失败');
        }

        return redirect('/list/' . $lists->id)->withErrors('列表创建成功');

    }

    public function update($id, Request $request)
    {

        $data = ['name' => $request->name, 'lists' => $request->lists,'background_color'=>$request->background_color,'item_color'=>$request->item_color];

        if ($head_img = File::mvFile('head_img_file')) $data['head_img'] = $head_img;
        if ($foot_img = File::mvFile('foot_img_file')) $data['foot_img'] = $foot_img;

        try {

            Lists::find($id)->update($data);

        } catch (\Exception $e) {

            return back()->withErrors('列表更新失败');
        }

        return redirect('/list/' . $id)->withErrors('列表更新成功');

    }

    public function delete($id){

        if(Lists::find($id)->delete()) return ['code'=>0,'message'=>'列表删除成功'];

        return ['code'=>-1,'message'=>'列表删除失败'];

    }


}
