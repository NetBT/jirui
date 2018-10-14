<?php
namespace backend\controllers;

use backend\models\AB;
use backend\models\AbGoods;
use backend\models\ABGoodsImages;
use backend\models\Goods;
use backend\models\GoodsImages;
use common\models\Functions;
use yii\bootstrap\ActiveForm;
use Yii;
/**
 * Site controller
 */
class AbGoodsController extends CommonController
{
    //============================   加盟商商品列表   =====================================
    public function actionList()
    {
        return $this->render('list');
    }

    //列表信息显示
    public function actionListData()
    {
        $this->returnJson();
        $goods = new AbGoods();
        return $goods->getListData();
    }

    public function actionAdd() {
        $addModel = new AbGoods(['scenario' => 'add']);
        $this->layout = 'layer_main';
        ABGoodsImages::emptySession();
        return $this->render('add', [
            'model' => $addModel,
        ]);
    }

    public function actionDoAdd() {
        $addModel = new AbGoods(['scenario' => 'add']);
        $this->returnJson();
        if (!$addModel->load(\Yii::$app->request->post())){
            return Functions::formatJson(2000, '获取失败');
        }
        return $addModel->saveData();
    }

    public function actionEdit() {
        $id = \Yii::$app->request->get('id');
        $model = AbGoods::findOne(['id' => $id]);
        $model->setScenario('edit');
        $this->layout = 'layer_main';
        $where['goods_id'] = $id;
        ABGoodsImages::updateSession($id);
        $GoodsImage = ABGoodsImages::getOneByWhere(['goods_id' => $id]);
        return $this->render('edit', [
            'model' => $model,
            'id' => $id,
            'poster' => $GoodsImage['image_url']
        ]);
    }

    public function actionDoEdit () {
        $addModel = new AbGoods(['scenario' => 'edit']);
        $this->returnJson();
        if ($addModel->load(\Yii::$app->request->post()) && $addModel->saveData()){
            return Functions::formatJson(1000, '修改成功');
        }
        return Functions::formatJson(2000, '修改失败');
    }

    public function actionSearchGoods() {
        $goodsCode = Yii::$app->request->post('goodsCode');
        $model = new AbGoods();
        $this->returnJson();
        return $model->searchGoods($goodsCode);
    }

    public function actionStockIn() {
        $goodsCode = Yii::$app->request->post('goodsCode');
        return $this->render('stock_in' ,['code' => $goodsCode]);
    }
    public function actionStockOut() {
        $goodsCode = Yii::$app->request->post('goodsCode');
        return $this->render('stock_out' ,['code' => $goodsCode]);
    }

    public function actionDoStockIn() {
        $this->returnJson();
        $model = new AbGoods();
        return $model->doSockIn();
    }
    public function actionDoStockOut() {
        $this->returnJson();
        $model = new AbGoods();
        return $model->doSockOut();
    }

    /**
     * 该方法是异步校验字段，输入框失去焦点之后自动会自动请求改地址
     * @return array
     */
    public function actionValidateForm()
    {
        $type = \Yii::$app->request->get('type');
        $editAdminModel = new AbGoods(['scenario' => $type]);
        if (\Yii::$app->request->isAjax && $editAdminModel->load(\Yii::$app->request->post()))
        {
            $this->returnJson();
            return ActiveForm::validate($editAdminModel);
        }
    }

    public function actionDoDelete() {
        $id = \Yii::$app->request->post('id');
        $AB = new AB();
        $this->returnJson();

        return $AB->deleteData($id);
    }

    public function actionAddColor() {
        return $this->renderAjax('add_color');
    }
    public function actionAddSize() {
        return $this->renderAjax('add_size');
    }

    public function actionDetail() {
        $goodsId = Yii::$app->request->post('goodsId');
        $info = AbGoods::getOneByWhere(['id' => $goodsId]);
        $this->layout = 'layer_main';
        $imageList = ABGoodsImages::getByWhere(['goods_id' => $goodsId]);
        return $this->render('detail', [
            'info' => $info,
            'imgList' => $imageList
        ]);
    }

    //导出加盟商商品信息
    public function actionExportExcel()
    {
        $model = new AbGoods();
        $model->exportExcel();
    }

    public function actionDoShelf() {
        $goodsId = Yii::$app->request->post('goodsId');
        $model = new AbGoods();
        $this->returnJson();
        return $model->toggleShelf($goodsId);
    }
}
