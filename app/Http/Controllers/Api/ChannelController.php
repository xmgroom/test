<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Tools\File;
use App\Http\Tools\Socket;
use App\Models\Blacklist;
use App\Models\Channel;
use App\Models\Image;
use App\Models\Message;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ChannelController extends Controller
{


    public function getMessage(Request $request)
    {

        if (Blacklist::where('openid', $request->openid)->where('channel_id', $request->channel_id)->first()) return 'block';

        if (!$message = Message::create($request->except('danmu'))) return 'error';

        $channel = Channel::find($request->channel_id);

        if($channel->screen==1){

            if ($channel->chatroom_audit != 1) {

                Socket::pushByMessageId($message->id);

            }

            if ($channel->chatroom_danmu != 1) {

                Socket::pushByMessageIdWithDanmu($message->id);

            }
        }else{

            if ($channel->chatroom_danmu != 1) {

                Socket::pushByMessageIdWithDanmu($message->id);

            }

        }


        Socket::pushToAdmin($request->channel_id, $message);

        return 'success';

    }

    public function blacklist(Request $request)
    {

        $data = $request->all();

        if (Blacklist::where('openid', $request->openid)->where('channel_id', $request->channel_id)->first()) return 'success';

        if (!Blacklist::create($data)) return 'error';

        return 'success';

    }

    public function cancel_blacklist(Request $request)
    {

        if (! $blacklist =  Blacklist::where('openid', $request->openid)->where('channel_id', $request->channel_id)) return 'success';

        if (! $blacklist->delete()) return 'error';

        return 'success';

    }

    public function remove(Request $request)
    {

        if ($message = Message::find($request->id)) {

            Socket::removeByMessageId($request->id);

            $message->delete();

            return 'success';

        }

        return 'error';

    }

    public function send(Request $request)
    {

        if (Socket::pushByMessageId($request->id)) return 'success';

        return 'error';

    }

    public function sendDanmu(Request $request)
    {

        if (Socket::pushByMessageIdWithDanmu($request->id)) return 'success';

        return 'error';

    }

    public function sendAdmin(Request $request)
    {

        if (Socket::pushAdmin($request->channel_id, $request->message)) {

            Message::create([
                'channel_id'=>$request->channel_id,
                'name'=>'管理员',
                'openid'=>'admin',
                'message'=>$request->message,
                'image'=>'/image/admin_avatar.jpg'
            ]);
            return 'success';
        }

        return 'error';

    }

    public function cleanMessage(Request $request)
    {

        if ($request->channel) {

            $messages = Message::where('channel_id', $request->channel)->get();

            if ($messages) {

                foreach ($messages as $message) $message->delete();

            }

        }

        return 0;

    }

    public function exportMessage($id)
    {
        $cellData = [['用户名', '消息', '发送时间']];

        $message = Message::where('channel_id', $id)->select('name as 用户名', 'message as 消息', 'created_at as 发送时间')->get()->toArray();
        $cellData = array_merge($cellData, $message);

        Excel::create('messages', function ($excel) use ($cellData) {
            $excel->sheet('sheet1', function ($sheet) use ($cellData) {
                $sheet->rows($cellData);
            });
        })->export('xlsx');


    }

    public function chatroom(Request $request)
    {

        if ($request->channel) {

            $channel = Channel::find($request->channel);

            if ($channel->chatroom == 1) $channel->chatroom = 0;
            else if ($channel->chatroom == 0) $channel->chatroom = 1;

            $channel->save();

        }

        return 0;

    }

    public function chatroom_audit(Request $request)
    {

        if ($request->channel) {

            $channel = Channel::find($request->channel);

            if ($channel->chatroom_audit == 1) $channel->chatroom_audit = 0;
            else if ($channel->chatroom_audit == 0) $channel->chatroom_audit = 1;

            $channel->save();

        }

        return 0;

    }

    public function chatroom_any(Request $request)
    {

        if ($request->channel) {

            $channel = Channel::find($request->channel);

            if ($channel->chatroom_any == 1) $channel->chatroom_any = 0;
            else if ($channel->chatroom_any == 0) $channel->chatroom_any = 1;

            $channel->save();

        }

        return 0;

    }

    public function chatroom_danmu(Request $request)
    {

        if ($request->channel) {

            $channel = Channel::find($request->channel);

            if ($channel->chatroom_danmu == 1) $channel->chatroom_danmu = 0;
            else if ($channel->chatroom_danmu == 0) $channel->chatroom_danmu = 1;

            $channel->save();

        }

        return 0;

    }
}
