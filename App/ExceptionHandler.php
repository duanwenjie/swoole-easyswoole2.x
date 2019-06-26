<?php

/**
 * Description
 * User: duanwenjie
 * Date: 2019/6/26
 * Time: 6:05 PM
 */
namespace App;

use EasySwoole\Core\Http\AbstractInterface\ExceptionHandlerInterface;
use EasySwoole\Core\Http\Request;
use EasySwoole\Core\Http\Response;
class ExceptionHandler implements ExceptionHandlerInterface
{
    public function handle(\Throwable $throwable, Request $request, Response $response)
    {
        // TODO: Implement handle() method.
        $data = Array(
            "code" => 500,
            "result" => "系统错误",
            "msg" => "错误：【".$throwable->getMessage()."】文件：【".$throwable->getFile()."】行：【".$throwable->getLine()."】"
        );
        $response->write(json_encode($data,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
        $response->withHeader('Content-type','application/json;charset=utf-8');
        $response->withStatus(500);
        return true;
    }

}