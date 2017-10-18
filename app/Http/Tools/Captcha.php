<?php

namespace App\Http\Tools;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

/**
 * Created by PhpStorm.
 * User: steven
 * Date: 16/9/12
 * Time: 下午8:06
 */

class Captcha
{

    public static function check()
    {

        if (Input::get('captcha') == Session::pull('captcha')) return true;

        return false;

    }


}