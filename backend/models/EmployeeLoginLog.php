<?php
namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

class EmployeeLoginLog extends Common
{

    private $fieldArray = [
        "id",           //ID
        "login_name",   //用户名
        "login_time",   //登录时间
        "login_ip",     //登录IP
        "login_position",//登录地址
        "login_explorer",//客户端信息
    ];
    public static function tableName()
    {
        return "{{%employee_login_log}}";
    }

    /**
     * 获取字段
     * @return array
     */
    private function _getFields() {
        return $this->fieldArray;
    }

    /**
     * 返回adminList数据
     * @return array
     */
    public function getAdminLoginLogData () {
        $returnData = [
            "draw" => intval(Yii::$app->request->post('draw')),
            "recordsTotal" => 0,
            "recordsFiltered" => 0,
            "data" => null
        ];
        //搜索条件
        $searchWhere  = $this->getSearch(Yii::$app->request->post('extra_search'));//自定义搜索条件

        //得到文章的总数（但是还没有从数据库取数据）
        if(isset($searchWhere['andWhere'])){
            $count = self::getCountByAndWhere($searchWhere['where'], $searchWhere['andWhere']);
        } else {
            $count = self::getCountByWhere($searchWhere);
        }
        $returnData["recordsTotal"] = $returnData['recordsFiltered'] = intval($count);

        //分页
        $offset = "";
        $limit = "";
        $start = Yii::$app->request->post('start');
        $length = Yii::$app->request->post('length');
        $limitFlag = isset($start) && $length != -1;

        if($limitFlag)
        {
            $offset = $start;
            $limit = $length;
        }

        $selectField = "";
        $fields = $this->_getFields();
        foreach($fields as $key => $value)
        {
            $selectField .= ",".$value;
        }
        $selectField = ltrim($selectField,',');
        //排序 order
        $orderSql = 'id ASC';
        if(isset($searchWhere['andWhere'])){
            $returnData['data'] = static::getByAndWhere($searchWhere['where'],$searchWhere['andWhere'], $selectField, $orderSql, $offset, $limit);
        } else {
            $returnData['data'] = static::getByWhere($searchWhere, $selectField, $orderSql, $offset, $limit);
        }
        return $returnData;
    }

    public function getSearch ($search = array()) {
        $where = [];
        $andWhere = [];
        if(!empty($search)){
            $loginName = $search['loginName'];
            $loginIp = $search['loginIp'];
            $loginStartTime = $search['loginStartTime'];
            $loginEndTime = $search['loginEndTime'];
            if(!empty($loginName))
            {
                $where['login_name'] = $loginName;
            }

            if(!empty($loginIp))
            {
                $where['login_ip'] = $loginIp;
            }

            if(!empty($loginStartTime) && empty($loginEndTime))
            {
                $andWhere = ['>','login_time',$loginStartTime. ' 00:00:00'];
            } elseif(empty($loginStartTime) && !empty($loginEndTime)) {
                $andWhere = ['<','login_time',$loginEndTime. ' 23:59:59'];
            } elseif (!empty($loginStartTime) && !empty($loginEndTime)) {
                $andWhere = ['between','login_time',$loginStartTime. ' 00:00:00', $loginEndTime. ' 23:59:59'];
            }
        }
        return [
            'where' => $where,
            'andWhere' => $andWhere
        ];
    }

    public function handelList($list)
    {
        if(!empty($list['data']))
        {
            foreach($list['data'] as $key => $value)
            {
                $list['data'][$key]['login_ip'] = $value['login_ip'] ? $value['login_ip'] : '--';
                $list['data'][$key]['login_position'] = $value['login_position'] ? $value['login_position'] : '--';
            }
        }
        return $list;
    }

}
