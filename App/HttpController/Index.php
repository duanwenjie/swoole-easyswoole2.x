<?php

/**
 * Description
 * User: duanwenjie
 * Date: 2019/6/26
 * Time: 4:37 PM
 */
namespace App\HttpController;

class Index extends Base
{
    function index()
    {
        // TODO: Implement index() method.
        $this->response()->write("hello world easyswoole \r\n");
        $this->returnJson('1','hello world easyswoole');
    }

    function test()
    {
        $param = $this->getParam();

        $this->returnJsonData($param);
    }

    function test2()
    {
        $s = new B();
    }

}