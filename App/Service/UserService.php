<?php
/**
 * Description
 * User: duanwenjie
 * Date: 2019/5/9
 * Time: 11:59 AM
 */

namespace App\Service;

use App\Model\ToolModel;
use think\Exception;
class UserService extends BaseService
{
    static public function getUserList($param, $is_download = false)
    {
        try{
            $data = ToolModel::init()->name('user')->order('id','DESC')->find();
            return $data;
        }catch (Exception $e){
            throw new Exception($e->getMessage());
        }
    }

}