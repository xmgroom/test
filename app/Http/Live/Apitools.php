<?php

namespace App\Http\Live;

use App\Models\Channel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class Apitools
{
        public static function img($ima, $suffix ){
             preg_match('/^(data:\s*image\/(\w+);base64,)/', $ima, $result);
            $type = $result[2];    //获取图片的类型jpg png等
            $imgname = $suffix.time().".".$type; //图片重命名
            $savepath = 'upload/image/'.$imgname;   //图片保存目录
            
    //         $qian=array(" ","　","\t","\n","\r");
    // $ima= str_replace($qian, '', $ima);
    $image = str_replace(' ','+',$ima);
    $url = explode(',', $image);

$a = file_put_contents($savepath, base64_decode($url[1]));//返回的是字节数
return $savepath;
    }

           public static function video($video, $suffix ){
             preg_match('/^(data:\s*image\/(\w+);base64,)/', $ima, $result);
            $type = $result[2];    //获取图片的类型jpg png等
            $imgname = $suffix.time().".".$type; //图片重命名
            $savepath = 'upload/image/'.$imgname;   //图片保存目录
            
    //         $qian=array(" ","　","\t","\n","\r");
    // $ima= str_replace($qian, '', $ima);
    $image = str_replace(' ','+',$ima);
    $url = explode(',', $image);

$a = file_put_contents($savepath, base64_decode($url[1]));//返回的是字节数
return $savepath;
    }

    public static function mkSig(array $arr,$as)
    {
        ksort($arr);

        // $request = Auth::user()->accessSecret;
        $request = $as;

        foreach ($arr as $key => $value) {
            $request .= $key . '=' . $value;
        }

        return hash_hmac('sha256', $request, $as);
    }


    public static function curlPost(array $dataArr, $url)
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($dataArr));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataArr);

        ob_start();
        curl_exec($ch);

        $res = ob_get_contents();

        ob_end_clean();
        curl_close($ch);
        return $res;

    }


    public static function mkUrl(array $dataArr)
    {
        $url = '';
        foreach ($dataArr as $key => $value) {
            $url .= $key . '=' . $value . '&';
        }
        return substr($url, 0, strlen($url) - 1);
    }

    public static function curlGet($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);

        $res = curl_exec($ch);

        curl_close($ch);

        return $res;
    }

    public static function live_api($url, array $array,$ak,$as)
    {

        $array['version'] = '2.0';
        $array['accessKey'] = $ak;
        $array['timestamp'] = self::microtime_float() . "";

        $signature = self::mkSig($array,$as);
        $array['signature'] = $signature;


        return self::curlPost($array, $url);

    }

    public static function live_api_wechat($url, array $array,$channel_id)
    {

        $user = Channel::find($channel_id)->user;

        $array['version'] = '2.0';
        $array['accessKey'] = $user->accessKey;
        $array['timestamp'] = self::microtime_float() . "";

        ksort($array);

        $request = $user->accessSecret;

        foreach ($array as $key => $value) {
            $request .= $key . '=' . $value;
        }

        $array['signature'] = hash_hmac('sha256', $request, $user->accessSecret);

        return json_decode(self::curlPost($array, $url));

    }

    public static function live_api_auth($url, array $array){

        return json_decode(self::curlPost($array, $url));

    }

    public static function live_api_get($url, array $array)
    {

        $array['version'] = '2.0';
        $array['accessKey'] = Auth::user()->accessKey;
        $array['timestamp'] = self::microtime_float() . "";

        $signature = self::mkSig($array);
        $array['signature'] = $signature;
        return json_decode(self::curlGet($url . '?' . self::mkUrl($array)));

    }


    public static function live_api_create_channel($url, array $array,$ak,$as)
    {

        $arr['action'] = 'liveCreateChannel';
        $arr['version'] = '2.0';
        $arr['accessKey'] = $ak;
        $arr['timestamp'] = self::microtime_float() . "";

        $signature = self::mkSig($arr,$as);
        $arr['signature'] = $signature;

        $url_str = '?';

        foreach ($arr as $key => $value) $url_str .= $key . '=' . $value . '&';

        $data_string = json_encode($array);
// print_r($url.$url_str);die;
        $ch = curl_init($url . $url_str);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
        );

        $result = curl_exec($ch);

        return json_encode($result);

    }

    public static function live_api_stop_record($url, array $array)
    {

        $arr['action'] = 'liveStopChannelRecord';
        $arr['version'] = '2.0';
        $arr['accessKey'] = Auth::user()->accessKey;
        $arr['timestamp'] = self::microtime_float() . "";

        $signature = self::mkSig(array_merge($arr,$array));
        $arr['signature'] = $signature;

        $url_str = '?';

        foreach ($arr as $key => $value) $url_str .= $key . '=' . $value . '&';
        $data_string = http_build_query($array);
        $ch = curl_init($url . $url_str);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/x-www-form-urlencoded',
                'Content-Length: ' . strlen($data_string))
        );

        $result = curl_exec($ch);
        return json_decode($result);

    }

    public static function microtime_float()
    {
        list($tmp1, $tmp2) = explode(' ', microtime());
        $res = (float)sprintf('%.0f', (floatval($tmp1) + floatval($tmp2)) * 1000);
        return $res;
    }


    public static function live_api_auth_packgeinfo($url,$userId)
    {
        return json_decode(self::curlGet($url."?dhyUserId=".$userId));
    }
    
}
