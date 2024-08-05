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
            $Content = $obj->Content;

            if ($MsgType == "event") {
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




            if ($MsgType == "text") {

                $caolianjie = '';
                //资源搜索
                //查询数据库业务逻辑
                $server_result = $this->get("https://www.switchba.com/api/v2/queryByKey.php?k=" . $Content);
                $result = $server_result->data;
                foreach ($result as $key => $value) {
                    if ($key < 10) {
                        $title = $value->post_title;
                        $id = $value->id;
                        $url =  "'" . "https://thinkphp-nginx-bdq6-114871-5-1327940628.sh.run.tcloudbase.com/index/other?id=" . $id . "'";
                        $key = $key + 1;
                        $caolianjie .= "资源:" . " $key " . ":" . " <a href=" . $url . ">" . $title . "</a> " . "\n";
                    }
                }
                //电影搜索
                //查询数据库业务逻辑
                $server_result = $this->get("https://www.switchba.com/api/queryByKey.php?k=" . $Content);
                $result = $server_result->data;

                foreach ($result as $key => $value) {
                    if ($key < 10) {
                        $title = $value->post_title;
                        $id = $value->id;
                        //$url =  "'" . "https://www.switchntd.com/" . $id . ".html" . "'";
                        $url =  "'" . "https://thinkphp-nginx-bdq6-114871-5-1327940628.sh.run.tcloudbase.com/index/video_detail?id=" . $id . "'";
                        $key = $key + 1;
                        $caolianjie .= "电影:" . " $key " . ":" . " <a href=" . $url . ">" . $title . "</a> " . "\n";
                    }
                }
                $url = "'" . "https://video.switchba.com/vodsearch/-------------.html?wd=" . $Content . "'";
                $caolianjie .= " 更多资源" . ":" . " <a href=" . $url . ">点击查看" . "</a> " . "\n";

                //游戏搜索
                //查询数据库业务逻辑
                $server_result = $this->get("https://www.switchntd.com/wp-admin/api/queryByKey.php?k=" . $Content);
                $result = $server_result->data;
                foreach ($result as $key => $value) {
                    if ($key < 10) {
                        $title = $value->post_title;
                        $id = $value->id;
                        //$url =  "'" . "https://www.switchntd.com/" . $id . ".html" . "'";
                        $url =  "'" . "https://thinkphp-nginx-bdq6-114871-5-1327940628.sh.run.tcloudbase.com?id=" . $id . "'";
                        $key = $key + 1;
                        $caolianjie .= "游戏:" . " $key " . ":" . " <a href=" . $url . ">" . $title . "</a> " . "\n";
                    }
                }
                $caolianjie .= " 更多游戏资源推荐浏览器访问:switchntd.com";
                return json_encode([
                    "ToUserName" => $FromUserName,
                    "FromUserName" => $ToUserName,
                    "CreateTime" => $CreateTime,
                    "MsgType" => $MsgType,
                    "Content" => "“" .  $Content . "”" . "的查询结果为" . $server_result->count . "条(由于长度限制,只显示前10条结果,更多结果请在底部网站中搜索)：" . "\n" . $caolianjie
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            }
        }
    }
}
