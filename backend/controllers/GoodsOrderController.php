<?php
namespace backend\controllers;

use backend\models\AB;
use backend\models\Employee;
use backend\models\Goods;
use backend\models\GoodsOrder;
use backend\models\GoodsOrderDetail;
use backend\models\HeadRefundLog;
use common\models\Functions;
use common\models\Status;
use Yii;
/**
 * Site controller
 */
class GoodsOrderController extends CommonController
{
    public function actionList() {
        return $this->render('list');
    }

    public function actionListData()
    {
        $this->returnJson();
        $goods = new GoodsOrder();
        return $goods->getListData();
    }

    public function actionGetHeadDetail() {
        $orderNumber = Yii::$app->request->post('orderNumber');
        $orderInfo = GoodsOrder::getOneByWhere(['order_number' => $orderNumber]);
        $orderABInfo = AB::getOneByWhere(['id' => $orderInfo['AB_id']]);
        $linkUser = Employee::getOneByWhere(['id' => $orderABInfo['AB_principal']]);
        return $this->renderAjax('order_head_detail', [
            'orderNumber' => $orderNumber,
            'info' => $orderInfo,
            'ABInfo' => $orderABInfo,
            'linkUser' => $linkUser['employee_name']
        ]);
    }

    public function actionGetHeadDetailData() {
        $this->returnJson();
        $goods = new GoodsOrderDetail();
        return $goods->getDetailData();
    }

    public function actionTradeOrderStatistic() {
        return $this->render('trade_order_statistic');
    }

    public function actionSetTotal() {
        $model = new GoodsOrder();
        $this->returnJson();
        return $model->getTotal();
    }

    public function actionEchartsHeadHomeLine () {
        $model = new GoodsOrder();
        $this->returnJson();
        return $model->getHomeEchartsData();
    }

    /**
     * 下单量统计 -- 走势图
     * @return array
     */
    public function actionEchartsOrderQuantity() {
        $model = new GoodsOrder();
        $this->returnJson();
        return $model->echartsOrderQuantity();
    }

    /**
     * 成交量统计 -- 走势图
     * @return array
     */
    public function actionEchartsVolume() {
        $model = new GoodsOrder();
        $this->returnJson();
        return $model->echartsVolume();
    }

    /**
     * 成交金额统计 -- 走势图
     * @return array
     */
    public function actionEchartsTurnVolume() {
        $model = new GoodsOrder();
        $this->returnJson();
        return $model->echartsTurnVolume();
    }
    /**
     * 退货金额统计 -- 走势图
     * @return array
     */
    public function actionEchartsRefundAmount() {
        $model = new HeadRefundLog();
        $this->returnJson();
        return $model->echartsRefundAmount();
    }

    public function actionConfirmOrder() {
        $params = Yii::$app->request->post('params');
        if (empty($params)) {
            return false;
        }
        $list = Functions::extractKey($params, 'goodsId', 'orderNum');
        $where['goods_status'] = Status::GOODS_STATUS_PUT_ON_SHELVES;
        $where['id'] = array_keys($list);
        $goods = new Goods();
        $orderList = $goods->getGoodsListForWhere($where);
        foreach ($orderList as $k => $v) {
            $orderList[$k]['order_num'] = $list[$v['id']];
        }
        return $this->renderAjax('confirm_order', [
            'list' => $orderList
        ]);
    }

    public function actionGetDetail() {
        $orderNumber = Yii::$app->request->post('orderNumber');
        return $this->renderAjax('order_detail', [
            'orderNumber' => $orderNumber
        ]);
    }

    public function actionGetDetailData() {
        $this->returnJson();
        $goods = new GoodsOrderDetail();
        return $goods->getDetailData();
    }

    /**
     * 总部直购订单-下单
     * @return array
     */
    public function actionSaveOrder() {
        $goodsOrder = new GoodsOrder();
        $this->returnJson();
        return $goodsOrder->saveOrder();
    }

    /**
     * 加盟商直购订单列表
     * @return string
     */
    public function actionAbOrderList() {
        return $this->render('AB_order_list');
    }

    /**
     * 加盟商直购订单列表数据
     * @return array
     */
    public function actionAbOrderListData() {
        $this->returnJson();
        $goods = new GoodsOrder();
        return $goods->getABListData();
    }

    public function actionPayOrder() {
        $model = new GoodsOrder();
        $this->returnJson();
        return $model->payOrder();
    }

    /**
     * 展示发货页面 -- 总部
     * @return string
     */
    public function actionShowSend() {
        $id = Yii::$app->request->post('id');
        $info = GoodsOrderDetail::getOneInfoById($id);
        return $this->renderAjax('send', ['info' => $info]);
    }

    /**
     * 执行发送
     * @return array
     */
    public function actionDoSend() {
        $this->returnJson();
        $model = new GoodsOrderDetail();
        $id = intval(Yii::$app->request->post('id'));
        return $model->doSend($id);
    }
    /**
     * 执行全部发送
     * @return array
     */
    public function actionDoAllSend() {
        $this->returnJson();
        $model = new GoodsOrderDetail();
        $orderNumber = intval(Yii::$app->request->post('orderNumber'));
        return $model->doAllSend($orderNumber);
    }

    /**
     * 断货
     * @return array
     */
    public function actionDoBroken() {
        $this->returnJson();
        $model = new GoodsOrderDetail();
        return $model->doBroken();
    }

    /**
     * 退款页面
     * @return string
     */
    public function actionRefundMoney() {
        $orderNumber = Yii::$app->request->post('orderNumber');
        $info = GoodsOrder::getOneByWhere(['order_number' => $orderNumber]);
        $ABInfo = AB::getOneInfoById($info['AB_id']);
        return $this->renderAjax('refund_money', [
            'info' => $info,
            'ABInfo' => $ABInfo
        ]);
    }

    public function actionDoRefundMoney() {
        $this->returnJson();
        $model = new GoodsOrder();
        return $model->doRefundMoney();
    }

    /**
     * 展示发货页面 -- 总部
     * @return string
     */
    public function actionRefundGoods() {
        $id = Yii::$app->request->post('id');
        $info = GoodsOrderDetail::getOneInfoById($id, ['id', 'order_number', 'goods_nums', 'goods_name']);
        $orderInfo = GoodsOrder::getOneByWhere(['order_number' => $info['order_number']], ['AB_id']);
        $ABInfo = AB::getOneInfoById($orderInfo['AB_id'], 'AB_name');
        return $this->renderAjax('refund_goods', [
            'info' => $info,
            'ABInfo' => $ABInfo
        ]);
    }


    public function actionDoRefundGoods() {
        $this->returnJson();
        $model = new GoodsOrderDetail();
        $id = intval(Yii::$app->request->post('id'));
        return $model->doRefundGoods($id);
    }

    /**
     * 商品入库--加盟商
     */
    public function actionGoodsImport()
    {
        $model = new GoodsOrderDetail();
        $this->returnJson();
        return $model->goodsImport();
    }

    //导出商品订单信息信息
    public function actionExportExcel()
    {
        $model = new GoodsOrder();
        $model->exportExcel();
    }
}
