<?php
namespace app\controller;
use think\facade\Log;
use think\Request;
class Api
{

    public function message(Request $request){
        
        $data=$request;
        print_r($data->getInput());
        Log::write('getCount rsp: '.json_encode($data->getInput()));
    }

}