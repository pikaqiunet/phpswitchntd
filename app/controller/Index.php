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

class Index
{

    /**
     * 主页静态页面
     * @return Html
     */
    public function index(Request $request)
    {
        View::assign('flag', false);
        $id = $request->param("id") ?? "";
        View::assign('id', $id);
        if ($request->isPost()) {
            $code = $request->param("code") ?? "";
            $id = $request->param("id") ?? "";
            //验证code是否为真，如果为真，flag设为true 并且根据id返回资源链接，名称，密码
            $sqlcode = Db::table('wp_options')->where(["option_name" => "site-content"])->find();
            if (strtolower(trim($code)) == strtolower(trim($sqlcode["option_value"]))) {
                View::assign('flag', true);
                $result = Db::table('wp_posts')->where('ID', $id)->find();
                View::assign('post_title', $result["post_title"]);
                View::assign('id', $result["ID"]);
            }
        }
        return View::fetch();
    }
}
