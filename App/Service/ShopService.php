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
class ShopService extends BaseService
{
    static public function test()
    {
//        $res = ToolModel::query("SELECT * FROM base_shop WHERE shop_name = '印尼2店' ");
//        $res2 = ToolModel::init('hegui')->name('base_country')->field('*')->limit(5)->select();
//        $res2 = ToolModel::init()->name('base_shop')->field('*')->select();
        $res2 = ToolModel::init('hegui')->name('audit_listing')->find();
        $res = ToolModel::find('audit_listing');
        return [$res,$res2];
    }

    static public function getShopList($param, $is_download = false)
    {
        try{
            $where = "where 1 = 1 ";
            $pageNumber = isset($param['page']) ? $param['page'] : 1;
            $pageData = isset($param['limit']) ? $param['limit'] : 20;
            $shop_name = isset($param['shop_name']) ? $param['shop_name'] : '';  //店铺名称
            $shop_id = isset($param['id']) ? $param['id'] : '';  //店铺代码
            $country_name = isset($param['country_name']) ? $param['country_name'] : '';  //所属国家
            $status = isset($param['status']) ? $param['status'] : '';  //状态
            $type = isset($param['type']) ? $param['type'] : '';  //店铺类型
            $offset = ($pageNumber - 1) * $pageData;
            $limit = " LIMIT $offset,$pageData";

            if (!empty($shop_name)) {
                $shop_name = trim($shop_name);
                $where .= " AND BS.shop_name = '{$shop_name}'";
            }
            if (!empty($shop_id)) {
                $shop_id = trim($shop_id);
                $where .= " AND BS.id = {$shop_id}";
            }
            if (!empty($country_name)) {
                $country_name = trim($country_name);
                $where .= " AND BS.base_country_name = '{$country_name}'";
            }
            if (!empty($status)) {
                $where .= " AND BS.status = {$status}";
            }
            if (!empty($type)) {
                $where .= " AND BS.type = {$type}";
            }

            $sqlCount = "SELECT COUNT(BS.id) num FROM base_shop BS {$where}";
            $countArr = ToolModel::query($sqlCount);
            if (empty($countArr) || $countArr[0]['num'] == 0) {
                return array('total' => 0, 'list' => array());
            }
            $fileds = 'BS.id,BS.shop_name,BS.base_country_name,BS.address,
                      case BS.type when 1 then "自营" when 2 then "联营" when 3 then "加盟" end type_name,
                      case BS.status when 1 then "启用" when -1 then "禁用" end status_name,BS.type,BS.status,
                      BS.sap,BS.responsibility_name,BS.responsibility_phone,BC.company_name,ER.name as currency_name,BS.operator,BS.update_time'; //

            $sql = "SELECT {$fileds} FROM base_shop BS LEFT JOIN base_company BC ON BS.base_company_id = BC.id
            LEFT JOIN exchange_rate ER ON BS.exchange_rate_id = ER.id {$where} ORDER BY BS.id DESC {$limit}";
            if ($is_download) $sql = "SELECT {$fileds} FROM base_shop BS LEFT JOIN base_company BC ON BS.base_company_id = BC.id {$where} ORDER BY BS.id DESC ";
            $list = ToolModel::query($sql);
            $data = array();
            $data['total'] = intval($countArr[0]['num']);
            $data['list'] = $list;

            return $data;
        }catch (Exception $e){
            throw new Exception($e->getMessage());
        }
    }

}