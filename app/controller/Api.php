<?php
namespace app\controller;
use think\facade\Log;
use think\Request;
class Api
{

    public function message(Request $request){
        
        
        if($request->isPost()){
            $data=$request->getInput();
            $obj=json_decode($data);
            $ToUserName=$obj->ToUserName;
            $FromUserName=$obj->FromUserName;
            $CreateTime=$obj->CreateTime;
            $MsgType=$obj->MsgType;
            $Content=$obj->Content;
                          
           return response(json_encode([
             "ToUserName"=>$FromUserName,
             "ToUserName"=>$ToUserName,
             "CreateTime"=>$CreateTime,
             "MsgType"=>$MsgType,
             "Content"=>"回复：".$Content
           ]));   

        }
        
    }

}