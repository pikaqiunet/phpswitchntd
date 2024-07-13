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
use think\response\Html;
use think\response\Json;
use think\facade\Log;
use think\Request;
use think\facade\View;
use think\Request as ThinkRequest;

class Index
{

    /**
     * 主页静态页面
     * @return Html
     */
    public function index(Request $request)
    {

        if ($request->isPost()) {
            # 用来验证



        }

        # html路径: ../view/index.html
        return View::fetch();

        
    }

}
