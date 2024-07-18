<?php

namespace app\controller;

use app\controller\Base;
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
            $ToUserName = $obj->ToUserName ?? '';
            if (!$ToUserName) {
                echo "success";
                exit;
            }
            $FromUserName = $obj->FromUserName;
            $CreateTime = $obj->CreateTime;
            $MsgType = $obj->MsgType;
            $Content = $obj->Content;
            $Content = explode("#", $Content);
            if ($MsgType == "text") {
                //判断指令是否有误
                if (!(($Content[0] ?? '') && ($Content[1] ?? ''))) {
                    //查询数据库业务逻辑
                    $server_result = $this->get("https://www.switchntd.com/wp-admin/api/queryByKey.php?k=" . $Content[0]);
                    $result = $server_result->data;
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
                    $url =  "'" . "https://www.switchntd.com/?s=" . $Content[0] . "'";
                    $caolianjie .= " 更多资源请访问 " . ":" . " <a href=" . $url . ">switchntd.com" . "</a> " . "\n";
                    return json_encode([
                        "ToUserName" => $FromUserName,
                        "FromUserName" => $ToUserName,
                        "CreateTime" => $CreateTime,
                        "MsgType" => $MsgType,
                        "Content" => "“" .  $Content[0] . "”" . "的查询结果为" . $server_result->count . "条(由于长度限制,只显示前10条结果,更多结果请在底部网站中搜索)：" . "\n" . $caolianjie
                    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                }

                if ($Content[0] == "游戏") {
                    //查询数据库业务逻辑
                    $server_result = $this->get("https://www.switchntd.com/wp-admin/api/queryByKey.php?k=" . $Content[1]);
                    $result = $server_result->data;
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
                    $url =  "'" . "https://www.switchntd.com/?s=" . $Content[1] . "'";
                    $caolianjie .= " 更多资源请访问 " . ":" . " <a href=" . $url . ">switchntd.com" . "</a> " . "\n";
                    return json_encode([
                        "ToUserName" => $FromUserName,
                        "FromUserName" => $ToUserName,
                        "CreateTime" => $CreateTime,
                        "MsgType" => $MsgType,

                        "Content" => "“" . $Content[1] . "”" . "的查询结果为" . $server_result->count . "条(由于长度限制,只显示前10条结果,更多结果请在底部网站中搜索)：" . "\n" . $caolianjie
                    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                } elseif ($Content[0] == "电影") {

                    //查询数据库业务逻辑
                    $server_result = $this->get("https://www.switchba.com/api/queryByKey.php?k=" . $Content[1]);
                    $result = $server_result->data;
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
                            $url =  "'" . "https://thinkphp-nginx-bdq6-114871-5-1327940628.sh.run.tcloudbase.com/index/video_detail?id=" . $id . "'";
                            $key = $key + 1;
                            $caolianjie .= " $key " . ":" . " <a href=" . $url . ">" . $title . "</a> " . "\n";
                        }
                    }
                    $url =  "'" . "https://video.switchba.com/vodsearch/-------------.html?wd=".$Content[1]. "'";
                    $caolianjie .= " 更多资源请访问 " . ":" . " <a href=" . $url . ">video.switchba.com" . "</a> " . "\n";
                    return json_encode([
                        "ToUserName" => $FromUserName,
                        "FromUserName" => $ToUserName,
                        "CreateTime" => $CreateTime,
                        "MsgType" => $MsgType,

                        "Content" => "“" . $Content[1] . "”" . "的查询结果为" . $server_result->count . "条(由于长度限制,只显示前10条结果,更多结果请在底部网站中搜索)：" . "\n" . $caolianjie
                    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                } else {
                    //查询数据库业务逻辑
                    $server_result = $this->get("https://www.switchba.com/api/v2/queryByKey.php?k=" . $Content[1] . "&t=" . $Content[0]);
                    $result = $server_result->data;
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
                            $url =  "'" . "https://thinkphp-nginx-bdq6-114871-5-1327940628.sh.run.tcloudbase.com/index/other?id=" . $id . "'";
                            $key = $key + 1;
                            $caolianjie .= " $key " . ":" . " <a href=" . $url . ">" . $title . "</a> " . "\n";
                        }
                    }
                    return json_encode([
                        "ToUserName" => $FromUserName,
                        "FromUserName" => $ToUserName,
                        "CreateTime" => $CreateTime,
                        "MsgType" => $MsgType,

                        "Content" => "“" . $Content[1] . "”" . "的查询结果为" . $server_result->count . "条(由于长度限制,只显示前10条结果,更多结果请在底部网站中搜索)：" . "\n" . $caolianjie
                    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                }
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
