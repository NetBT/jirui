<?php
namespace backend\models;

use common\models\Status;
use moonland\phpexcel\Excel;
use Yii;
use yii\base\Exception;
use common\models\Functions;

/**
 * 供应商信息表
 * Class AB
 * @package backend\models
 */
class GoodsOrder extends Common
{
    public static function tableName()
    {
        return '{{%goods_order}}';
    }
    /**
     * 获取商品列表
     * @return array
     */
    public function getListData () {
        $returnData = [
            "draw" => intval(Yii::$app->request->post('draw')),
            "recordsTotal" => 0,
            "recordsFiltered" => 0,
            "data" => null
        ];
        //搜索条件
        $post = Yii::$app->request->post('extra_search');
        //计算总数
        $where = [];
        isset($post['order_number']) && !empty($post['order_number']) ? ($where['order_number'] = $post['order_number']) : null;
        isset($post['AB_number']) && !empty($post['AB_number']) ? ($where['AB_number'] = $post['AB_number']) : null;

        $andWhere = $this->getAndWhereForTime('create_time', $post['startTime'], $post['endTime']);
        $count = static::getCountByAndWhere($where, $andWhere);

        $returnData["recordsTotal"] = $returnData['recordsFiltered'] = intval($count);

        //设置分页
        $this->setPagination();
        $orderMap = Status::getGOStatusMap();
        //获取数据
        $returnData['data'] = static::getByAndWhere($where, $andWhere, ['*'], 'create_time desc', $this->_Pagination['offset'], $this->_Pagination['limit']);
        if (!empty($returnData['data'])) {
            foreach ($returnData['data'] as $k => $v) {
                $info = Employee::getEmployeeNameById([$v['order_user'], $v['operate_user']], 'employee_name');
                $returnData['data'][$k]['order_user'] = $info[$v['order_user']];
                $returnData['data'][$k]['operate_user'] = $info[$v['operate_user']];
                $returnData['data'][$k]['order_status_name'] = $orderMap[$v['order_status']];
                $returnData['data'][$k]['create_time'] = str_replace(' ', '<br />', $v['create_time']);
            }
        }
        return $returnData;
    }

    /**
     * 获取商品列表
     * @return array
     */
    public function getABListData () {
        $returnData = [
            "draw" => intval(Yii::$app->request->post('draw')),
            "recordsTotal" => 0,
            "recordsFiltered" => 0,
            "data" => null
        ];
        //搜索条件
        $post = Yii::$app->request->post('extra_search');
        //计算总数
        $where['AB_id'] = self::getBusinessId();
        isset($post['order_number']) && !empty($post['order_number']) ? ($where['order_number'] = $post['order_number']) : null;
        isset($post['AB_number']) && !empty($post['AB_number']) ? ($where['AB_number'] = $post['AB_number']) : null;

        $andWhere = $this->getAndWhereForTime('create_time', $post['startTime'], $post['endTime']);
        $count = static::getCountByAndWhere($where, $andWhere);

        $returnData["recordsTotal"] = $returnData['recordsFiltered'] = intval($count);

        //设置分页
        $this->setPagination();
        $orderMap = Status::getGOStatusMap();
        //获取数据
        $returnData['data'] = static::getByAndWhere($where, $andWhere, ['*'], 'create_time desc', $this->_Pagination['offset'], $this->_Pagination['limit']);
        if (!empty($returnData['data'])) {
            foreach ($returnData['data'] as $k => $v) {
                $info = Employee::getEmployeeNameById([$v['order_user'], $v['operate_user']], 'employee_name');
                $returnData['data'][$k]['order_user'] = $info[$v['order_user']];
                $returnData['data'][$k]['operate_user'] = $info[$v['operate_user']];
                $returnData['data'][$k]['order_status_name'] = $orderMap[$v['order_status']];
            }
        }
        return $returnData;
    }

    /**
     * 下单量统计
     * return格式: [
     *      'xAxis' => [
     *          'data' => ['x1', 'x2' ...]
     *      ],
     *      'series' => [
     *          'data' => [100, 200, 300, ...]
     *      ]
     * ]
     * @return array
     */
    public function echartsOrderQuantity() {
        $post = Yii::$app->request->post();
        $startTime = !empty($post["start"]) ? date("Y-m-d 00:00:00", strtotime($post["start"])) : date("Y-m-d 00:00:00");
        $endTime = !empty($post["end"]) ? date("Y-m-d 23:59:59", strtotime($post["end"])) : date("Y-m-d 23:59:59");

        //获取下单量
        $andWhere[] = ['between', 'create_time', $startTime, $endTime];
        $list = static::getByAndWheres([], $andWhere, ['id','create_time']);
        $timeMap = Functions::getTimeForEchart($startTime, $endTime);
        $result = ['xAxis' => ['data' => []], 'series' => ['data' => []]];
        foreach ($timeMap as $v) {
            $num = 0;
            foreach ($list as $kk => $vv) {
                if ($vv['create_time'] >= $v['start'] && $vv['create_time'] <= $v['end']) {
                    $num ++;
                }
            }
            $tmp = [$v['xAxis'], $num];
            $result['series']['data'][] = $tmp;
        }
        return $result;
    }

    /**
     * 成交量统计
     * return格式: [
     *      'xAxis' => [
     *          'data' => ['x1', 'x2' ...]
     *      ],
     *      'series' => [
     *          'data' => [100, 200, 300, ...]
     *      ]
     * ]
     * @return array
     */
    public function echartsVolume() {
        $post = Yii::$app->request->post();
        $startTime = !empty($post["start"]) ? date("Y-m-d 00:00:00", strtotime($post["start"])) : date("Y-m-d 00:00:00");
        $endTime = !empty($post["end"]) ? date("Y-m-d 23:59:59", strtotime($post["end"])) : date("Y-m-d 23:59:59");

        //获取下单量
        $where['order_status'] = [Status::G_O_STATUS_SETTLEMENT, Status::G_O_STATUS_ALL_SHIPPED, Status::G_O_STATUS_PART_SHIPPED, Status::G_O_STATUS_COMPLETE];
        $andWhere[] = ['between', 'pay_time', $startTime, $endTime];
        $list = static::getByAndWheres($where, $andWhere, ['id','pay_time']);
        $timeMap = Functions::getTimeForEchart($startTime, $endTime);
        $result = ['xAxis' => ['data' => []], 'series' => ['data' => []]];
        foreach ($timeMap as $v) {
            $num = 0;
            foreach ($list as $kk => $vv) {
                if ($vv['pay_time'] >= $v['start'] && $vv['pay_time'] <= $v['end']) {
                    $num ++;
                }
            }
            $tmp = [$v['xAxis'], $num];
            $result['series']['data'][] = $tmp;
        }
        return $result;
    }
    /**
     * 成交金额统计
     * return格式: [
     *      'xAxis' => [
     *          'data' => ['x1', 'x2' ...]
     *      ],
     *      'series' => [
     *          'data' => [100, 200, 300, ...]
     *      ]
     * ]
     * @return array
     */
    public function echartsTurnVolume() {
        $post = Yii::$app->request->post();
        $startTime = !empty($post["start"]) ? date("Y-m-d 00:00:00", strtotime($post["start"])) : date("Y-m-d 00:00:00");
        $endTime = !empty($post["end"]) ? date("Y-m-d 23:59:59", strtotime($post["end"])) : date("Y-m-d 23:59:59");

        //获取下单量
        $where['order_status'] = [Status::G_O_STATUS_SETTLEMENT, Status::G_O_STATUS_ALL_SHIPPED, Status::G_O_STATUS_PART_SHIPPED, Status::G_O_STATUS_COMPLETE];
        $andWhere[] = ['between', 'pay_time', $startTime, $endTime];
        $list = static::getByAndWheres($where, $andWhere, ['id','pay_time', 'order_real_money']);
        $timeMap = Functions::getTimeForEchart($startTime, $endTime);
        $result = ['xAxis' => ['data' => []], 'series' => ['data' => []]];
        foreach ($timeMap as $v) {
            $money = 0;
            foreach ($list as $kk => $vv) {
                if ($vv['pay_time'] >= $v['start'] && $vv['pay_time'] <= $v['end']) {
                    $money += $vv['order_real_money'];
                }
            }
            $tmp = [$v['xAxis'], $money];
            $result['series']['data'][] = $tmp;
        }
        return $result;
    }

    /**
     * 保存订单：直接下订单  购物费勾选下订单
     * @return array
     */
    public function saveOrder() {
        $params = Yii::$app->request->post('params');
        $mark = Yii::$app->request->post('mark');
        $trans = Yii::$app->db->beginTransaction();
        try {
            $ABInfo = AB::getOneByWhere(['id' => static::getBusinessId()]);
            $minOrder['order_number'] = $this->makeOrderNumber();
            $minOrder['AB_id'] = static::getBusinessId();
            $minOrder['AB_number'] = $ABInfo['AB_number'];
            $minOrder['order_user'] = Yii::$app->user->getId();
            $minOrder['order_money'] = 0;
            $minOrder['order_real_money'] = 0;
            $minOrder['order_discount'] = 0;
            $minOrder['order_pay_type'] = Status::AB_POSTPONE_PAY_WAY_BALANCE;
            $minOrder['mark'] = $mark;
            $minOrder['create_time'] = date('Y-m-d H:i:s');
            $minOrder['operate_user'] = Yii::$app->user->getId();
            $totalMoney = $totalRealMoney = $totalDiscount = 0;
            //存储订单
            if (empty($params)) {
                throw new Exception('空的订单信息');
            }
            $cart = ABGoodsOrderCart::getByWhere(['user_id' => Yii::$app->user->getId()]);
            foreach ($params as $k => $v) {
                $goodsId = intval($v['goodsId']);
                $goodsNum = intval($v['goodsNum']);
                if (empty($goodsId) || empty($goodsNum) || $goodsId < 1 || $goodsNum <= 0) {
                    throw new Exception('商品信息错误');
                }
                $goodsInfo = Goods::getOneByWhere(['id' => $v['goodsId']]);
                //检查是否有商品
                if (empty($goodsInfo)) {
                    throw new Exception('未知商品信息');
                }
                //检查商品状态
                if($goodsInfo['goods_status'] != Status::GOODS_STATUS_PUT_ON_SHELVES) {
                    throw new Exception($goodsInfo['goods_name'] . '-未上架,请重新确认订单');
                }
                //检查商品库存
                if ($goodsInfo['goods_num'] < $v['goodsNum']) {
                    throw new Exception($goodsInfo['goods_name']. '-库存不足');
                }
                $data['order_number'] = $minOrder['order_number'];
                $data['goods_id'] = $goodsId;
                $data['goods_name'] = $goodsInfo['goods_name'];
                $data['goods_texture'] = $goodsInfo['goods_texture'];
                $data['goods_style'] = $goodsInfo['goods_style'];
                $data['goods_code'] = $goodsInfo['goods_code'];
                $data['goods_size'] = $goodsInfo['goods_size'];
                $data['goods_color'] = $goodsInfo['goods_color'];
                $data['goods_unit_price'] = $goodsInfo['goods_price'];
                $data['goods_discount'] = $goodsInfo['goods_discount'];
                $data['goods_category'] = $goodsInfo['goods_category'];
                $data['goods_cost'] = $goodsInfo['goods_cost'];
                $data['goods_real_price'] = Goods::getGoodsCurrPriceByInfo($goodsInfo);
                $data['goods_nums'] = $goodsNum;
                $data['create_time'] = date('Y-m-d H:i:s');
                $data['subtotal'] = $data['goods_real_price'] * $goodsNum;
                $data['status'] = Status::G_O_D_STATUS_WAIT_SHIPMENT;
                if (!empty($cart)) {
                    //清除购物车
                    foreach ($cart as $cv) {
                        if ($cv['goods_id'] == $goodsId && $cv['order_num'] == $goodsNum) {
                            $cartWhere['user_id'] = Yii::$app->user->getId();
                            ABGoodsOrderCart::deleteAll(['id' => $cv['id']]);
                        }
                    }
                }

                //总计累加
                $totalMoney += $goodsNum * $data['goods_unit_price'];
                $totalRealMoney += $data['subtotal'];
                $totalDiscount += $data['goods_discount'] * $goodsNum;

                $res = GoodsOrderDetail::insertData($data);
                if ($res === false) {
                    throw new Exception('订单生成失败');
                }
                //扣减总部库存
                $goodsData['goods_num'] = $goodsInfo['goods_num'] - $goodsNum;
                $goodsData['update_time'] = date('Y-m-d H:i:s');
                $goodsRes = Goods::updateData($goodsData, ['id' => $goodsId]);
                if ($goodsRes === false) {
                    throw new Exception('扣减库存失败');
                }
            }
            //执行帐变 扣款失败则状态为未支付
            $res = ABCoinChange::recordCoinChange(AB::getBusinessId(), $totalRealMoney, Status::AB_CC_TYPE_HEADQUARTERS_PURCHASE, '总部直购商品');
            if ($res === true) {
                $minOrder['order_status'] = Status::G_O_STATUS_SETTLEMENT;
                $minOrder['pay_time'] =  date('Y-m-d H:i:s');
            } else {
                $minOrder['order_status'] = Status::G_O_STATUS_NOT_PAID;
            }

            $minOrder['order_money'] = $totalMoney;
            $minOrder['order_real_money'] = $totalRealMoney;
            $minOrder['order_discount'] = $totalDiscount;
            $res = static::insertData($minOrder);
            if ($res === false) {
                throw new Exception('订单生成失败');
            }

            $trans->commit();
            return Functions::formatJson(1000, '下单成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(2000, $e->getMessage());
        }
    }

    /**
     * 订单支付成功
     * @return array
     */
    public function payOrder(){
        $orderNumber = Yii::$app->request->post('orderNumber');
        $trans = Yii::$app->db->beginTransaction();
        try {
            if (empty($orderNumber)) {
                throw new Exception('订单编码错误');
            }

            $orderInfo = static::getOneByWhere(['order_number' => $orderNumber]);
            if (empty($orderInfo)) {
                throw new Exception('订单信息错误');
            }
            if ($orderInfo['AB_id'] != static::getBusinessId()) {
                throw new Exception('订单归属错误');
            }

            if ($orderInfo['order_status'] != Status::G_O_STATUS_NOT_PAID) {
                throw new Exception('订单已支付');
            }
            $totalMoney = $orderInfo['order_real_money'];
            $res = ABCoinChange::recordCoinChange(static::getBusinessId(), $totalMoney, Status::AB_CC_TYPE_HEADQUARTERS_PURCHASE, '总部直购商品');
            if ($res !== true) {
                throw new Exception($res['message']);
            }
            $data['order_status'] = Status::G_O_STATUS_SETTLEMENT;
            $data['pay_time'] = date('Y-m-d H:i:s');
            $where['order_number'] = $orderNumber;
            $res = static::updateData($data, $where);
            if($res === false) {
                throw new Exception('更新失败');
            }
            $trans->commit();
            return Functions::formatJson(1000, '支付成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(2000, $e->getMessage());
        }
    }
    /**
     * 生成订单号 规则：加盟商简拼+店铺编号(9999最大)+年月日+当日第几单(9999最大预留)
     * @return string
     */
    public function makeOrderNumber() {
        $ABInfo = AB::getOneByWhere(['id' => static::getBusinessId()]);
        $where['AB_id'] = $ABInfo['id'];
        do {
            $n = GoodsOrder::getCountByWhere($where);
            $n ++;
            $orderNumber = $ABInfo['AB_code'] . $ABInfo['id'] . date('Ymd') . Functions::zeroFill($n, 4);
            $count = GoodsOrder::getCountByWhere(['order_number' => $orderNumber]);
        } while ($count);
        return $orderNumber;
    }

    /**
     * 执行退款操作
     * @return array
     */
    public function doRefundMoney() {
        $trans = Yii::$app->db->beginTransaction();
        try {
            $post = Yii::$app->request->post();
            $orderNumber = $post['orderNumber'];
            $refundMoney = floatval($post['refundMoney']);
            $refundType = intval($post['refundType']);
            if (empty($refundMoney) || $refundMoney < 0) {
                throw new Exception('退款金额不能为空');
            }
            if (empty($refundType) || !array_key_exists($refundType, Status::HeadRefundMoneyTypeMap())) {
                throw new Exception('退款类型错误');
            }
            $orderInfo = static::getOneByWhere(['order_number' => $orderNumber]);
            if (empty($orderInfo)) {
                throw new Exception('订单信息不存在');
            }

            if($orderInfo['order_real_money'] < $refundMoney) {
                throw new Exception('退款金额不能大于实付金额');
            }

            $data['order_real_money'] = $orderInfo['order_real_money'] - $refundMoney;
            $data['update_time'] = date('Y-m-d H:i:s');
            $where['order_number'] = $orderNumber;
            $res = static::updateDataWithLog($data, $where);
            if ($res === false) {
                throw new Exception('退款失败,请联系技术支持');
            }

            //如果是退到余额中则执行帐变
            if ($refundType == Status::HEAD_REFUND_TYPE_BALANCE) {
                $res = ABCoinChange::recordCoinChange($orderInfo['AB_id'], $refundMoney, Status::AB_CC_TYPE_HEADQUARTERS_REFUND, '总部退款');
                if ($res !== true) {
                    throw new Exception($res['message']);
                }
            }

            //记录总部退款记录
            $res = HeadRefundLog::recordRefundMoneyLog($orderInfo['AB_id'], $refundMoney, $orderNumber, $refundType, '总部执行退款');
            if($res !==  true) {
                throw new Exception($res['msg']);
            }
            $trans->commit();
            return Functions::formatJson(1000, '退款成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(2000, $e->getMessage());
        }
    }

    /**
     * 更新总订单
     * @param string $orderNumber
     *
     * @return bool
     */
    public function updateOrder($orderNumber = '') {
        $detail = GoodsOrderDetail::getByWhere(['order_number' => $orderNumber],['id' ,'status']);
        $count = count($detail);
        $totalSend = 0;
        $totalWait = 0;
        $totalRefund = 0;
        $totalBroken = 0;
        foreach ($detail as $k => $v) {
            switch ($v['status']) {
                case Status::G_O_D_STATUS_ALL_SHIPPED:
                    $totalSend++;
                    break;
                case Status::G_O_D_STATUS_STOCK_OUT:
                    $totalBroken++;
                    break;
                case Status::G_O_D_STATUS_REFUND:
                    $totalRefund++;
                    break;
                case Status::G_O_D_STATUS_WAIT_SHIPMENT:
                    $totalWait ++;
                    break;
                default:
                    continue;
            }
        }
        //如果等待发货或者断货数量大于0 并且已经发货了部分， 则为部分发货
        if (($totalWait > 0 || $totalBroken > 0) && $totalSend > 0) {
            $status = Status::G_O_STATUS_PART_SHIPPED;
        }
        //如果已发送的等于总数量-退款的，则订单状态为全部发货
        if ($totalSend == $count - $totalRefund) {
            $status = Status::G_O_STATUS_ALL_SHIPPED;
        }
        //如果所有的退货等于总订单则为全部退货
        if($totalRefund == $count) {
            $status = Status::G_O_STATUS_ALL_REFUND;
        }
        if (empty($status)) {
            return true;
        }
        $orderInfo = static::getOneByWhere(['order_number' => $orderNumber], 'order_status');
        if ($orderInfo['order_status'] != $status) {
            $data['order_status'] = $status;
            $data['update_time'] = date('Y-m-d H:i:s');
            $res = $this->updateDataWithLog($data, ['order_number' => $orderNumber]);
            if ($res === false) {
                return false;
            }
        }
        return true;
    }

    public function getTotal() {
        $post = Yii::$app->request->post();
        $startTime = !empty($post["start"]) ? date("Y-m-d 00:00:00", strtotime($post["start"])) : date("Y-m-d 00:00:00");
        $endTime = !empty($post["end"]) ? date("Y-m-d 23:59:59", strtotime($post["end"])) : date("Y-m-d 23:59:59");
        $data = [
            'totalOrderNum' => 0,
            'totalVolume' => 0,
            'totalTurnVolume' => 0,
            'totalRefundAmount' => 0
        ];
        //获取下单量
        $andWhere = ['between', 'pay_time', $startTime, $endTime];
        $data['totalOrderNum'] = floatval(static::getCountByAndWhere([], $andWhere));
        //获取成交量
        $where['order_status'] = [Status::G_O_STATUS_SETTLEMENT, Status::G_O_STATUS_ALL_SHIPPED, Status::G_O_STATUS_PART_SHIPPED, Status::G_O_STATUS_COMPLETE];
        $andWhere = ['between', 'pay_time', $startTime, $endTime];
        $data['totalVolume'] = floatval(static::getCountByAndWhere($where, $andWhere));
        //获取成交金额
        $where['order_status'] = [Status::G_O_STATUS_SETTLEMENT, Status::G_O_STATUS_ALL_SHIPPED, Status::G_O_STATUS_PART_SHIPPED, Status::G_O_STATUS_COMPLETE];
        $andWhere = ['between', 'pay_time', $startTime, $endTime];
        $data['totalTurnVolume'] = floatval(static::getSumByWhereAndWhere($where, $andWhere, 'order_real_money'));
        //获取退货金额
        $andWhere = ['between', 'refund_time', $startTime, $endTime];
        $data['totalRefundAmount'] = floatval(HeadRefundLog::getSumByWhereAndWhere([], $andWhere, 'refund_money'));
        return Functions::formatJson(1000, '成功', $data);
    }

    /**
     * return [
        'xAxis' => ['data' => [
        '2018-01',
        '2018-02',
        '2018-03',
        '2018-04',
        '2018-05',
    ]],
    'series' => ['data' => [900, 100, 200, 300, 400]]
    ];
     */
    public function getHomeEchartsData() {
        $xData = $series = [];
        for($i = 11; $i >= 0; $i--) {
            $month = Functions::getMonthStartEnd(date('Y-m', strtotime('-'. $i .' month')));
            $xData[] = $month['month'];
            $where['order_status'] = [Status::G_O_STATUS_SETTLEMENT, Status::G_O_STATUS_ALL_SHIPPED, Status::G_O_STATUS_PART_SHIPPED, Status::G_O_STATUS_COMPLETE];
            $andWhere = ['between', 'create_time', $month['startDate'], $month['endDate']];
            $income = static::getSumByWhereAndWhere($where, $andWhere, 'order_real_money');
            $series[] = floatval($income);
        }
        return [
            'xAxis' => ['data' => $xData],
            'series' => ['data' => $series],
        ];
    }

    public static function getIncome($start = null, $end = null) {
        if (empty($start)) {
            $start = date("Y-m-d 00:00:00");
        }
        if (empty($end)) {
            $end = date("Y-m-d 23:59:59");
        }
        $where['AB_id'] = static::getBusinessId();
        $andWhere = ['between', 'pay_time', $start, $end];
        $income = static::getSumByWhereAndWhere($where, $andWhere, 'order_real_money');
        return floatval($income);
    }

    /**
     * 导出excel
     */
    public function exportExcel()
    {
        $businessId = Common::getBusinessId();
        $where = [];
        if(!empty($businessId) && $businessId != 1) {
            $where['AB_id'] = $businessId;
        }
        $list = self::find()->where($where)->asArray()->all();
        $employeeInfo = Employee::getFormArray('','id','employee_name');
        foreach ($list as $key => $value) {
            $list[$key]['order_user'] = $value['order_user'] ? $employeeInfo[$value['order_user']] : '--';
            $list[$key]['operate_user'] = $value['operate_user'] ? $employeeInfo[$value['operate_user']] : '--';
            $list[$key]['order_status'] = $value['order_status'] ? Status::getGOStatusMap()[$value['order_status']] : '--';
        }
        Excel::export([
            'models' => $list,
            'fileName' => date('Ymd').'导出总部商品订单信息',
            'columns' => [
                'order_number',
                'AB_id',
                'AB_number',
                'order_user',
                'order_money',
                'order_real_money',
                'order_discount',
                'order_status',
                'mark',
                'create_time',
                'pay_time',
                'order_refund_amount',
                'operate_user',
                'refund_time',
            ], //没有头工作,因为头会得到标签的属性标签
            'headers' => [
                'order_number' => '订单编号',
                'AB_id' => '商户',
                'AB_number' => '合同编号',
                'order_user' => '订单操作',
                'order_money' => '订单金额',
                'order_real_money' => '实际收款',
                'order_discount' => '折扣价',
                'order_status' => '订单状态',
                'mark' => '备注',
                'create_time' => '订单时间',
                'pay_time' => '支付时间',
                'order_refund_amount' => '退款金额',
                'operate_user' => '退货操作者',
                'refund_time' => '退货时间',
            ],
        ]);
    }
}
