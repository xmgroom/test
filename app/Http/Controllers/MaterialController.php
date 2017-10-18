<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class MaterialController extends Controller
{

    public function index()
    {

//        \App\Http\Live\Video::setCallbackUrl();

        $videos = Video::where('company_id', Session::get('company:id'))->where('user_id',Auth::user()->id)->get();

        return view('material.material', ['videos' => $videos]);

    }


    public function type($type = 1)
    {
        $videos = Video::where('company_id', Session::get('company:id'))->where('user_id',Auth::user()->id);

        if ($type == 2) $videos = $videos->orderBy('duration', 'desc')->get();
        else if ($type == 3) $videos = $videos->orderBy('id', 'desc')->get();
        else if ($type == 4) $videos = $videos->orderBy('id', 'asc')->get();
        else $videos = $videos->get();

        return view('material.material', ['videos' => $videos, 'type' => $type]);

    }

    public function search(Request $request)
    {

        Session::put('material.name', $request->name);
        Session::put('material.date', $request->date);

        $videos = Video::where('company_id', Session::get('company:id'))->where('user_id',Auth::user()->id);
        $videos->where('title', 'LIKE', '%' . $request->name . '%');
        if ($dates = explode('-', $request->date)) {
            $videos->where('updated_at', '>=', date("Y/m/d H:i:s", strtotime($dates[0])))->where('updated_at', '<=', date("Y/m/d H:i:s", strtotime('+1 days', strtotime($dates[1]))));
        }

        return view('material.material', ['videos' => $videos->get()]);

    }


}
