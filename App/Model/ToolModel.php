<?php

namespace App\Model;

use EasySwoole\Config;
use think\Db;
class ToolModel
{
    public static $db;

    /**
     * 工具类初始化
     * 规则：默认数据库为：yujiso，使用其他数据库必须先init，并传入对应的数据库名
     * @param string $database
     * @return Db
     * @author dwj
     */
    static public function init($database = "yujiso")
    {
        $dbConfig = Config::getInstance()->getConf($database);
        return self::$db = new Db($dbConfig);
    }

    /**
     * 新增或更新数据
     * @param string $tablename 表名
     * @param array $data 数据
     * @param array $update_filter 更新时候需要过滤的字段
     */
    static public function dbInsertOrUpdate($tablename, $data, $update_filter = ['ctime'])
    {
        if (self::$db === null) self::init();
        if (empty($tablename) || empty($data)){
            return false;
        }
        $insert_keys = array_keys($data[0]);
        $field_sql = '(' . implode(',', $insert_keys) . ')';
        $value_sql = '';
        foreach ($data as $itm){
            $itm = array_map(function ($val){
                return "'" . addslashes(trim($val)) . "'";
            }, $itm);
            $value_sql .= '(' . implode(',', $itm) . '),';
        }
        $value_sql = substr($value_sql, 0, -1);
        $update_keys = array_diff($insert_keys, $update_filter);
        $update_sql = '';
        foreach ($update_keys as $key){
            $update_sql .= "{$key}=VALUES({$key}),";
        }
        $update_sql = substr($update_sql, 0, -1);
        $sql = "INSERT INTO {$tablename} {$field_sql} VALUES {$value_sql} ON DUPLICATE KEY UPDATE {$update_sql}";
        $result = self::$db->connect()->execute($sql);

        return $result;

    }

    /**
     * 查询一条数据
     * @param string $tablename 表名
     * @param array $data 数据
     * @param array $update_filter 更新时候需要过滤的字段
     */
    static public function find($table, $where = array(), $fileds = '*', $order = '')
    {
        if (self::$db === null) self::init();

        return self::$db->table($table)->field($fileds)->where($where)->order($order)->find();
    }

    //返回字段值 无返回null
    static public function getValue($table, $where = array(), $filed = 'id', $order = '')
    {
        if (self::$db === null) self::init();

        return self::$db->table($table)->where($where)->order($order)->limit(1)->value($filed);
    }

    /**
     * 插入数据
     * @param array $data
     * @return int 如果$data是一维数组的话，返回值是插入的id，如果是二维数组的话，返回插入行数
     */
    static public function insert($data = array(), $table = '')
    {
        if (self::$db === null) self::init();
        if (!is_array($data)){
            return 0;
        }
        if (count($data) == count($data, 1)){
            return self::$db->name($table)->insertGetId($data);
        }else{
            return self::$db->name($table)->insertAll($data);
        }
    }

    /**
     * 删除数据
     * @param array $where
     * @return int 返回影响行数
     */
    static public function delete($where = array(), $table = '')
    {
        if (self::$db === null) self::init();

        return self::$db->name($table)->where($where)->delete();
    }

    /**
     * 更新数据
     * @param array $where
     * @return int 返回影响行数
     */
    static public function update($where = array(), $data = array(), $table = '')
    {
        if (self::$db === null) self::init();

        return self::$db->name($table)->where($where)->update($data);
    }


    /**
     * 根据条件查询获得数据
     * @param array $where
     * @param string $fileds
     * @return array 返回二维数组，未找到记录则返回空数组
     */
    static public function select($table, $where = array(), $fileds = "*", $order = "", $key = "")
    {
        if (self::$db === null) self::init();
        $db_ = self::$db->name($table)->field($fileds)->where($where)->order($order);
        if (empty($key)){
            return $db_->select();
        }else{
            return $db_->column($fileds, $key);
        }

    }

    /**
     * 查询全部数据有分页查询
     * @param array $where
     * @param string $fileds
     * @param string $offset
     * @param string $num
     * @param string $order
     * @return array 返回二维数组，未找到记录则返回空数组
     */
    static public function selectByLimit($table = '', $fileds = '*', $where, $offset = 0, $num = 1, $order = "id desc")
    {
        if (self::$db === null) self::init();

        return self::$db->table($table)->field($fileds)->where($where)->order($order)->limit("$offset,$num")->select();
    }

    /**
     * 联表查询语句
     * @param array $where
     * @param string $fileds
     * @param array $join $join = [['think_work w','a.id=w.artist_id'],['think_card c','a.card_id=c.id']];
     * @param string $offset
     * @param string $num
     * @param string $order
     * @param string $pagination 是否有分页
     * @return array 返回二维数组，未找到记录则返回空数组
     */
    static public function join($table = '', $where, $fileds = '*', $join = array(), $offset = 0, $num = 1, $order = "a.id desc", $pagination = true)
    {
        if (self::$db === null) self::init();
        if ($pagination){
            $result = self::$db->table($table)->alias('a')->field($fileds)->join($join)->where($where)->order($order)->limit("$offset,$num")->select();
        }else{
            $result = self::$db->table($table)->alias('a')->field($fileds)->join($join)->where($where)->order($order)->select();
        }

        return $result;
    }

    /**
     * 原生态查询
     * @param string $sql
     * @return array 返回二维数组，未找到记录则返回false
     */
    static public function query($sql)
    {
        if (self::$db === null) self::init();

        return self::$db->query($sql);
    }


    /**
     * 批量插入数据
     * @param $tablename
     * @param $data
     * @return bool
     * @author duanwenjie
     */
    static public function dbInsertAll($tablename, $data)
    {
        if (self::$db === null) self::init();
        self::$db->connect()->name($tablename)->insertAll($data);

        return true;
    }


    /**
     * 获取最近一次查询的sql语句
     * @return mixed
     * @author duanwenjie
     */
    public static function getLastSql()
    {
        if (self::$db === null) self::init();

        return self::$db->getLastSql();
    }


    /**
     * 执行语句
     * @param $sql
     * @param array $bind
     * @return mixed
     * @author duanwenjie
     */
    static public function execute($sql, $bind = [])
    {
        if (self::$db === null) self::init();

        return self::$db->execute($sql, $bind, self::$db);
    }

    //事务机制处理
    static public function transaction($callback)
    {
        if (self::$db === null) self::init();

        return self::$db->transaction($callback);
    }

    static public function startTrans()
    {
        if (self::$db === null) self::init();

        return self::$db->startTrans();
    }

    static public function rollback()
    {
        if (self::$db === null) self::init();

        return self::$db->rollback();
    }

    static public function commit()
    {
        if (self::$db === null) self::init();

        return self::$db->commit();
    }
}
