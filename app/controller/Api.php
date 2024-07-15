<?php

namespace app\controller;

use app\controller\Base;
use think\facade\Db;
use think\facade\Log;
use think\Request;

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
            $ToUserName = $obj->ToUserName;
            $FromUserName = $obj->FromUserName;
            $CreateTime = $obj->CreateTime;
            $MsgType = $obj->MsgType;
            $Content = $obj->Content;
            if ($MsgType == "text") {
                //查询数据库业务逻辑
                $result = $this->get("https://www.switchntd.com/wp-admin/api/queryByKey.php?k=" . $Content)->data;

                //print_r($newResult);
                if (count($result) == 0) {
                    return json([
                        "ToUserName" => $FromUserName,
                        "FromUserName" => $ToUserName,
                        "CreateTime" => $CreateTime,
                        "MsgType" => "text",
                        "Content" => "抱歉，暂无此资源，我们将很快更新，请稍后查询~"
                    ]);
                }
                $caolianjie = '';
                foreach ($result as $key => $value) {
                    if ($key < 10) {
                        $title = $value->post_title;
                        $id = $value->id;
                        //$url =  "'" . "https://www.switchntd.com/" . $id . ".html" . "'";
                        $url =  "'" . "https://thinkphp-nginx-bdq6-114871-5-1327940628.sh.run.tcloudbase.com?id=" . $id . "'";
                        $key = $key + 1;
                        $caolianjie .= " $key " . ":" . " <a href=" . $url . ">" . $title . "</a> " . "\n";
                    }
                }
                $url =  "'" . "https://www.switchntd.com" . "'";
                $caolianjie .= " 更多资源请访问 " . ":" . " <a href=" . $url . ">switchntd" . "</a> " . "\n";
                return json_encode([
                    "ToUserName" => $FromUserName,
                    "FromUserName" => $ToUserName,
                    "CreateTime" => $CreateTime,
                    "MsgType" => $MsgType,

                    "Content" => "“" . $Content . "”" . "的查询结果(只显示前10条结果，更多资源请在底部网站中搜索)：" . "\n" . $caolianjie
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            } else {
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
