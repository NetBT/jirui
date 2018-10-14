<?php
namespace backend\controllers;

use backend\models\AbRecharge;
use backend\models\Advert;
use backend\models\AdvertPostpone;
use backend\models\AdvertRecharge;
use common\models\Functions;
use common\models\Status;
use yii\bootstrap\ActiveForm;

/**
 * Site controller
 */
class AdvertController extends CommonController
{
    public function actionList() {
        return $this->render('list');
    }

    //供应商列表信息显示
    public function actionListData()
    {
        $this->returnJson();
        $model = new Advert();
        return $model->getListData();
    }

    public function actionAdd() {
        $addModel = new Advert(['scenario' => 'add']);
        return $this->renderAjax('add', [
            'model' => $addModel,
        ]);
    }

    public function actionDoAdd() {
        $addModel = new Advert(['scenario' => 'add']);
        $this->returnJson();
        if (!$addModel->load(\Yii::$app->request->post())){
            return Functions::formatJson(2000, '获取失败');
        }
        return $addModel->saveData();
    }

    public function actionEdit() {
        $id = \Yii::$app->request->post('id');
        $model = Advert::findOne(['id' => $id]);
        $model->setScenario('edit');
        return $this->renderAjax('edit', [
            'model' => $model,
        ]);
    }

    public function actionDoEdit() {
        $model = new Advert(['scenario' => 'edit']);
        $this->returnJson();
        if (!$model->load(\Yii::$app->request->post())){
            return Functions::formatJson(2000, '获取失败');
        }
        return $model->saveData();
    }
    /**
     * 该方法是异步校验字段，输入框失去焦点之后自动会自动请求改地址
     * @return array
     */
    public function actionValidateForm()
    {
        $type = \Yii::$app->request->get('type');
        $editAdminModel = new Advert(['scenario' => $type]);
        if (\Yii::$app->request->isAjax && $editAdminModel->load(\Yii::$app->request->post()))
        {
            $this->returnJson();
            return ActiveForm::validate($editAdminModel);
        }
    }
    public function actionDoDelete() {
        $id = \Yii::$app->request->post('id');
        $model = new Advert();
        $this->returnJson();

        return $model->deleteData($id);
    }
    /**
     * 广告充值页面
     * @return string
     */
    public function actionRecharge() {
        $id = \Yii::$app->request->post('id');
        $info = Advert::getOneByWhere(['id' => $id], ['advert_name', 'id']);
        return $this->renderAjax('recharge', [
            'info' => $info,
        ]);
    }

    /**
     * 执行加盟商充值
     * @return array
     */
    public function actionDoRecharge() {
        $this->returnJson();
        $model = new AdvertRecharge();
        return $model->doRecharge();
    }

    /**
     * 加盟商充值页面
     * @return string
     */
    public function actionPostpone() {
        $id = \Yii::$app->request->post('id');
        $info = Advert::getOneByWhere(['id' => $id], ['advert_name', 'id']);
        return $this->renderAjax('postpone', [
            'info' => $info,
        ]);
    }

    /**
     * 执行加盟商充值
     * @return array
     */
    public function actionDoPostpone() {
        $this->returnJson();
        $model = new AdvertPostpone();
        return $model->doPostpone();
    }

    /**
     * 切换加盟商状态  启用  禁用状态
     * @return array
     */
    public function actionToggleStatus () {
        $id = \Yii::$app->request->post('id');
        $model = new Advert();
        $this->returnJson();
        return $model->toggleStatus($id);
    }

    public function actionModalAdvert() {
        $list = Advert::getAdvertRand(1, Status::ADVERT_POSITION_MODAL);
        $advert = array_pop($list);
        return $this->renderAjax('show_modal', ['info' => $advert]);
    }

    public function actionStatistic() {
        return $this->render('statistic');
    }

    /**
     * 广告数统计
     * @return array
     */
    public function actionEchartsAdvertNum() {
        $model = new Advert();
        $this->returnJson();
        return $model->echartsAdvertNum();
    }

    /**
     * 充值续费金额
     * @return array
     */
    public function actionEchartsRecharge() {
        $model = new AdvertRecharge();
        $this->returnJson();
        return $model->echartsRecharge();
    }

    public function actionGetTotal() {
        $model = new Advert();
        $this->returnJson();
        return $model->getTotal();
    }

    //导出商品订单信息信息
    public function actionExportExcel()
    {
        $model = new Advert();
        $model->exportExcel();
    }
}
