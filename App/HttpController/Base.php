<?php
/**
 * Description
 * User: duanwenjie
 * Date: 2019/6/25
 * Time: 11:23 AM
 */

namespace App\HttpController;
use EasySwoole\Core\Http\AbstractInterface\Controller;
class Base extends Controller
{
    public $param;
    function index()
    {
        // TODO: Implement index() method.
        $this->actionNotFound('index');
    }


    protected function onRequest($action):?bool
    {
        return parent::onRequest($action); // TODO: Change the autogenerated stub
    }

    protected function onException(\Throwable $throwable, $actionName): void
    {
        parent::onException($throwable, $actionName); // TODO: Change the autogenerated stub
    }

    /* == 自定义的工具类 == */
    public function returnJson($code = '1', $msg = '', $data = [], $count=null)
    {
        $this->writeJson($code,$data,$msg,$count);
    }

    public function returnJsonData($result = [])
    {
        if (!$this->response()->isEndResponse()) {
            $data = Array("data" => $result);
            $this->response()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            $this->response()->withHeader('Content-type', 'application/json;charset=utf-8');
            return true;
        } else {
            return false;
        }
    }

    public function getParam()
    {
        return parent::getParam(); // TODO: Change the autogenerated stub
    }

    public function isPost()
    {
        $server = $this->request()->getServerParams();
        if ($server['request_method'] == "POST"){
            return true;
        }else{
            return false;
        }
    }

    public function isGet()
    {
        $server = $this->request()->getServerParams();
        if ($server['request_method'] == "GET"){
            return true;
        }else{
            return false;
        }
    }
}