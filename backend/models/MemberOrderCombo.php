<?php

namespace backend\models;

use common\models\Status;
use Yii;
use yii\base\Exception;
use common\models\Functions;

/**
 * 功能的简述：Class MemberOrderCombo
 * 创建作者：
 * 创建时间：
 * @property Combo $combo
 * @property AbGoods[]|array $abGoods
 * @property MemberOrderImage[]|array $orderImages
 * @property MemberOrderDetail]|array $orderDetails
 * @property MemberOrder $memberOrder
 * 修改日期         修改者             BUG小功能修改申请单号
 * 注意：
 */
class MemberOrderCombo extends Common
{

    private $fieldArray = [
        "id",                   //主键
        "order_number",         //总订单编号
        "combo_order_number",   //套系订单编号
        "combo_name",           //套系名称
        "combo_id",             //套系ID
        "price",                //价格
        "discount",             //折扣
        "integral",             //积分
        "create_time",          //创建时间
        "mark",                 //备注
        "plan_status",          //排项状态
        "shoot_status",         //拍摄状态
        "shoot_finish_time",    //拍摄完成时间
        "select_status",        //选片状态
        "select_time",          //选片时间
        "select_photos_user",   //选片师
        "composite_status",     //后期状态
        "composite_user",       //后期师
        "deal_status",          //理件状态
        "shoot_user",          //摄影师
        "deal_user",            //理件师
        "take_park_status",     //取件状态
        "take_park_time",       //取件时间
    ];

    public static function tableName()
    {
        return '{{%ab_member_order_combo}}';
    }

    public $business_name;
    public $goods_images = [];

    /**
     * 获取字段
     * @return array
     */
    private function _getFields()
    {
        return $this->fieldArray;
    }

    public function getListData($type = '')
    {
        $returnData = [
            "draw" => intval(Yii::$app->request->post('draw')),
            "recordsTotal" => 0,
            "recordsFiltered" => 0,
            "data" => null
        ];
        //搜索条件
        $searchWhere = $this->getSearch(Yii::$app->request->post('extra_search'), $type);//自定义搜索条件

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
        $orderSql = $this->_Pagination['order'] ? $this->_Pagination['order'] : 'id ASC';

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
     * @param array $search
     * @param string $type
     * @return array
     */
    public function getSearch($search = [], $type = '')
    {
        $where = [];
        $andWhere = [];
        if ($type) {
            switch ($type) {
                case Status::MEMBER_ORDER_COMBO_NOT_SHOOT://未拍摄
                    $where['shoot_status'] = [
                        Status::MEMBER_ORDER_SHOOT_STATUS_NO,
                        Status::MEMBER_ORDER_SHOOT_STATUS_ING
                    ];
                    break;
                case Status::MEMBER_ORDER_COMBO_NOT_SHOOT_FINISHED:
                    $where['shoot_status'] = Status::MEMBER_ORDER_SHOOT_STATUS_NOT_FINISH;
                    break;
                case Status::MEMBER_ORDER_COMBO_NOT_SELECT://未选片
                    $where['shoot_status'] = Status::MEMBER_ORDER_SHOOT_STATUS_YES;
                    $where['select_status'] = [
                        Status::MEMBER_ORDER_SELECT_STATUS_NO,
                        Status::MEMBER_ORDER_SELECT_STATUS_ING
                    ];
                    break;
                case Status::MEMBER_ORDER_COMBO_NOT_COMPOSITE://未后期
                    $where['select_status'] = Status::MEMBER_ORDER_SELECT_STATUS_YES;
                    $where['composite_status'] = [
                        Status::MEMBER_ORDER_COMPOSITE_STATUS_WCL,
                        Status::MEMBER_ORDER_COMPOSITE_STATUS_JX,
                        Status::MEMBER_ORDER_COMPOSITE_STATUS_SJ,
                        Status::MEMBER_ORDER_COMPOSITE_STATUS_YFCJ,
                    ];
                    break;
                case Status::MEMBER_ORDER_COMBO_NOT_DEAL:
                    $where['composite_status'] = Status::MEMBER_ORDER_COMPOSITE_STATUS_DONE;
                    break;
            }

        }
        if (!empty($search)) {
            $orderNumber = isset($search['orderNumber']) ? $search['orderNumber'] : '';
            $orderComboNumber = isset($search['orderComboNumber']) ? $search['orderComboNumber'] : '';
            $memberName = isset($search['memberName']) ? $search['memberName'] : '';
            if ($orderNumber) {
                //总订单的订单编码
                $where['order_number'] = $orderNumber;
            }
            if ($orderComboNumber) {
                //order_combo的订单编码
                $where['combo_order_number'] = $orderComboNumber;
            }

            if ($memberName) {
                $memberIdArray = [];
                $memberId = Member::getByWhere(['name' => $memberName]);
                foreach ($memberId as $key => $value) {
                    array_push($memberIdArray, $value['id']);
                }
                $where['member_id'] = $memberIdArray;
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
        $memberInfo = Member::getFormArray(['business_id' => Common::getBusinessId()], 'id', 'name');
        $employeeInfo = Employee::getFormArray(['alliance_business_id' => Common::getBusinessId()], 'id',
            'employee_name');
        $orderInfo = MemberOrder::getFormArray(['business_id' => Common::getBusinessId()], 'order_number', 'member_id');
        foreach ($list['data'] as $key => $value) {
            $list['data'][$key]['member_name'] = $memberInfo[$orderInfo[$value['order_number']]];
            $list['data'][$key]['plan_status'] = Status::memberOrderComboPlanStatusMap()[$value['plan_status']];
            $list['data'][$key]['shoot_status'] = Status::memberOrderComboShootStatusMap()[$value['shoot_status']];
            $list['data'][$key]['select_status'] = Status::memberOrderComboSelectStatusMap()[$value['select_status']];
            $list['data'][$key]['composite_status'] = Status::memberOrderComboCompositeStatusMap()[$value['composite_status']];
            $list['data'][$key]['deal_status'] = Status::memberOrderComboDealStatusMap()[$value['deal_status']];
            $list['data'][$key]['take_park_status'] = Status::memberOrderComboTakeParkStatusMap()[$value['take_park_status']];
            $list['data'][$key]['create_time'] = date('Y-m-d',
                    strtotime($value['create_time'])) . '</br>' . date('H:i:s', strtotime($value['create_time']));
            $list['data'][$key]['shoot_finish_time'] = $value['shoot_finish_time'] ? date('Y-m-d',
                    strtotime($value['shoot_finish_time'])) . '</br>' . date('H:i:s',
                    strtotime($value['shoot_finish_time'])) : '--';
            $list['data'][$key]['select_time'] = date('Y-m-d',
                    strtotime($value['select_time'])) . '</br>' . date('H:i:s', strtotime($value['select_time']));
            $list['data'][$key]['take_park_time'] = date('Y-m-d',
                    strtotime($value['take_park_time'])) . '</br>' . date('H:i:s', strtotime($value['take_park_time']));


            $list['data'][$key]['shoot_user'] = $value['shoot_user'] ? $employeeInfo[$value['shoot_user']] : '';
            $list['data'][$key]['select_photos_user'] = $value['select_photos_user'] ? $employeeInfo[$value['select_photos_user']] : '';
            $list['data'][$key]['composite_user'] = $value['composite_user'] ? $employeeInfo[$value['composite_user']] : '';
            $list['data'][$key]['deal_user'] = $value['deal_user'] ? $employeeInfo[$value['deal_user']] : '';

        }
        return $list;
    }

    /**
     * 获取订单编号
     * @return string
     */
    public function getMemberOrderComboNum()
    {
        $num = '';
        do {
            $num = date('YmdHi') . rand(100, 999) . 'MOC' . Common::getBusinessId();
            $res = static::getOneByWhere(['combo_order_number' => $num]);
        } while (!empty($res));
        return $num;
    }

    public function getInfo($id = 0)
    {
        //获取会员信息
        $memberOrderInfo = self::getOneInfoById($id, 'order_number,member_id,price,number,id,member_id');

        //获取订单信息
        $memberInfo = Member::getOneInfoById($memberOrderInfo['member_id'], 'name,integral,valid_money');
        $info = [
            'order' => $memberOrderInfo,
            'member' => $memberInfo,
        ];
        return $info;
    }

    /**
     * 订单退款
     * @return array
     */
    public function refund()
    {
        $post = Yii::$app->request->post();
        $memberId = $post['memberId'];
        $orderId = $post['orderId'];
        $orderData = $memberData = [];
        $trans = Yii::$app->db->beginTransaction();
        try {
            if (!$memberId) {
                throw new Exception('未找到用户');
            }

            if (!$orderId) {
                throw new Exception('未找到订单');
            }
            $memberOrderInfo = self::getOneInfoById($orderId, 'price');
            if ($post['refund_money'] > $memberOrderInfo['price']) {
                throw new Exception('退款金额不能大于订单金额');
            }

            $type = $post['type'];
            $orderData['update_time'] = date('Y-m-d H:i:s');
            $orderData['refund_money'] = $post['refund_money'];
            $orderData['refund_type'] = $type;
            $orderData['refund_time'] = date('Y-m-d H:i:s');

            $memberInfo = Member::getOneInfoById($memberId, 'integral,valid_money,total_consume');
            $memberData['integral'] = $memberInfo['integral'] - $post['refund_integral'];
            $memberData['total_consume'] = $memberInfo['total_consume'] - $post['refund_money'];
            if ($type == Status::MEMBER_ORDER_REFUND_TYPE_VALID_MONEY) {
                $memberData['valid_money'] = $memberInfo['valid_money'] + $post['refund_money'];
            }
            $res = static::updateDataWithLog($orderData, ['id' => $orderId]);
            if ($res === false) {
                throw new Exception('订单退款失败');
            }
            $memberModel = new Member();
            $res = $memberModel->updateDataWithLog($memberData, ['id' => $memberId]);
            if ($res === false) {
                throw new Exception('会员退款失败');
            }

            $trans->commit();
            return Functions::formatJson(1000, '操作成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(1001, $e->getMessage());
        }
    }

    /**
     * 二销售款
     * @return array
     */
    public function secondGathering()
    {
        $post = Yii::$app->request->post();
        $memberId = $post['memberId'];
        $orderId = $post['orderId'];
        $orderSecondData = $memberData = [];
        $trans = Yii::$app->db->beginTransaction();
        try {
            if (!$memberId) {
                throw new Exception('未找到用户');
            }

            if (!$orderId) {
                throw new Exception('未找到订单');
            }

            //用户表更新总消费
            $memberInfo = Member::getOneInfoById($memberId, 'total_consume');
            $memberData['total_consume'] = $memberInfo['total_consume'] + $post['second_money'];
            $memberData['update_time'] = date('Y-m-d H:i:s');
            $memberModel = new Member();
            $res = $memberModel->updateDataWithLog($memberData, ['id' => $memberId]);
            if ($res === false) {
                throw new Exception('收款失败');
            }

            //订单second表
            $orderSecondData['business_id'] = Common::getBusinessId();
            $orderSecondData['order_id'] = $orderId;
            $orderSecondData['create_time'] = date('Y-m-d H:i:s');
            $orderSecondData['second_gathering_fund'] = $post['gathering_fund'];
            $orderSecondData['second_gathering_money'] = $post['second_money'];
            $orderSecondData['second_pay_type'] = $post['pay_type'];;
            $orderSecondData['mark'] = $post['mark'];;

            $memberOrderSecondModel = new MemberOrderSecond();
            $res = $memberOrderSecondModel->insertDataWithLog($orderSecondData);
            if ($res === false) {
                throw new Exception('收款失败');
            }
            $trans->commit();
            return Functions::formatJson(1000, '操作成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(1001, $e->getMessage());
        }
    }


    /**
     * 更改order_combo 各种状态
     * @return array
     */
    public function changeComboOrderStatus()
    {
        $post = Yii::$app->request->post();

        $comboOrderNumber = trim($post['comboOrderNumber']);
        $type = intval($post['type']);
        $afterStatus = intval($post['afterStatus']);
        $beforeStatus = intval($post['beforeStatus']);
        $employeeInfo = Employee::getFormArray([
            'status' => Status::EMPLOYEE_STATUS_ACTIVE,
            'alliance_business_id' => Common::getBusinessId()
        ], 'id', 'employee_name');
        try {
            if (!$type) {
                throw new Exception('未指定类型');
            }

            if (!$comboOrderNumber) {
                throw new Exception('未指定订单');
            }

            $where['combo_order_number'] = $comboOrderNumber;

            $currentInfo = self::getOneByWhere($where);
            $shootUser = $currentInfo['shoot_user'];
            $shootUserName = !empty($shootUser) ? $employeeInfo[$shootUser] : '';
            $selectPhoneUser = $currentInfo['select_photos_user'];
            $selectPhoneUserName = !empty($selectPhoneUser) ? $employeeInfo[$selectPhoneUser] : '';
            $compositeUser = $currentInfo['composite_user'];
            $compositeUserName = !empty($compositeUser) ? $employeeInfo[$compositeUser] : '';
            switch ($type) {
                case Status::MEMBER_ORDER_COMBO_NOT_SHOOT://拍摄
                    //未排项的不能进行拍摄
                    if ($currentInfo['plan_status'] == Status::MEMBER_ORDER_PLAN_STATUS_NO) {
                        throw new Exception('该订单未排项，请先排项在进行拍摄');
                    }

                    //判断是否是同一个员工操作
                    if (!empty($shootUser) && ($shootUser != Yii::$app->user->getId())) {
                        throw new Exception('该订单只由' . $shootUserName . '操作');
                    }
                    $data['shoot_status'] = $afterStatus;
                    if ($afterStatus == Status::MEMBER_ORDER_SHOOT_STATUS_YES) {
                        $data['shoot_finish_time'] = date('Y-m-d H:i:s');
                    }
                    $data['shoot_user'] = Yii::$app->user->getId();
                    $res = static::updateDataWithLog($data, $where);
                    if ($res === false) {
                        throw new Exception('操作失败');
                    }
                    return Functions::formatJson(1000, '操作成功');
                    break;
                //选片
                case Status::MEMBER_ORDER_COMBO_NOT_SELECT:
                    //判断是否是同一个员工操作
                    if (!empty($selectPhoneUser) && ($selectPhoneUser != Yii::$app->user->getId())) {
                        throw new Exception('该订单只由' . $selectPhoneUserName . '操作');
                    }
                    $data['select_status'] = $afterStatus;
                    if ($afterStatus == Status::MEMBER_ORDER_SHOOT_STATUS_YES) {
                        $data['select_time'] = date('Y-m-d H:i:s');
                    }
                    $data['select_photos_user'] = Yii::$app->user->getId();
                    $res = static::updateDataWithLog($data, $where);
                    if ($res === false) {
                        throw new Exception('操作失败');
                    }
                    return Functions::formatJson(1000, '操作成功');
                    break;
                //后期处理
                case Status::MEMBER_ORDER_COMBO_NOT_COMPOSITE:
                    //判断是否是同一个员工操作
                    if (!empty($compositeUser) && ($compositeUser != Yii::$app->user->getId())) {
                        throw new Exception('该订单只由' . $compositeUserName . '操作');
                    }
                    $data['composite_status'] = $afterStatus;
                    $data['composite_user'] = Yii::$app->user->getId();
                    $res = static::updateDataWithLog($data, $where);
                    if ($res === false) {
                        throw new Exception('操作失败');
                    }
                    return Functions::formatJson(1000, '操作成功');
                    break;
                //通知取件
                case Status::MEMBER_ORDER_COMBO_NOT_TAKE_PARK:
                    $data['take_park_status'] = $afterStatus;
                    if ($afterStatus == Status::MEMBER_ORDER_TAKE_PARK_STATUS_YES) {
                        $data['take_park_time'] = date('Y-m-d H:i:s');
                    }
                    $res = static::updateDataWithLog($data, $where);
                    if ($res === false) {
                        throw new Exception('操作失败');
                    }
                    break;
            }
            return Functions::formatJson(1000, '操作成功');
        } catch (Exception $e) {
            return Functions::formatJson(1001, $e->getMessage());
        }
    }

    public function quoteOrderInfo()
    {
        try {
            $orderNumber = Yii::$app->request->post('orderNumber');
            if (empty($orderNumber)) {
                throw new Exception('订单信息错误');
            }

            $where['combo_order_number'] = $orderNumber;
            $where['business_id'] = static::getBusinessId();
            $orderInfo = MemberOrder::getOneByWhere(['order_number' => $orderNumber]);
            if (empty($orderInfo)) {
                throw new Exception('订单信息不存在');
            }
            //获取套系信息
            $comboInfo = Combo::getOneByWhere(['id' => $orderInfo['combo_id']]);
            $comboTypeMap = Status::comboTypeMap();
            foreach ($comboTypeMap as $k => $v) {
                $comboInfo[$k]['combo_type_name'] = $comboTypeMap[$v['combo_type']];
            }
            //获取会员信息
            $memberInfo = Member::getOneByWhere(['id' => $orderInfo['member_id']]);
        } catch (Exception $e) {

        }
    }

    public function doReplan($orderNumber = '')
    {
        $trans = Yii::$app->db->beginTransaction();
        try {
            if (empty($orderNumber)) {
                throw new Exception('订单号不能为空');
            }
            $where['combo_order_number'] = $orderNumber;
            $where['business_id'] = static::getBusinessId();
            $orderInfo = static::getOneByWhere($where);

            if (empty($orderInfo)) {
                throw new Exception('订单信息不存在');
            }

            $data['shoot_status'] = Status::MEMBER_ORDER_SHOOT_STATUS_NOT_FINISH;
            $data['update_time'] = date('Y-m-d H:i:s');
            $res = static::updateData($data, ['combo_order_number' => $orderNumber]);
            if ($res === false) {
                throw new Exception('系统错误,请联系技术支持');
            }
            $trans->commit();
            return Functions::formatJson(1000, '标记成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(2000, $e->getMessage());
        }
    }

    /**
     * 获取套系信息订单
     * @param array $where
     * @return array
     */
    public function getComboOrderByWhere($where = [])
    {
        $comboOrderInfo = self::getByWhere($where);
        if (!isset($comboOrderInfo) && empty($comboOrderInfo)) {
            return Functions::formatJson(2001, '未获取到信息');
        }

        $memberInfo = Member::getFormArray(['business_id' => Common::getBusinessId()], 'id', 'name');
        $employeeInfo = Employee::getFormArray(['alliance_business_id' => Common::getBusinessId()], 'id',
            'employee_name');
        $orderInfo = MemberOrder::getFormArray(['business_id' => Common::getBusinessId()], 'order_number', 'member_id');

        foreach ($comboOrderInfo as $key => $value) {
            $wh['combo_name'] = $value['combo_name'];
            $combo = Combo::getForm($wh);
            $comboOrderInfo[$key]['register_count'] = $combo[0]['register_count'];
            $comboOrderInfo[$key]['combo_clothing'] = $combo[0]['combo_clothing'];
            $comboOrderInfo[$key]['member_name'] = $memberInfo[$orderInfo[$value['order_number']]];
            $comboOrderInfo[$key]['plan_status'] = Status::memberOrderComboPlanStatusMap()[$value['plan_status']];
            $comboOrderInfo[$key]['shoot_status'] = Status::memberOrderComboShootStatusMap()[$value['shoot_status']];
            $comboOrderInfo[$key]['select_status'] = Status::memberOrderComboSelectStatusMap()[$value['select_status']];
            $comboOrderInfo[$key]['composite_status'] = Status::memberOrderComboCompositeStatusMap()[$value['composite_status']];
            $comboOrderInfo[$key]['deal_status'] = Status::memberOrderComboDealStatusMap()[$value['deal_status']];
            $comboOrderInfo[$key]['take_park_status'] = Status::memberOrderComboTakeParkStatusMap()[$value['take_park_status']];
            $comboOrderInfo[$key]['create_time'] = date('Y-m-d',
                    strtotime($value['create_time'])) . '</br>' . date('H:i:s', strtotime($value['create_time']));
            $comboOrderInfo[$key]['shoot_finish_time'] = $value['shoot_finish_time'] ? date('Y-m-d',
                    strtotime($value['shoot_finish_time'])) . '</br>' . date('H:i:s',
                    strtotime($value['shoot_finish_time'])) : '--';
            $comboOrderInfo[$key]['select_time'] = date('Y-m-d',
                    strtotime($value['select_time'])) . '</br>' . date('H:i:s', strtotime($value['select_time']));
            $comboOrderInfo[$key]['take_park_time'] = date('Y-m-d',
                    strtotime($value['take_park_time'])) . '</br>' . date('H:i:s', strtotime($value['take_park_time']));
            $comboOrderInfo[$key]['shoot_user'] = $value['shoot_user'] ? $employeeInfo[$value['shoot_user']] : '';
            $comboOrderInfo[$key]['select_photos_user'] = $value['select_photos_user'] ? $employeeInfo[$value['select_photos_user']] : '';
            $comboOrderInfo[$key]['composite_user'] = $value['composite_user'] ? $employeeInfo[$value['composite_user']] : '';
            $comboOrderInfo[$key]['deal_user'] = $value['deal_user'] ? $employeeInfo[$value['deal_user']] : '';
        }
        return Functions::formatJson(1000, '', $comboOrderInfo);
    }

    public function showOrderComboInfo($where = [])
    {
        //获取订单信息
        $memberOrderComboInfo = self::getOneByWhere($where);

        $orderList = MemberOrder::getFormArray(['business_id' => Common::getBusinessId()], 'order_number', 'member_id');
        $orderInfo = MemberOrder::getOneByWhere(['order_number' => $memberOrderComboInfo['order_number']]);
        //获取会员信息
        $model = new Member();
        $memberWhere['id'] = $orderList[$memberOrderComboInfo['order_number']];
        $memberInfo = $model->getMemberInfoByWhere($memberWhere);
        $info = [
            'orderCombo' => $memberOrderComboInfo,
            'member' => $memberInfo['data'],
            'order' => $orderInfo,
        ];
        return $info;
    }

    public function inImages($image_id, $goods_id)
    {
        return isset($this->goodsImages($goods_id)[$image_id]);
    }

    public function goodsImages($goods_id)
    {
        if ($this->goods_images[$goods_id]) {
            return $this->goods_images[$goods_id];
        }
        $images = MemberOrderGoodsImages::find()->where([
            'combo_order_number' => $this->combo_order_number,
            'goods_id' => $goods_id
        ])->indexBy('image_id')->all();
        $this->goods_images[$goods_id] = $images;
        return $images;
    }

    public function getMember()
    {
        return $this->hasOne(Member::class, ['id' => 'member_id']);
    }

    public function getCombo()
    {
        return $this->hasOne(Combo::class, ['id' => 'combo_id']);
    }

    public function getOrderDetails()
    {
        return $this->hasMany(MemberOrderDetail::class, ['combo_order_number' => 'combo_order_number']);
    }

    public function getAbGoods(){
        return $this->hasMany(AbGoods::class,['goods_code'=>'goods_code'])->via('orderDetails');
    }

    public function getMemberOrder()
    {
        return $this->hasOne(MemberOrder::class, ['order_number' => 'order_number']);
    }

    public function getOrderImages()
    {
        return $this->hasMany(MemberOrderImage::class, ['member_order_id' => 'id'])->via('memberOrder');
    }

    public function viewShootFinishTime($format = '')
    {
        $timestamp = strtotime($this->shoot_finish_time);
        if ($format) {
            return date($format, $timestamp);
        }
        $weekdays = [
            1 => '星期一',
            2 => '星期二',
            3 => '星期三',
            4 => '星期四',
            5 => '星期五',
            6 => '星期六',
            7 => '星期天',
        ];
        return date('Y-m-d', $timestamp) . ' (' . $weekdays[date('N', $timestamp)] . ') ' . date('H:i', $timestamp);
    }
}
