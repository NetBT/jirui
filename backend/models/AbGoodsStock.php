<?php
namespace backend\models;

use common\models\Status;
use Yii;
use yii\base\Exception;
use common\models\Functions;

/**
 * 出入库记录
 * Class AB
 * @package backend\models
 */
class AbGoodsStock extends Common
{
    private $fieldArray = [
        "id",
        "business_id",
        "head_goods_id",
        "goods_id",
        "goods_name",
        "goods_color",
        "goods_size",
        "goods_texture",
        "goods_style",
        "goods_real_price",
        "operate_num",
        "total_money",
        "operate_type",
        "operate_user",
        "create_time",
    ];

    public static function tableName()
    {
        return '{{%ab_goods_stock_log}}';
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
            $type = $search['type'];
            $startTime = isset($search['startTime']) ? $search['startTime'] : '';
            $endTime = isset($search['endTime']) ? $search['endTime'] : '';

            if($type)
            {
                $where['operate_type'] = $type;
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
        $employeeList = Employee::getFormArray('','id','employee_name');
        foreach($list['data'] as $key => $value)
        {
            $list['data'][$key]['create_time'] = date('Y-m-d',strtotime($value['create_time'])).'</br>'.date('H:i:s',strtotime($value['create_time']));
            $list['data'][$key]['operate_user_name'] = $employeeList[$value['operate_user']];
            $list['data'][$key]['operate_type'] = Status::goodsStockTypeMap()[$value['operate_type']];
        }
        return $list;
    }



    public static function recordStockLog($goodsId = null, $goodsNum = 0, $type = null) {
        try {
            $goodsId = intval($goodsId);
            $goodsNum = intval($goodsNum);
            $type = intval($type);
            if (empty($goodsId) || empty($type) || $goodsNum == 0) {
                throw new Exception('信息错误');
            }
            //查询货品信息
            $info = AbGoods::getOneByWhere(['id' => $goodsId]);
            if (intval($info['head_goods_id']) > 0) {
                $goodsInfo = Goods::getOneByWhere(['id' => $info['head_goods_id']]);
                $data['goods_real_price'] = Goods::getGoodsCurrPriceByInfo($goodsInfo);
            } else {
                $goodsInfo = AbGoods::getOneByWhere(['id' => $goodsId]);
                $data['goods_real_price'] = AbGoods::getGoodsCurrPriceByInfo($goodsInfo);
            }
            $data['business_id'] = Combo::getBusinessId();
            $data['goods_id'] = $goodsId;
            $data['head_goods_id'] = $info['head_goods_id'];
            $data['goods_name'] = $goodsInfo['goods_name'];
            $data['goods_color'] = $goodsInfo['goods_color'];
            $data['goods_size'] = $goodsInfo['goods_size'];;
            $data['goods_texture'] = $goodsInfo['goods_texture'];
            $data['goods_style'] = $goodsInfo['goods_style'];
            $data['goods_real_price'] = AbGoods::getGoodsCurrPriceByInfo($goodsInfo);
            $data['operate_num'] = $goodsNum;
            $data['total_money'] = $data['goods_real_price'] * $goodsNum;
            $data['operate_type'] = $type;
            $data['operate_user'] = Yii::$app->user->getId();
            $data['create_time'] = date('Y-m-d H:i:s');
            $res = static::insertData($data);
            if ($res === false) {
                throw new Exception('出入库记录失败');
            }
            return true;
        } catch (Exception $e) {
            return ['status' => false, 'msg' => $e->getMessage()];
        }
    }
}
