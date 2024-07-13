<?php

namespace app\controller;

use think\facade\Log;
use think\Request;

class Api
{
    //小程序密码获取接口
    public function code(Request $request)
    {

        if ($request->isGet()) {
            //从数据库调用密码
            return json(["code"=>"1234"]);

        }
    }

    //用户消息回复
    public function message(Request $request)
    {
        if ($request->isPost()) {
            $data = $request->getInput();
            $obj = json_decode($data);
            $ToUserName = $obj->ToUserName;
            $FromUserName = $obj->FromUserName;
            $CreateTime = $obj->CreateTime;
            $MsgType = $obj->MsgType;
            $Content = $obj->Content;
            if ($MsgType=="text") {

                //查询数据库业务逻辑

                //<a href="https://github.com/pikaqiunet/phpswitchntd">pikaqiunet/phpswitchntd</a>
                return json([
                    "ToUserName" => $FromUserName,
                    "FromUserName" => $ToUserName,
                    "CreateTime" => $CreateTime,
                    "MsgType" => $MsgType,
                    "Content" => "“".$Content."”"."的查询结果：" .'<a href="https://wwww.switchntd.com">'.$Content.'</a>'
                ]);

            }else{
                return json([
                    "ToUserName" => $FromUserName,
                    "FromUserName" => $ToUserName,
                    "CreateTime" => $CreateTime,
                    "MsgType" => "text",
                    "Content" => "抱歉，暂不支持除文字以外的消息类型回复~"
                ]);
            }

        }
    }
}
