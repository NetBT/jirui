<?php
namespace backend\models;

use common\models\Status;
use Yii;
use yii\base\Exception;
use common\models\Functions;

class MemberOrderRefund extends Common
{
    private $fieldArray = [
        "id",               //主键
        "member_id",        //会员ID
        "business_id",      //商户ID
        "order_number",     //订单编号
        "order_id",         //订单号
        "refund_type",      //退款方式
        "refund_money",     //退款金额
        "refund_integral",  //退还积分
        "refund_reason",    //退款原因
        "create_time",      //退款时间
    ];

    public static function tableName()
    {
        return '{{%ab_member_order_refund_log}}';
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
        $orderSql = $this->_Pagination['order'] ? $this->_Pagination['order'] : 'id ASC';
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
            $memberName = (isset($search['memberName']) && !empty($search['memberName'])) ? $search['memberName'] : '';
            $orderNumber = (isset($search['orderNumber']) && !empty($search['orderNumber'])) ? $search['orderNumber'] : '';

            $startTime = $search['startTime'];
            $endTime = $search['endTime'];
            if($memberName)
            {
                $memberIdInfo = Member::getFormArray(['name' => $memberName],'id','id');
                $where['member_id'] = $memberIdInfo;
            }
            if($orderNumber)
            {
                $where['order_number'] = $orderNumber;
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
        $orderInfo = MemberOrder::getFormArray(['business_id' => Common::getBusinessId()],'id','combo_id');
        $memberInfo = Member::getFormArray(['business_id' => Common::getBusinessId()],'id','name');
        $comboInfo = Combo::getFormArray(['business_id' => Common::getBusinessId()],'id','combo_name');
        $abInfo = AB::getFormArray('','id','AB_name');
        foreach($list['data'] as $key => $value)
        {
            $list['data'][$key]['create_time'] = date('Y-m-d',strtotime($value['create_time'])).'</br>'.date('H:i:s',strtotime($value['create_time']));
            $list['data'][$key]['member_id'] = isset($memberInfo[$value['member_id']]) ? $memberInfo[$value['member_id']] : '--';
            $list['data'][$key]['business_id'] = $value['business_id'] ? $abInfo[$value['business_id']] : '';
            $list['data'][$key]['combo_name'] = $value['order_id'] ? $comboInfo[$orderInfo[$value['order_id']]] : '';
            $list['data'][$key]['refund_type'] = (isset($value['refund_type']) && !empty($value['refund_type'])) ? Status::memberOrderRefundTypeMap()[$value['refund_type']] : '';

            //去除多余的0 floatval
            $list['data'][$key]['refund_money'] = (isset($value['refund_money']) && !empty($value['refund_money'])) ? floatval($value['refund_money']) : '--';

            $list['data'][$key]['short_reason'] = (isset($value['refund_reason']) && !empty($value['refund_reason'])) ? Functions::chinese_str_cut(rtrim($value['refund_reason'],','),20) : '--';
        }
        return $list;
    }
}
