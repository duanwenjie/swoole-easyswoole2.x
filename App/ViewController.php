<?php
/**
 * Description
 * User: duanwenjie
 * Date: 2019/6/27
 * Time: 9:12 AM
 */

namespace App;

use EasySwoole\Config;
use EasySwoole\Core\Http\AbstractInterface\Controller;
use EasySwoole\Core\Http\Request;
use EasySwoole\Core\Http\Response;
use think\Template;
abstract class ViewController extends Controller
{
    protected $view;

    /**
     * 初始化模板引擎
     * ViewController constructor.
     * @param string $actionName
     * @param Request $request
     * @param Response $response
     */
    function __construct(string $actionName, Request $request, Response $response)
    {
        $this->view = new Template();
        $tempPath   = Config::getInstance()->getConf('TEMP_DIR');     # 临时文件目录
        $this->view->config([
            'view_path'  => EASYSWOOLE_ROOT . '/Views/',              # 模板文件目录
            'cache_path' => "{$tempPath}/templates_c/",               # 模板编译目录
        ]);

        parent::__construct($actionName, $request, $response);
    }

    function fetch($template, $vars = [])
    {
        ob_start();
        $this->view->fetch($template, $vars);
        $content = ob_get_clean();
        $this->response()->write($content);
    }

}