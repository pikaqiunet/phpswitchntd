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
                $result=[];
                if ($id) {
                    $result = Db::table('wp_posts')->where('ID', $id)->find();
                }
                $downurl = Db::table('wp_postmeta')->where(["post_id" => $result["ID"], "meta_key" => "cao_downurl"])->find();
                $downcode = Db::table('wp_postmeta')->where(["post_id" => $result["ID"], "meta_key" => "cao_pwd"])->find();
                View::assign('downurl', $downurl["meta_value"]??'');
                View::assign('downcode', $downcode["meta_value"]??'');
                View::assign('post_title', $result["post_title"]??'');
                View::assign('id', $result["ID"]??'');

            }
        }
        return View::fetch();

    }
    public function index2(){
        // $options = [
        //     "ssl" => [
        //         "verify_peer" => false,
        //         "verify_peer_name" => false
        //     ]
        // ];
        $url = "https://www.switchba.com/index.php/ajax/suggest?mid=1&limit=10&wd=天使";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //如果目标网站的SSL证书无法验证，可以添加以下选项
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        $obj=json_decode($response);
        print_r($obj->list);


        //return json(file_get_contents("https://www.switchba.com/index.php/ajax/suggest?mid=1&limit=10&wd=天使",false,stream_context_create($options)));
    }
}
