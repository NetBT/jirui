<?php
namespace backend\controllers;

use backend\models\ABGoodsImages;
use backend\models\ABStatement;
use Yii;
/**
 * Site controller
 */
class AbStatementController extends CommonController
{
    public function actionAbHomeEchartsData() {
        $this->returnJson();
        $model = new ABStatement();
        return $model->getHomeEchartsData();
    }

    public function actionAbIncomeRateData() {
        $this->returnJson();
        $model = new ABStatement();
        return $model->getHomeStoreIncomePieData();
    }
}
