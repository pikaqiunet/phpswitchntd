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

use Error;
use Exception;
use app\model\Counters;
use think\facade\Db;
use think\response\Html;
use think\response\Json;
use think\facade\Log;
use think\Request;
use think\facade\View;
use think\Request as ThinkRequest;
use think\response\Redirect;
use app\controller\Base;

class Index extends Base
{


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
    public function video(Request $request)
    {
        $url = $request->param("url") ?? "";
        echo $this->get_video($url);
    }
}
