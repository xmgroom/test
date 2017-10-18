<?php

namespace App\Http\Tools;

use App\Events\AdminEvent;
use App\Events\MessageEvent;
use App\Events\MessageRemoveEvent;
use App\Models\Message;


class Socket
{

    public static function pushByMessageId($id,$danmu =0)
    {

        if (!$message = Message::find($id)) return false;

        $send_message = [

            'id' => $message->id,
            'name' => $message->name,
            'time' => date('Y-m-d H:i:s'),
            'message' => $message->message,
            'image' => $message->image,
            'danmu' => $danmu
        ];

        $message->state = 1;
        $message->save();

        event(new MessageEvent($message->channel_id, $send_message));

        return true;

    }

    public static function removeByMessageId($id){

        if (!$message = Message::find($id)) return false;

        event(new MessageRemoveEvent($message->channel_id, ['id'=>$message->id]));

        return true;

    }

    public static function pushByMessageIdWithDanmu($id)
    {


        if (!$message = Message::find($id)) return false;

        $send_message = [

            'id' => $message->id,
            'name' => $message->name,
            'time' => date('Y-m-d H:i:s'),
            'message' => $message->message,
            'image' => $message->image,
            'danmu' => 1

        ];
        $message->danmu_state = 1;
        $message->save();
        event(new MessageEvent($message->channel_id, $send_message));

        return true;


    }

    public static function pushAdmin($channel_id, $message)
    {

        $send_message = [

            'id' => 0,
            'name' => '管理员',
            'time' => date('Y-m-d H:i:s'),
            'message' => $message,
            'image' => '/image/admin_avatar.jpg',
            'danmu' => 0

        ];

        $send_message_danmu = [

            'id' => 0,
            'name' => '管理员',
            'time' => date('Y-m-d H:i:s'),
            'message' => $message,
            'image' => '/image/admin_avatar.jpg',
            'danmu' => 1

        ];

        event(new MessageEvent($channel_id, $send_message));
        event(new MessageEvent($channel_id, $send_message_danmu));

        return true;

    }

    public static function pushToAdmin($channel_id, $message)
    {

        $send_message = [

            'id' => $message->id,
            'name' => $message->name,
            'created_at' => date("Y-m-d H:m:s", $message->created_at->timestamp),
            'message' => $message->message,
            'image' => $message->image,
            'state' => 0,
            'danmu_state' => 0,

        ];

        event(new AdminEvent($channel_id, $send_message));

        return true;

    }

}