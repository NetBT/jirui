<?php

namespace backend\models;

use common\models\Status;
use Yii;
use yii\base\Exception;
use common\models\Functions;

/**
 * 功能的简述：Class MemberOrderDetail
 * 创建作者：
 * 创建时间：
 * @property MemberOrderGoodsImages[]|null $images
 * 修改日期         修改者             BUG小功能修改申请单号
 * 注意：
 */
class MemberOrderDetail extends Common
{
    const CATEGORY_THUMBS = 1;//相册
    const CATEGORY_FRAME = 2;//相框
    const CATEGORY_ORNAMENT = 3;//摆台

    static $category_name = [
        self::CATEGORY_THUMBS => 'THUMBS',
        self::CATEGORY_FRAME => 'FRAME',
        self::CATEGORY_ORNAMENT => 'ORNAMENT',
    ];

    private $fieldArray = [
        "id",           //主键
        "combo_order_number",//订单套系编号
        "goods_code",       //商品编号
        "goods_name",       //商品名称
        "goods_category",   //商品分类
        "goods_price",      //商品价格
        "goods_cost",       //商品成本价
        "goods_discount",   //商品折扣价
        "goods_color",      //商品颜色
        "goods_size",       //商品尺寸
        "goods_texture",    //商品材质
        "goods_style",      //商品风格
        "goods_type",       //商品类型
        "deal_status",      //理件状态
        "create_time",
    ];

    public $combo_id;

    public static function tableName()
    {
        return '{{%ab_member_order_detail}}';
    }

    public $business_name;

    /**
     * 获取字段
     * @return array
     */
    private function _getFields()
    {
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
        $searchWhere = $this->getSearch(Yii::$app->request->post('extra_search'));//自定义搜索条件

        //得到文章的总数（但是还没有从数据库取数据）
        if (isset($searchWhere['andWhere'])) {
            $count = self::getCountByAndWhere($searchWhere['where'], $searchWhere['andWhere']);
        } else {
            $count = self::getCountByWhere($searchWhere);
        }
        $returnData["recordsTotal"] = $returnData['recordsFiltered'] = intval($count);

        //设置分页
        $this->setPagination();

        $selectField = "";
        $fields = $this->_getFields();
        foreach ($fields as $key => $value) {
            $selectField .= "," . $value;
        }
        $selectField = ltrim($selectField, ',');
        //排序 order
        $orderSql = 'id ASC';
        if (isset($searchWhere['andWhere'])) {
            $returnData['data'] = static::getByAndWhere($searchWhere['where'], $searchWhere['andWhere'], $selectField,
                $orderSql, $this->_Pagination['offset'], $this->_Pagination['limit']);
        } else {
            $returnData['data'] = static::getByWhere($searchWhere, $selectField, $orderSql,
                $this->_Pagination['offset'], $this->_Pagination['limit']);
        }
        return $returnData;
    }


    /**
     * 自定义参数的搜索  搜索,搜索也分每一列
     * 这里要根据业务逻辑进行修改
     * @param $search
     * @return string
     */
    public function getSearch($search = [])
    {
        $where = [];
        $andWhere = [];
        if (!empty($search)) {
            $comboOrderNumber = isset($search['orderComboNumber']) ? $search['orderComboNumber'] : '';
            if ($comboOrderNumber) {
                $where['combo_order_number'] = $comboOrderNumber;
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
        $goodsCategoryInfo = GoodsCategory::getFormArray([], 'id', 'category_name');
        foreach ($list['data'] as $key => $value) {
            $list['data'][$key]['create_time'] = date('Y-m-d',
                    strtotime($value['create_time'])) . '</br>' . date('H:i:s', strtotime($value['create_time']));
            $list['data'][$key]['goods_type'] = Status::getABGoodsTypeMap()[$value['goods_type']];
            $list['data'][$key]['goods_category'] = $goodsCategoryInfo[$value['goods_category']];
            $list['data'][$key]['deal_status'] = Status::memberOrderDetailDealStatusMap()[$value['deal_status']];
        }
        return $list;
    }

    /**
     * 获取订单编号
     * @return string
     */
    public static function getMemberOrderComboNum()
    {
        $num = date('YmdHis') . 'MOC' . Common::getBusinessId();
        if (static::getOneByWhere(['combo_order_number' => $num])) {
            self::getMemberOrderComboNum();
        }
        return $num;
    }

    /**
     * 更新order——detail表的内容
     * @return array
     */
    public function changeDetailOrderStatus()
    {
        $post = Yii::$app->request->post();

        $orderDetailId = trim($post['id']);
        $type = intval($post['type']);
        $afterStatus = intval($post['afterStatus']);
        $beforeStatus = isset($post['beforeStatus']) ? intval($post['beforeStatus']) : 0;

        $orderDetailInfo = self::getOneInfoById($orderDetailId);

        $employeeInfo = Employee::getFormArray([
            'status' => Status::EMPLOYEE_STATUS_ACTIVE,
            'alliance_business_id' => Common::getBusinessId()
        ], 'id', 'employee_name');
        $trans = Yii::$app->db->beginTransaction();
        try {
            if (!$type) {
                throw new Exception('未指定类型');
            }

            if (!$orderDetailId) {
                throw new Exception('未指定商品');
            }

            $orderComboInfo = MemberOrderCombo::getOneByWhere(['combo_order_number' => $orderDetailInfo['combo_order_number']]);
            $dealUser = $orderComboInfo['deal_user'];
            $dealUserName = !empty($dealUser) ? $employeeInfo[$dealUser] : '';
            //判断是否是同一个员工操作
            if (!empty($dealUser) && ($dealUser != Yii::$app->user->getId())) {
                throw new Exception('该订单只由' . $dealUserName . '操作');
            }

            $where['id'] = $orderDetailId;
            switch ($type) {
                case Status::MEMBER_ORDER_DETAIL_DEAL_STATUS_FC://返厂处理
                    //1.判断order_combo里面的商品是否全部理件
                    $data['deal_status'] = $afterStatus;
                    $data['update_user'] = Yii::$app->user->getId();
                    $res = static::updateDataWithLog($data, $where);
                    if ($res === false) {
                        throw new Exception('操作失败');
                    }

                    break;
                case Status::MEMBER_ORDER_DETAIL_DEAL_STATUS_WC://完成理件
                    //1.商品表减去对应的数量
                    //2.判断order_combo里面的商品是否全部理件
                    $data['deal_status'] = $afterStatus;
                    $data['update_user'] = Yii::$app->user->getId();
                    $res = static::updateDataWithLog($data, $where);
                    if ($res === false) {
                        throw new Exception('操作失败');
                    }

                    $ABGoodsModel = new AbGoods();
                    $goodsInfo = AbGoods::getOneByWhere(['goods_code' => $orderDetailInfo['goods_code']]);
                    $afterGoodsNum = intval($goodsInfo['goods_num']) - intval($orderDetailInfo['goods_num']);
                    if ($afterGoodsNum < 0) {
                        throw new Exception('该商品库存不足');
                    }

                    $res = $ABGoodsModel->updateDataWithLog(['goods_num' => $afterGoodsNum],
                        ['goods_code' => $orderDetailInfo['goods_code']]);
                    if ($res === false) {
                        throw new Exception('库存更新失败');
                    }

                    //对应的出库操作
                    //添加ab_goods_stock_log
                    $insertData['business_id'] = Common::getBusinessId();
                    $insertData['head_goods_id'] = !empty($goodsInfo['head_goods_id']) ? $goodsInfo['head_goods_id'] : '';
                    //此商品ID是ab_goods中的Id
                    $insertData['goods_id'] = $goodsInfo['id'];
                    $insertData['goods_name'] = $orderDetailInfo['goods_name'];
                    $insertData['goods_color'] = $orderDetailInfo['goods_color'];
                    $insertData['goods_size'] = $orderDetailInfo['goods_size'];
                    $insertData['goods_texture'] = $orderDetailInfo['goods_texture'];
                    $insertData['goods_style'] = $orderDetailInfo['goods_style'];
                    $insertData['goods_real_price'] = $orderDetailInfo['goods_price'];
                    $insertData['operate_num'] = $orderDetailInfo['goods_num'];
                    $insertData['total_money'] = $orderDetailInfo['goods_num'] * $orderDetailInfo['goods_price'];
                    $insertData['operate_user'] = Yii::$app->user->getId();
                    $insertData['create_time'] = date('Y-m-d H:i:s');
                    $insertData['operate_type'] = Status::GOODS_STOCK_TYPE_EXPORT;//出库库操作
                    $abGoodsStockModel = new AbGoodsStock();
                    $resInsertStock = $abGoodsStockModel->insertDataWithLog($insertData);
                    if ($resInsertStock === false) {
                        throw new Exception('出库记录失败');
                    }
                    break;
            }

            //总体判断order_combo是否需要更改状态
            $dealStatus = self::getByWhere(['combo_order_number' => $orderDetailInfo['combo_order_number']],
                'deal_status');
            $dealStatusArray = [];
            foreach ($dealStatus as $key => $value) {
                array_push($dealStatusArray, $value['deal_status']);
            }
            $dealStatusArray = array_unique($dealStatusArray);
            $count = count($dealStatusArray);
            $comboData['deal_user'] = Yii::$app->user->getId();
            $comboData['deal_status'] = Status::MEMBER_ORDER_DEAL_STATUS_ING;
            $comboData['update_time'] = date('Y-m-d H:i:s');
            if (($count == 1) && ($dealStatusArray[0] == Status::MEMBER_ORDER_DETAIL_DEAL_STATUS_WC)) {
                $comboData['deal_status'] = Status::MEMBER_ORDER_DEAL_STATUS_YES;
            }
            $orderComboModel = new MemberOrderCombo();
            $res = $orderComboModel->updateDataWithLog($comboData,
                ['combo_order_number' => $orderDetailInfo['combo_order_number']]);
            if ($res === false) {
                throw new Exception('操作失败');
            }
            $trans->commit();
            return Functions::formatJson(1000, '操作成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(1001, $e->getMessage());
        }
    }

    /**
     * 方法描述：
     * @param $combo_order_number
     * @return MemberOrderGoodsImages
     * 注意：
     */
    public function getComboGoodsFirstImage($combo_order_number)
    {
        return MemberOrderGoodsImages::findOne([
            'combo_order_number' => $combo_order_number,
            'goods_code' => $this->goods_code
        ]);
    }

    public function getImages()
    {
        return $this->hasMany(MemberOrderGoodsImages::class, ['goods_code' => 'goods_code'])->with('image');
    }

    public function getCategoryEnName()
    {
        return isset(self::$category_name[$this->goods_category]) ? self::$category_name[$this->goods_category] : 'UNKNOWN';
    }
}
