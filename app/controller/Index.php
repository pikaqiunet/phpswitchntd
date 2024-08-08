<?php
// +----------------------------------------------------------------------
// | 文件: index.php
// +----------------------------------------------------------------------
// | 功能: 提供todo api接口
// +----------------------------------------------------------------------
// | 时间: 2021-11-15 16:20
// +----------------------------------------------------------------------
// | 作者: rangangwei<gangweiran@tencent.com>
// +----------------------------------------------------------------------

namespace app\controller;

use think\Request;
use think\facade\View;

use app\controller\Base;

class Index extends Base
{

    //

    //统一查询入口
    public function search(Request $request)
    {

        if ($request->isPost()) {
            $code = $request->param("code") ?? "";
            $Content = $request->param("keywords") ?? "";
            View::assign("keywords", $request->param("keywords") ?? "");

            $server_code = $this->get("https://www.switchntd.com/wp-admin/api/code.php")->data;
            if (strtolower(trim($code)) == strtolower(trim($server_code))) {
                //游戏搜索
                //查询数据库业务逻辑
                $server_result = $this->get("https://www.switchntd.com/wp-admin/api/queryByKey.php?" . http_build_query([
                    "k" => $Content
                ]));
                $result1 = $server_result->data;

                //资源搜索
                //查询数据库业务逻辑
                $server_result = $this->get("https://www.switchba.com/api/v2/queryByKey.php?" .  http_build_query([
                    "k" => $Content
                ]));
                $result2 = $server_result->data;
                //电影搜索
                //查询数据库业务逻辑
                $server_result = $this->get("https://www.switchba.com/api/queryByKey.php?" .  http_build_query([
                    "k" => $Content
                ]));
                $result3 = $server_result->data;
                View::assign("code", $server_code);
                View::assign("result1", $result1);
                View::assign("result2",  $result2);
                View::assign("result3",  $result3);
                return View::fetch('search_result');
            } else {
                View::assign("keywords", $request->param("keywords") ?? "");
                View::assign("verify", false);
                return View::fetch();
            }
        }
        View::assign("verify", true);
        View::assign("keywords", $request->param("keywords") ?? "");
        return View::fetch();
    }



    public function other(Request $request)
    {
        View::assign('flag', false);
        $id = $request->param("id") ?? "";
        View::assign('id', $id);
        View::assign('error', '');
        if ($request->isPost()) {
            $server_code = $this->get("https://www.switchntd.com/wp-admin/api/code.php")->data;
            $code = $request->param("code") ?? "";
            $id = $request->param("id") ?? "";
            if (strtolower(trim($code)) == strtolower(trim($server_code))) {
                if ($id) {
                    $server_result = $this->get("https://www.switchba.com/api/v2/queryById.php?id=" . $id);
                    View::assign('flag', true);
                    View::assign('downurl', ($server_result->data->down_url) ?? '');
                    View::assign('downcode', ($server_result->data->down_code) ?? '');
                    View::assign('post_title', ($server_result->data->post_title) ?? '');
                    View::assign('id', ($server_result->data->id) ?? '');
                }
            } else {
                View::assign('error', "搜索码错误！");
                return View::fetch();
            }
        }
        return View::fetch('');
    }

    public function index(Request $request)
    {
        View::assign('flag', false);
        $id = $request->param("id") ?? "";
        View::assign('id', $id);
        View::assign('error', '');
        if ($request->isPost()) {
            $server_code = $this->get("https://www.switchntd.com/wp-admin/api/code.php")->data;
            $code = $request->param("code") ?? "";
            $id = $request->param("id") ?? "";
            if (strtolower(trim($code)) == strtolower(trim($server_code))) {
                if ($id) {
                    $server_result = $this->get("https://www.switchntd.com/wp-admin/api/queryById.php?id=" . $id);
                    View::assign('flag', true);
                    View::assign('downurl', ($server_result->data->down_url) ?? '');
                    View::assign('downcode', ($server_result->data->down_code) ?? '');
                    View::assign('post_title', ($server_result->data->post_title) ?? '');
                    View::assign('id', ($server_result->data->id) ?? '');
                }
            } else {
                View::assign('error', "搜索码错误！");
                return View::fetch();
            }
        }
        return View::fetch();
    }
    public function video_detail(Request $request)
    {
        View::assign('flag', false);
        $id = $request->param("id") ?? "";
        View::assign('id', $id);
        View::assign('error', '');
        if ($request->isPost()) {
            $server_code = $this->get("https://www.switchntd.com/wp-admin/api/code.php")->data;
            $code = $request->param("code") ?? "";
            $id = $request->param("id") ?? "";
            if (strtolower(trim($code)) == strtolower(trim($server_code))) {
                if ($id) {
                    $server_result = $this->get("https://www.switchba.com/api/queryById.php?id=" . $id);
                    $data = $server_result->data;
                    $title = $data->post_title;
                    $url = $data->post_url;
                    $vod_play_url = $data->vod_play_url;
                    $vod_play_url = explode("#", $vod_play_url);
                    $vod_play_url_arr = [];
                    foreach ($vod_play_url as $value) {
                        $tmp = [];
                        $arr = explode('$', $value);
                        $tmp[] = $arr[0];
                        $tmp[] = $arr[1];
                        $vod_play_url_arr[] = $tmp;
                    }
                    View::assign('title', $title);
                    View::assign('url', $url);
                    View::assign('vod_play_url_arr', $vod_play_url_arr);
                    View::assign('flag', true);
                    return View::fetch('video_detail');
                }
            } else {
                View::assign('error', "搜索码错误！");
                return View::fetch('video_detail');
            }
        }
        return View::fetch('video_detail');
    }

    public function video(Request $request)
    {
        $url = $request->param("url") ?? "";
        echo $this->get_video($url);
    }
}
