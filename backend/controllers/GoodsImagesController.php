<?php
namespace backend\controllers;

use backend\models\GoodsImages;
use Yii;
/**
 * Site controller
 */
class GoodsImagesController extends CommonController
{
    public function actionDeleteImage() {
        $index = \Yii::$app->request->post('index');
        $goodsImages = new GoodsImages();
        $this->returnJson();
        return $goodsImages->deleteImage($index);
    }

    public function actionEdit() {
        $post = Yii::$app->request->post();
        $session = Yii::$app->getSession();
        $data = ['id' => null];
        if (isset($post['id']) && !empty($post['id'])) {
            $data['id'] = $post['id'];
            GoodsImages::updateSession($post['id']);

        } else {
            $imgList = [];
            $session->set('goodsImage', $imgList);
        }
        return $this->renderAjax('edit', $data);
    }

    public function actionUpload() {
        $goodsImage = new GoodsImages();
        $this->returnJson();
        return $goodsImage->uploadImage();
    }
}
