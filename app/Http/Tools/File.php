<?php

namespace App\Http\Tools;

/**
 * Created by PhpStorm.
 * User: steven
 * Date: 16/9/12
 * Time: 下午8:06
 */
class File
{

    protected static function mkFolder($path)
    {

        if (!is_readable($path)) {

            mkdir($path, 0700, true);

        }

    }


    /*
     * 文件存储工具
     *
     */

    public static function mvFile($fileName)
    {

        if (!\Illuminate\Support\Facades\Input::hasFile($fileName)) return false;

        $file = \Illuminate\Support\Facades\Input::file($fileName);

        $filePath = public_path('upload/');

        $fileName = str_random(20) . '.' . $file->guessExtension();

        File::mkFolder($filePath);

        if(!$file->move($filePath, $fileName))return false;

        return  '/upload/' .$fileName;
    }


}