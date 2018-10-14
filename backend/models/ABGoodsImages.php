<?php
namespace backend\models;

use common\models\Status;
use phpDocumentor\Reflection\Types\Static_;
use Yii;
use yii\base\Exception;
use common\models\Functions;

/**
 * 供应商信息表
 * Class AB
 * @package backend\models
 */
class ABGoodsImages extends Common
{
    public static function tableName()
    {
        return '{{%ab_goods_images}}';
    }

    public function uploadImage() {
        try {
            $res = Functions::uploadFile('imageFile');
            $id = Yii::$app->request->post('id');
            if ($res === false) {
                throw new Exception(false);
            }
            if (!empty($id)) {
                $data['goods_id'] = $id;
                $data['image_url'] = $res;
                $data['create_time'] = date('Y-m-d H:i:s');
                $count = static::getCountByWhere(['goods_id' => $id]);
                $count ++ ;
                $data['order_no'] = $count;
                static::insertData($data);
                static::updateSession($id);
            } else {
                $session = Yii::$app->getSession();
                $currImgList = $session->get('AbGoodsImage');
                array_unshift($currImgList, $res);
                $session->set('AbGoodsImage', $currImgList);
            }
            return Functions::formatJson(1000, '上传成功', $res);
        } catch (Exception $e) {
            return Functions::formatJson(2000, $e->getMessage());
        }
    }

    public function deleteImage ($index = null) {
        $id = Yii::$app->request->post('imageId');
        if (!empty($id)) {
            $deleteItem = static::getOneByWhere(['id' => $id]);
            static::deleteAll(['id' => $id]);
            $res = Functions::deleteUploadFile($deleteItem['image_url']);
            if ($res === true) {
                static::updateSession($id);
                return Functions::formatJson(1000, '删除成功');
            }
        } else {
            $session = Yii::$app->getSession();
            $imgList = $session->get('AbGoodsImage');
            $fileName = isset($imgList[$index]) && !empty($imgList[$index]) ? $imgList[$index] : null;
            $res = Functions::deleteUploadFile($fileName);
            if ($res === true) {
                unset($imgList[$index]);
                $session->set('AbGoodsImage', $imgList);
                return Functions::formatJson(1000, '删除成功');
            }
        }
        return Functions::formatJson(2000, '文件删除失败');
    }

    public static function emptySession(){
        $session = Yii::$app->getSession();
        $imgList = $session->get('AbGoodsImage');
        if (!empty($imgList)) {
            foreach ($imgList as $v) {
                Functions::deleteUploadFile($v);
            }
        }
        $session->remove('AbGoodsImage');
    }

    public static function deleteByGoodsId($goodsId = 0) {
        $goodsId = intval($goodsId);
        if (empty($goodsId)) {
            return false;
        }
        $currImageList = self::getByWhere(['goods_id' => $goodsId]);
        self::deleteAll(['goods_id' => $goodsId]);
        if (!empty($currImageList)) {
            foreach ($currImageList as $v) {
                Functions::deleteUploadFile($v['image_url']);
            }
        }
        return true;
    }

    public static function updateSession($id = null) {
        $session = Yii::$app->getSession();
        $where['goods_id'] = $id;
        $imgList = self::getByWhere($where, ['id', 'image_url']);
        $imgList = Functions::extractKey($imgList, 'id', 'image_url');
        $session->set('AbGoodsImage', $imgList);
    }

    public static function updateGoodsImages($goodsId = 0){
        $session = Yii::$app->getSession();
        $currImageList = $session->get('AbGoodsImage');
        $dbList = static::getByWhere(['goods_id' => $goodsId]);
        foreach ($dbList as $k => $v) {
            if (!array_key_exists($v['id'], $currImageList)) {
                self::deleteAll(['id' => $v['id']]);
                Functions::deleteUploadFile($v['image_url']);
            }
        }
    }
}
