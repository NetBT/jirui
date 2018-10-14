<?php
namespace backend\models;

use common\models\Status;
use Yii;
use yii\base\Exception;
use common\models\Functions;

class RecommendRebate extends Common
{

    private $fieldArray = [
        "id",            //主键
        "recommend_id",  //推荐人
        "target_id",     //被推荐人
        "rebate_money",  //返利金额
        "create_time",   //创建时间
        "business_id",   //商户号
        "mark",          //备注
    ];

    public static function tableName()
    {
        return '{{%recommend_rebate_log}}';
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
        $orderSql = 'id ASC';
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
        $where['business_id'] = Common::getBusinessId();

        if(!empty($search)){
            $name = isset($search['name']) ? $search['name'] : '';
            $startTime = isset($search['startTime']) ? $search['startTime'] : '';
            $endTime = isset($search['endTime']) ? $search['endTime'] : '';
            if($name){
                $where['recommend_id'] = $name;
            }
            if(!empty($startTime) && empty($endTime))
            {
                $andWhere = ['>','create_time',$startTime. ' 00:00:00'];
            } elseif(empty($startTime) && !empty($endTime)) {
                $andWhere = ['<','create_time',$endTime. ' 23:59:59'];
            } elseif (!empty($startTime) && !empty($endTime)) {
                $andWhere = ['between','create_time',$startTime. ' 00:00:00', $endTime. ' 23:59:59'];
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
        $memberList = Member::getFormArray('','id','name');
        foreach($list['data'] as $key => $value)
        {
            $list['data'][$key]['create_time'] = date('Y-m-d',strtotime($value['create_time'])).'</br>'.date('H:i:s',strtotime($value['create_time']));
            $list['data'][$key]['rebate_money'] = $value['rebate_money'] ? floatval($value['rebate_money']) : '--';
            $list['data'][$key]['target_id'] = isset($memberList[$value['target_id']]) ? $memberList[$value['target_id']] : '--';
            $list['data'][$key]['recommend_id'] = isset($memberList[$value['recommend_id']]) ? $memberList[$value['recommend_id']] : '--';
        }
        return $list;
    }

}
