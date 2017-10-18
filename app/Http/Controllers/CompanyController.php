<?php

namespace App\Http\Controllers;

use App\Http\Tools\File;
use App\Http\Tools\WeChat;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class CompanyController extends Controller
{

    public function index()
    {

        $companies = Company::where('user_id', Auth::user()->id)->get();

        if (Config::get('app.debug')) {

            $url = '';

        } else {

            $url = WeChat::authorization();

        }

        return view('company.list', ['companies' => $companies, 'url' => $url]);

    }

     public function apishow($id)
    {
        if($id==0){
            Session::put('company:id', 0);
            Session::put('company:logo', 'http://wx.qlogo.cn/mmopen/hvr6DDkCA6xqT8jMPdGxRVvHybT8CZ08nWj0XQEC8x9OmGjIE6xcuBTyWEKKWwAaicV8sZYMh7liaQpkfAeWbl5Pu5fdE4OcXU/0');
            Session::put('company:name', '虹直播');
        }else{
            $company = Company::find($id);
        }
        $msg['code']=0;
         $msg['data']=$company;
        return json_encode($msg);

    }

    public function show($id)
    {
        if($id==0){
            Session::put('company:id', 0);
            Session::put('company:logo', 'http://wx.qlogo.cn/mmopen/hvr6DDkCA6xqT8jMPdGxRVvHybT8CZ08nWj0XQEC8x9OmGjIE6xcuBTyWEKKWwAaicV8sZYMh7liaQpkfAeWbl5Pu5fdE4OcXU/0');
            Session::put('company:name', '虹直播');
        }else{
            $company = Company::find($id);
            Session::put('company:id', $id);
            Session::put('company:logo', $company->logo);
            Session::put('company:name', $company->name);
        }

        return redirect('/');

    }


    public function update(Request $request)
    {

        $data = ['name' => $request->name];

        if ($logo = File::mvFile('logo')) $data['logo'] = $logo;

        try {

            Company::find($request->id)->update($data);

        } catch (\Exception $e) {

            return back()->withErrors('编辑失败');
        }

        return redirect('/company')->withErrors('编辑成功');

    }

    public function delete(Request $request)
    {

        if (Company::find($request->id)->delete()) return redirect('/company')->withErrors('删除成功');

        return redirect('/company')->withErrors('删除失败');

    }

}
