<?php
/**
 * Description
 * User: duanwenjie
 * Date: 2019/5/9
 * Time: 11:30 AM
 */

namespace App\HttpController;

use App\Service\ShopService;
class Shop extends Base
{
    /**
     * 获取店铺列表
     * @author duanwenjie
     */
    public function getShopList()
    {
        $param = $this->getParam();
        $result = ShopService::getShopList($param);
        $this->returnJson('1','查询成功',$result['list'],$result['total']);
    }
}