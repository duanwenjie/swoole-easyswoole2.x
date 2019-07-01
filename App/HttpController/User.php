<?php
/**
 * Description
 * User: duanwenjie
 * Date: 2019/6/27
 * Time: 11:35 AM
 */

namespace App\HttpController;

use App\Model\ToolModel;
use App\Service\UserService;

class User extends Base
{
    public function getUsers()
    {
        $param = $this->getParam();
        $result = UserService::getUserList($param);
        $this->returnJson('1','操作成功',$result);
    }

}