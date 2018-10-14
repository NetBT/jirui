<?php
namespace backend\models;

use common\models\Status;
use Yii;
use yii\base\Exception;
use common\models\Functions;

class OperateLog extends Common
{

    private $fieldArray = [
        "id",                   //主键
        "operate_user_id",      //操作ID
        "operate_condition",    //操作条件
        "operate_content",      //操作内容
        "create_time",          //创建时间
        "mark",                 //备注
    ];

    public static function tableName()
    {
        return '{{%operate_log}}';
    }

    /**
     * 获取字段
     * @return array
     */
    private function _getFields() {
        return $this->fieldArray;
    }

    public function getListData()
    {
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

        //设置分页
        $this->setPagination();

        $selectField = "";
        $fields = $this->_getFields();
        foreach($fields as $key => $value)
        {
            $selectField .= ",".$value;
        }
        $selectField = ltrim($selectField,',');
        //排序 order
        $orderSql = 'id Desc';
        if(isset($searchWhere['andWhere'])){
            $returnData['data'] = static::getByAndWhere($searchWhere['where'],$searchWhere['andWhere'], $selectField, $orderSql, $this->_Pagination['offset'], $this->_Pagination['limit']);
        } else {
            $returnData['data'] = static::getByWhere($searchWhere, $selectField, $orderSql, $this->_Pagination['offset'], $this->_Pagination['limit']);
        }
        return $returnData;
    }

    /**
     * 自定义参数的搜索  搜索,搜索也分每一列
     * 这里要根据业务逻辑进行修改
     * @param $search
     * @return string
     */
    public function getSearch ($search = [])
    {
        $where = [];
        $andWhere = [];
        //获取加盟商ID
        $businessId = Common::getBusinessId();
        if($businessId){
            //获取加盟商下面的所有用户
            $employeeIdList = Employee::getFormArray(['alliance_business_id' => $businessId],'id','id');
            //获取操作日志关联的用户
            $where['operate_user_id'] = $employeeIdList;
        }
        if(!empty($search)){
            $operateUserId = $search['operateUserId'];
            $startTime = $search['startTime'];
            $endTime = $search['endTime'];
            if($operateUserId)
            {
                $employeeInfo = Employee::getOneByWhere(['employee_name' => $operateUserId], 'id');
                $userId = $employeeInfo['id'];
                $where['operate_user_id'] = $userId;
            }
            if(!empty($startTime) && empty($endTime))
            {
                $andWhere = ['>','create_time',$startTime];
            } elseif(empty($startTime) && !empty($endTime)) {
                $andWhere = ['<','create_time',$endTime];
            } elseif (!empty($startTime) && !empty($endTime)) {
                $andWhere = ['between','create_time',$startTime, $endTime];
            }
        }
        return [
            'where' => $where,
            'andWhere' => $andWhere
        ];
    }

    /**
     * 进行初始化数据处理
     * @param array $list
     * @return array
     */
    public function handelInit($list = [])
    {
        $abInfo = AB::getByWhere('','id,AB_name');
        $abList = Functions::extractKey($abInfo,'id','AB_name');

        $employeeInfo = Employee::getByWhere('','id,employee_name,alliance_business_id');
        $employeeList = Functions::extractKey($employeeInfo,'id');
        foreach($list['data'] as $key => $value)
        {

            $operateCondition = json_decode($value['operate_condition'],true);
            $operateConditionStr = '';
            if($operateCondition) {
                foreach ($operateCondition as $kk => $vv){
                    $operateConditionStr .= '【'.$vv['where'].'】为\''.$vv['value'].'\'</br>';
                }
            }
            $operateConditionStr = $operateConditionStr ? addslashes($operateConditionStr) : '--'; //转义单引号
            $list['data'][$key]['short_operate_condition'] = stripslashes(Functions::chinese_str_cut($operateConditionStr,100));//删除转义
            $list['data'][$key]['operate_content'] = $operateConditionStr;
            $operateContent = json_decode($value['operate_content'],true);
            $operateContentStr = '';
            if($operateContent){
                foreach ($operateContent as $kkk => $vvv){
                    if(count($vvv) > 2){
                        $oldData = is_array($vvv['oldData']) ? implode(',',$vvv['oldData']) : $vvv['oldData'];
                        $newData = is_array($vvv['newData']) ? implode(',',$vvv['newData']) : $vvv['newData'];
                        $operateContentStr .= '将【'.$vvv['item'].'】的\''.$oldData.'\'改为\''.$newData.'\'</br>';
                    } else {
                        $operateContentStr .= '【'.$vvv['item'].'】数据为\''.$vvv['data'].'\'</br>';
                    }
                }
            }
            $operateContentStr = $operateContentStr ? addslashes($operateContentStr) : '--';//转义单引号
            $list['data'][$key]['short_operate_content'] = stripslashes(Functions::chinese_str_cut($operateContentStr,100));//删除转义
            $list['data'][$key]['operate_content'] = $operateContentStr;
            $list['data'][$key]['operate_user_id'] = $employeeList[$value['operate_user_id']]['employee_name'];
            $list['data'][$key]['operate_ab_id'] = '总部';
            if($employeeList[$value['operate_user_id']]['alliance_business_id'] && $employeeList[$value['operate_user_id']]['alliance_business_id'] != 1) {
                $list['data'][$key]['operate_ab_id'] = $abList[$employeeList[$value['operate_user_id']]['alliance_business_id']];
            }
//            $list['data'][$key]['operate_ab_id'] = $employeeList[$value['operate_user_id']]['alliance_business_id'] ? '--' : '总部';

        }
        return $list;
    }
}
