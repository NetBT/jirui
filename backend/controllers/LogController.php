<?php
namespace backend\controllers;

use backend\models\AbGoodsStock;
use backend\models\Employee;
use backend\models\EmployeePost;
use backend\models\EmployeeRate;
use backend\models\Member;
use backend\models\MemberOrderRefund;
use backend\models\OperateLog;
use backend\models\RecommendRebate;
use Yii;
use yii\bootstrap\ActiveForm;

/**
 * Site controller
 */
class LogController extends CommonController
{

    //==========================   提成报表   =================================
    public function actionIndexRate()
    {
        return $this->render('list_rate');
    }
    //==========================   订单记录   =================================
    public function actionIndexMemberOrder()
    {
        return $this->render('list_member_order');
    }

    //==========================   退单记录   =================================
    public function actionIndexMemberOrderRefund()
    {
        return $this->render('list_member_order_refund');
    }

    public function actionListMemberOrderRefund()
    {
        $model = new MemberOrderRefund();
        $this->returnJson();
        return $model->handelInit($model->getListData());
    }

    //==========================   推荐返利   =================================
    public function actionIndexRebate()
    {
        return $this->render('list_rebate');
    }

    public function actionListRebate()
    {
        $model = new RecommendRebate();
        $this->returnJson();
        return $model->handelInit($model->getListData());
    }

    //==========================   出/入库记录   ==============================
    public function actionIndexGoodsImportExport()
    {
        return $this->render('list_goods_import_export');
    }

    public function actionListGoodsImportExport()
    {
        $model = new AbGoodsStock();
        $this->returnJson();
        return $model->handelInit($model->getListData());
    }

    //==========================   直购订单   =================================
    public function actionIndexGoodsOrder()
    {
        return $this->render('list_goods_order');
    }

    //==========================   进客记录   =================================
    public function actionIndexMemberImport()
    {
        return $this->render('list_member_import');
    }

    public function actionEchartVisited() {
        $model = new Member();
        $this->returnJson();
        return $model->echartsVisited();
    }

}
