<?php

namespace app\controller;

use think\facade\Db;
use think\facade\Log;
use think\Request;

class Api
{
    //小程序密码获取接口
    public function code(Request $request)
    {
        if ($request->isPost()) {
            $result = Db::table('wp_options')->where('option_name', "site-content")->find();
            $code = $result["option_value"];
            //从数据库调用密码
            return json(["code" => $code]);
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
                $result = Db::table('wp_posts')->where('post_title', 'like', '%' . $Content . '%')->select();
                $newResult = [];
                foreach ($result as $value) {
                    //根据文章id，查询meta_key=cao_pwd;meta_key=cao_downurl
                    $url = Db::table('wp_postmeta')->where(["post_id" => $value["ID"], "meta_key" => "cao_downurl"])->find();
                    $code = Db::table('wp_postmeta')->where(["post_id" => $value["ID"], "meta_key" => "cao_pwd"])->find();

                    $value["cao_downurl"] = $url["meta_value"] ?? '';
                    $value["cao_pwd"] = $code["meta_value"] ?? '';
                    $newResult[] = $value;
                    
                }
                //print_r($newResult);
                if (count($newResult) == 0) {
                    return json([
                        "ToUserName" => $FromUserName,
                        "FromUserName" => $ToUserName,
                        "CreateTime" => $CreateTime,
                        "MsgType" => "text",
                        "Content" => "抱歉，暂无此资源，我们将很快更新，请稍后查询~"
                    ]);
                }
                $caolianjie = '';
                foreach ($newResult as $key => $value) {
                    if ($key < 8) {
                        $title = $value["post_title"];
                        $id = $value["ID"];
                        //$url =  "'" . "https://www.switchntd.com/" . $id . ".html" . "'";
                        $url =  "'" . "https://thinkphp-nginx-bdq6-114871-5-1327940628.sh.run.tcloudbase.com?id=" . $id. "'";
                        $key = $key + 1;
                        $caolianjie .= " $key " . ":" . " <a href=" . $url . ">" . $title . "</a> " . "\n";
                    }
                }
                return json_encode([
                    "ToUserName" => $FromUserName,
                    "FromUserName" => $ToUserName,
                    "CreateTime" => $CreateTime,
                    "MsgType" => $MsgType,
                    "Content" => "“" . $Content . "”" . "的查询结果(只显示前8条结果，更多资源请在网站中搜索)：" . "\n" . $caolianjie
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
