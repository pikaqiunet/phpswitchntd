<?php

namespace app\controller;

use think\facade\Log;
use think\Request;

class Api
{

    public function message(Request $request)
    {


        if ($request->isPost()) {
            Log::write('post后');
            $data = $request->getInput();
            $obj = json_decode($data);
            $ToUserName = $obj->ToUserName;
            $FromUserName = $obj->FromUserName;
            $CreateTime = $obj->CreateTime;
            $MsgType = $obj->MsgType;
            $Content = $obj->Content;
            Log::write('post后');
            
            return response(json_encode([
                "ToUserName" => $FromUserName,
                "FromUserName" => $ToUserName,
                "CreateTime" => $CreateTime,
                "MsgType" => $MsgType,
                "Content" => "回复：" . $Content
            ]));
        }
    }
}
