<?php

namespace app\controller;

use app\controller\Base;
use think\Request;
use think\facade\Log;

class Api extends Base
{
    //小程序密码获取接口
    public function code(Request $request)
    {
        if ($request->isPost()) {
            $server_code = $this->get("https://www.switchntd.com/wp-admin/api/code.php")->data;
            //从数据库调用密码
            return json(["code" => $server_code]);
        }
    }



    //用户消息回复
    public function message(Request $request)
    {
        if ($request->isPost()) {
            $data = $request->getInput();
            $obj = json_decode($data);

            $ToUserName = $obj->ToUserName ?? '';
            if (!$ToUserName) {
                echo "success";
                exit;
            }

            $FromUserName = $obj->FromUserName;
            $CreateTime = $obj->CreateTime;
            $MsgType = $obj->MsgType;



            if ($MsgType == "event") {


                //被动
                return json([
                    "ToUserName" => $FromUserName,
                    "FromUserName" => $ToUserName,
                    "CreateTime" => $CreateTime,
                    "MsgType" => "text",
                    "Content" => "感谢您的关注，请点击左下角“使用说明”查看使用方式~"
                ]);
                exit;
            }
            if ($MsgType != "text") {
                return json([
                    "ToUserName" => $FromUserName,
                    "FromUserName" => $ToUserName,
                    "CreateTime" => $CreateTime,
                    "MsgType" => "text",
                    "Content" => "信息已收到,文字以外的消息稍后由人工回复，请及时关注公众号聊天消息~"
                ]);
                exit;
            }

            $Content = $obj->Content;


            if ($MsgType == "text") {
                $url = "'" . "https://thinkphp-nginx-bdq6-114871-5-1327940628.sh.run.tcloudbase.com/index/search?keywords=" . $Content . "'";
                $title = "点击查看";
                $caolianjie = " <a href=" . $url . ">" . $title . "</a> " . "\n";
                return json_encode([
                    "ToUserName" => $FromUserName,
                    "FromUserName" => $ToUserName,
                    "CreateTime" => $CreateTime,
                    "MsgType" => $MsgType,
                    "Content" => "“" .  $Content . "”" . "的查询结果如下" . "\n" . $caolianjie
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            }
        }
    }
}
