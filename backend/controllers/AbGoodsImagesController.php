<?php
namespace backend\controllers;

use backend\models\ABGoodsImages;
use Yii;
/**
 * Site controller
 */
class AbGoodsImagesController extends CommonController
{
    public function actionDeleteImage() {
        $index = \Yii::$app->request->post('index');
        $goodsImages = new ABGoodsImages();
        $this->returnJson();
        return $goodsImages->deleteImage($index);
    }

    public function actionEdit() {
        $post = Yii::$app->request->post();
        $session = Yii::$app->getSession();
        $data = ['id' => null];
        if (isset($post['id']) && !empty($post['id'])) {
            $data['id'] = $post['id'];
            ABGoodsImages::updateSession($post['id']);
        } else {
            $imgList = [];
            $session->set('AbGoodsImage', $imgList);
        }
        return $this->renderAjax('edit', $data);
    }

    public function actionUpload() {
        $goodsImage = new ABGoodsImages();
        $this->returnJson();
        return $goodsImage->uploadImage();
    }
}
