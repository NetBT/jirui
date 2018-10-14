<?php
namespace backend\controllers;

use backend\models\AB;
use backend\models\Goods;
use backend\models\GoodsImages;
use common\models\Functions;
use yii\bootstrap\ActiveForm;
use Yii;
/**
 * Site controller
 */
class GoodsController extends CommonController
{
    //============================   总部商品列表   =====================================
    public function actionList()
    {
        return $this->render('list');
    }

    //列表信息显示
    public function actionListData()
    {
        $this->returnJson();
        $goods = new Goods();
        return $goods->getListData();
    }

    public function actionAdd() {
        $addModel = new Goods(['scenario' => 'add']);
        $this->layout = 'layer_main';
        GoodsImages::emptySession();
        return $this->render('add', [
            'model' => $addModel,
        ]);
    }

    public function actionDoAdd() {
        $addModel = new Goods(['scenario' => 'add']);
        $this->returnJson();
        if (!$addModel->load(\Yii::$app->request->post())){
            return Functions::formatJson(2000, '获取失败');
        }
        return $addModel->saveData();
    }

    public function actionEdit() {
        $id = \Yii::$app->request->get('id');
        $model = Goods::findOne(['id' => $id]);
        $model->setScenario('edit');
        $this->layout = 'layer_main';
        $where['goods_id'] = $id;
        GoodsImages::updateSession($id);
        $GoodsImage = GoodsImages::getOneByWhere(['goods_id' => $id]);
        return $this->render('edit', [
            'model' => $model,
            'id' => $id,
            'poster' => $GoodsImage['image_url']
        ]);
    }

    public function actionDoEdit () {
        $addModel = new Goods(['scenario' => 'edit']);
        $this->returnJson();
        if ($addModel->load(\Yii::$app->request->post()) && $addModel->saveData()){
            return Functions::formatJson(1000, '修改成功');
        }
        return Functions::formatJson(2000, '修改失败');
    }
    /**
     * 该方法是异步校验字段，输入框失去焦点之后自动会自动请求改地址
     * @return array
     */
    public function actionValidateForm()
    {
        $type = \Yii::$app->request->get('type');
        $editAdminModel = new Goods(['scenario' => $type]);
        if (\Yii::$app->request->isAjax && $editAdminModel->load(\Yii::$app->request->post()))
        {
            $this->returnJson();
            return ActiveForm::validate($editAdminModel);
        }
    }

    public function actionDoDelete() {
        $id = \Yii::$app->request->post('id');
        $model = new Goods();
        $this->returnJson();

        return $model->deleteData($id);
    }

    public function actionAddColor() {
        return $this->renderAjax('add_color');
    }
    public function actionAddSize() {
        return $this->renderAjax('add_size');
    }

    public function actionDetail() {
        $goodsId = Yii::$app->request->post('goodsId');
        $info = Goods::getOneByWhere(['id' => $goodsId]);
        $this->layout = 'layer_main';
        $imageList = GoodsImages::getByWhere(['goods_id' => $goodsId]);
        return $this->render('detail', [
            'info' => $info,
            'imgList' => $imageList
        ]);
    }

    public function actionDoShelf() {
        $goodsId = Yii::$app->request->post('goodsId');
        $model = new Goods();
        $this->returnJson();
        return $model->toggleShelf($goodsId);
    }

    //导出总部商品信息
    public function actionExportExcel()
    {
        $model = new Goods();
        $model->exportExcel();
    }
}
