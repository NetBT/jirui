<?php
namespace backend\controllers;

use backend\models\ABGoodsImages;
use backend\models\ABStatement;
use backend\models\AbSupplier;
use common\models\Functions;
use Yii;
/**
 * Site controller
 */
class AbSuppliersController extends CommonController
{
    public function actionList() {
        return $this->render('list');
    }

    public function actionListData() {
        $this->returnJson();
        $model = new AbSupplier();
        return $model->getListData();
    }

    public function actionAdd() {
        $model = new AbSupplier(['scenario' => 'add']);
        return $this->renderAjax('add', ['model' => $model]);
    }

    public function actionDoAdd() {
        $model = new AbSupplier(['scenario' => 'add']);
        $this->returnJson();
        if ($model->load(\Yii::$app->request->post())){
            return $model->doSave();
        }
        return Functions::formatJson(2000, '数据获取失败');
    }

    public function actionEdit() {
        $id = Yii::$app->request->post('id');
        $model = AbSupplier::findOne($id);
        $model->setScenario('edit');
        return $this->renderAjax('edit', ['model' => $model]);
    }

    public function actionDoEdit() {
        $model = new AbSupplier(['scenario' => 'edit']);
        $this->returnJson();
        if ($model->load(\Yii::$app->request->post())){
            return $model->doSave();
        }
        return Functions::formatJson(2000, '数据获取失败');
    }

    public function actionDoDelete() {
        $id = Yii::$app->request->post('id');
        $model = new AbSupplier();
        $this->returnJson();
        return $model->doDelete($id);
    }
}
