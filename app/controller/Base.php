<?php

namespace app\controller;

class Base
{
    protected function get($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //如果目标网站的SSL证书无法验证，可以添加以下选项
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        $obj = json_decode($response);
        return $obj;
    }
    protected function get_file($url)
    {
        // 禁用 SSL 证书验证
        $context = stream_context_create(array(
            'http' => array(
                'header' => "Connection: close\r\n"
            ),
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
            )
        ));

        return file_get_contents($url, false, $context);
    }

    protected function get_video($url)
    {
        $url = "https://bofang.ikdmjx.com/?url=" . $url;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //如果目标网站的SSL证书无法验证，可以添加以下选项
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}
