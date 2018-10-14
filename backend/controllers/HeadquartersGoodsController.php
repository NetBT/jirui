<?php
namespace backend\controllers;

use backend\models\AB;
use backend\models\ABGoodsOrderCart;
use backend\models\Goods;
use backend\models\GoodsImages;
use backend\models\GoodsOrder;
use backend\models\GoodsOrderCart;
use common\models\Functions;
use common\models\Status;
use yii\bootstrap\ActiveForm;
use Yii;
/**
 * Site controller
 */
class HeadquartersGoodsController extends CommonController
{
    //============================   加盟商总部直购列表   =====================================
    public function actionList()
    {
        //获取总部商品列表
        $goods = new Goods();
        $data = $goods->getGoodsListForSale();
        return $this->render('list', $data);
    }

    /**
     * 购物车页面
     * @return string
     */
    public function actionCart()
    {
        $this->layout = 'layer_main';
        $cart = new ABGoodsOrderCart();
        $list = $cart->getCartList();
        return $this->render('cart', ['list' => $list]);
    }

    /**
     * 加入购物车
     * @return array
     */
    public function actionAddCart() {
        $goodsCart = new ABGoodsOrderCart();
        $this->returnJson();
        return $goodsCart->addToCart();
    }

    /**
     * 删除购物车内容
     * @return array
     */
    public function actionDeleteCart() {
        $this->returnJson();
        $cart = new ABGoodsOrderCart();
        return $cart->deleteCart();
    }

    /**
     * 更改购车数量
     * @return bool|int
     */
    public function actionChangeCartNum() {
        $goodsId = Yii::$app->request->post('goodsId');
        $goodsId = intval($goodsId);
        $num = Yii::$app->request->post('num');
        $data['goods_id'] = $goodsId;
        $data['order_num'] = intval($num);
        $where['user_id'] = Yii::$app->user->getId();
        $where['goods_id'] = $goodsId;
        return ABGoodsOrderCart::updateData($data, $where);
    }
}
