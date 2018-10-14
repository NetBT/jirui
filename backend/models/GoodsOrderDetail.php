<?php
namespace backend\models;

use common\models\Status;
use Yii;
use yii\base\Exception;
use common\models\Functions;

/**
 * 供应商信息表
 * Class AB
 * @package backend\models
 */
class GoodsOrderDetail extends Common
{
    public static function tableName()
    {
        return '{{%goods_order_detail}}';
    }

    /**
     * 获取商品详情列表
     * @return array
     */
    public function getDetailData () {
        $returnData = [
            "draw" => intval(Yii::$app->request->post('draw')),
            "recordsTotal" => 0,
            "recordsFiltered" => 0,
            "data" => null
        ];
        //计算总数
        $where['order_number'] = Yii::$app->request->get('orderNumber');
        $count = static::getCountByWhere($where);

        $returnData["recordsTotal"] = $returnData['recordsFiltered'] = intval($count);

        //设置分页
        $this->setPagination();
        $orderMap = Status::getGODStatusMap();
        //获取数据
        $returnData['data'] = static::getByWhere($where, ['*'], 'id asc', $this->_Pagination['offset'], $this->_Pagination['limit']);
        if (!empty($returnData['data'])) {
            foreach ($returnData['data'] as $k => $v) {
                $returnData['data'][$k]['status_name'] = $orderMap[$v['status']];
                $returnData['data'][$k]['import_status_name'] = Status::getGODImportStatusMap()[$v['import_status']];
                $returnData['data'][$k]['create_time'] = str_replace(' ', '<br />', $v['create_time']);

            }
        }
        return $returnData;
    }

    public function doAllSend($orderNumber = '') {
        $where['order_number'] = $orderNumber;
        $where['status'] = [Status::G_O_D_STATUS_WAIT_SHIPMENT,Status::G_O_D_STATUS_STOCK_OUT];
        $list = static::getByWhere($where, ['id']);
        foreach ($list as $v) {
            $this->doSend($v['id']);
        }
        return Functions::formatJson(1000, '执行成功');
    }

    /**
     * 全部发货
     * @return array
     */
    public function doSend ($id = 0) {
        $trans = Yii::$app->db->beginTransaction();
        try {
            if ($id === 0) {
                throw new Exception('信息错误');
            }
            $info = static::getOneInfoById($id);

            if (empty($info)) {
                throw new Exception('未知订单信息');
            }

            //获取总的订单信息
            $orderInfo = GoodsOrder::getOneByWhere(['order_number' => $info['order_number']]);
            if ($orderInfo['order_status'] == Status::G_O_STATUS_ALL_REFUND) {
                throw new Exception('已经全部退货,不能发货');
            }
            if($info['status'] == Status::G_O_D_STATUS_ALL_SHIPPED) {
                throw new Exception('已经全部发货,不能重复操作');
            }
            if($info['status'] == Status::G_O_D_STATUS_REFUND) {
                throw new Exception('已申请退货, 不能发货');
            }

            $data['status'] = Status::G_O_D_STATUS_ALL_SHIPPED;
            $data['send_num'] = $info['goods_nums'];
            $data['update_time'] = date('Y-m-d H:i:s');
            $res = $this->updateDataWithLog($data, ['id' => $id]);
            if ($res === false) {
                throw new Exception('更新数据失败');
            }

            //判断总订单是否已经全部发货

//            $where['order_number'] = $info['order_number'];
//            $detaiList = static::getByWhere($where, 'status');
//            $orderData['order_status'] = Status::G_O_STATUS_ALL_SHIPPED;
//            foreach ($detaiList as $v) {
//                if ($v['status'] != Status::G_O_D_STATUS_ALL_SHIPPED) {
//                    $orderData['order_status'] = Status::G_O_STATUS_PART_SHIPPED;
//                }
//            }
//            $orderData['update_time'] = date('Y-m-d H:i:s');
//            $orderModel = new GoodsOrder();
//            $res = $orderModel->updateDataWithLog($orderData, ['order_number' => $info['order_number']]);
//            if ($res === false) {
//                throw new Exception('总订单更新失败');
//            }
            $model = new GoodsOrder();
            $res = $model->updateOrder($info['order_number']);
            if ($res == false) {
                throw new Exception('订单信息更新失败');
            }
            $trans->commit();
            return Functions::formatJson(1000, '发货成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(2000, $e->getMessage());
        }
    }

    /**
     * 执行断货
     * @return array
     */
    public function doBroken() {
        $id = intval(Yii::$app->request->post('id'));
        $trans = Yii::$app->db->beginTransaction();
        try {
            if ($id === 0) {
                throw new Exception('信息错误');
            }
            $info = static::getOneInfoById($id);

            if (empty($info)) {
                throw new Exception('未知订单信息');
            }
            if($info['status'] == Status::G_O_D_STATUS_ALL_SHIPPED) {
                throw new Exception('已经全部发货,不能操作');
            }
            if($info['status'] == Status::G_O_D_STATUS_REFUND) {
                throw new Exception('已申请退货, 不能断货');
            }

            $data['status'] = Status::G_O_D_STATUS_STOCK_OUT;
            $data['update_time'] = date('Y-m-d H:i:s');
            $res = $this->updateDataWithLog($data, ['id' => $id]);
            if ($res === false) {
                throw new Exception('设置断货失败');
            }
            $trans->commit();
            return Functions::formatJson(1000, '断货成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(2000, $e->getMessage());
        }
    }

    public function doRefundGoods($id = 0) {
        $trans = Yii::$app->db->beginTransaction();
        try {
            if ($id === 0) {
                throw new Exception('信息错误');
            }
            $info = static::getOneInfoById($id);

            if (empty($info)) {
                throw new Exception('未知订单信息');
            }

            //获取总的订单信息
            $orderInfo = GoodsOrder::getOneByWhere(['order_number' => $info['order_number']]);
            //返回给加盟商余额
            $refundMoney = $info['goods_real_price'];
            $res = ABCoinChange::recordCoinChange($orderInfo['AB_id'], $refundMoney, Status::AB_CC_TYPE_HEADQUARTERS_REFUND, '总部退货');
            if ($res !== true) {
                throw new Exception($res['message']);
            }
            //恢复库存
            $goodsInfo = Goods::getOneByWhere(['id' => $info['goods_id']]);
            $goodsData['goods_num'] = $goodsInfo['goods_num'] + $info['goods_nums'];
            $goodsData['update_time'] = date('Y-m-d H:i:s');
            $goods = new Goods();
            $res = $goods->updateDataWithLog($goodsData, ['id' => $info['goods_id']]);
            if ($res === false) {
                throw new Exception('库存增加失败');
            }
            //更改订单状态
            $detailData['status'] = Status::G_O_D_STATUS_REFUND;
            $detailData['update_time'] = date('Y-m-d H:i:s');
            $res = $this->updateDataWithLog($detailData, ['id' => $id]);
            if ($res === false) {
                throw new Exception('更改订单状态失败');
            }
            //更改总订单余额
            $orderData['order_real_money'] = $orderInfo['order_real_money'] - $refundMoney;
            $orderData['update_time'] = date('Y-m-d H:i:s');
            $order = new GoodsOrder();
            $res = $order->updateDataWithLog($orderData, ['order_number' => $info['order_number']]);
            if ($res === false) {
                throw new Exception('更改总订单状态失败');
            }
            $model = new GoodsOrder();
            $res = $model->updateOrder($info['order_number']);
            if ($res == false) {
                throw new Exception('订单信息更新失败');
            }
            //记录退货日志
            $res = HeadRefundLog::recordRefundGoodsLog($id, $refundMoney, $info['goods_nums'], '总部退货');
            if ($res === false) {
                throw new Exception($res['msg']);
            }
            $trans->commit();
            return Functions::formatJson(1000, '退货成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(2000, $e->getMessage());
        }
    }


    public function goodsImport()
    {
        $post = Yii::$app->request->post();
        $id = intval($post['id']);
        $goodsId = intval($post['goods_id']);
        $abGoodsModel = new AbGoods();
        $abGoodsStockModel = new AbGoodsStock();
        $trans = Yii::$app->db->beginTransaction();
        try {
            if ($id === 0) {
                throw new Exception('信息错误');
            }

            $info = static::getOneInfoById($id);
            if (empty($info)) {
                throw new Exception('未知商品信息');
            }

            //将goods_order_detail import_status 状态修改了
            $updateOrderDetail['import_status'] = Status::G_O_D_STATUS_IMPORT_YES;//入库
            $updateOrderDetail['update_time'] = date('Y-m-d H:i:s');
            $resUpdateOrderDetail = self::updateDataWithLog($updateOrderDetail, ['id' => $id]);
            if ($resUpdateOrderDetail === false) {
                throw new Exception('商品入库失败');
            }

            //判断ad_goods表
            $headGoodsInfo = AbGoods::getOneByWhere(['head_goods_id' => $goodsId]);
            if(empty($headGoodsInfo)) {
                //没有此商品  需要添加数据
                $insertGoodsData['head_goods_id'] = $goodsId;
                $insertGoodsData['AB_id'] = Common::getBusinessId();
                $insertGoodsData['goods_code'] = $info['goods_code'];
                $insertGoodsData['goods_name'] = $info['goods_name'];
                $insertGoodsData['goods_category'] = $info['goods_category'];
                $insertGoodsData['goods_price'] = $info['goods_real_price'];
                $insertGoodsData['goods_cost'] = $info['goods_code'];
                $insertGoodsData['goods_discount'] = $info['goods_discount'];
                $insertGoodsData['goods_color'] = $info['goods_color'];
                $insertGoodsData['goods_size'] = $info['goods_size'];
                $insertGoodsData['goods_texture'] = $info['goods_texture'];
                $insertGoodsData['goods_num'] = $info['goods_nums'];
                $insertGoodsData['goods_style'] = $info['goods_style'];
//                $insertGoodsData['goods_type'] = $info['goods_code'];
                $insertGoodsData['create_user'] = Yii::$app->user->getId();
                $insertGoodsData['create_time'] = date('Y-m-d H:i:s');

                $resInsertGoods = $abGoodsModel->insertDataWithLog($insertGoodsData);
                if ($resInsertGoods === false) {
                    throw new Exception('商品入库失败');
                }
                $stockGoodsId = $resInsertGoods;
            } else {
                //有此商品。更改数量
                $updateGoodsData['goods_num'] = $headGoodsInfo['goods_num'] + $info['goods_nums'];
                $updateGoodsData['update_time'] = date('Y-m-d H:i:s');
                $updateGoodsData['update_user'] = Yii::$app->user->getId();
                $resUpdateGoods = $abGoodsModel->updateDataWithLog($updateGoodsData,['head_goods_id' => $goodsId]);
                if ($resUpdateGoods === false) {
                    throw new Exception('商品入库更新失败');
                }
                $stockGoodsId = $headGoodsInfo['id'];
            }

            //添加ab_goods_stock_log
            $insertData['business_id'] = Common::getBusinessId();
            $insertData['head_goods_id'] = $goodsId;
            //此商品ID是ab_goods中的Id
            $insertData['goods_id'] = $stockGoodsId;
            $insertData['goods_name'] = $info['goods_name'];
            $insertData['goods_color'] = $info['goods_color'];
            $insertData['goods_size'] = $info['goods_size'];
            $insertData['goods_texture'] = $info['goods_texture'];
            $insertData['goods_style'] = $info['goods_style'];
            $insertData['goods_real_price'] = $info['goods_real_price'];
            $insertData['operate_num'] = $info['goods_nums'];
            $insertData['total_money'] = $info['subtotal'];
            $insertData['operate_user'] = Yii::$app->user->getId();
            $insertData['create_time'] = date('Y-m-d H:i:s');
            $insertData['operate_type'] = Status::GOODS_STOCK_TYPE_IMPORT;//入库操作
            $resInsertStock = $abGoodsStockModel->insertDataWithLog($insertData);
            if ($resInsertStock === false) {
                throw new Exception('入库记录失败');
            }
            $trans->commit();
            return Functions::formatJson(1000, '入库成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(2000, $e->getMessage());
        }
    }

}
