<?php
namespace backend\models;

use common\models\Status;
use Yii;
use yii\base\Exception;
use common\models\Functions;

/**
 * 供应商信息表
 * Class AB
 * @package backend\models
 */
class ABGoodsOrderCart extends Common
{
    public static function tableName()
    {
        return '{{%ab_goods_order_cart}}';
    }

    /**
     * 添加购物车
     * @return array
     */
    public function addToCart($goodsId = 0) {
        if (empty($goodsId)) {
            $goodsId = Yii::$app->request->post('goodsId');
        }
        $orderNum = Yii::$app->request->post('orderNum');
        $orderNum = isset($orderNum) && $orderNum > 0 ? intval($orderNum) : 1;

        $goodsId = intval($goodsId);
        $where['goods_id'] = $goodsId;
        $where['user_id'] = Yii::$app->user->getId();
        $info = static::getOneByWhere($where);
        if (!empty($info)) {
            $data['order_num'] = $info['order_num'] + $orderNum;
            $res = static::updateData($data, $where);
        } else {
            $data['user_id'] = Yii::$app->user->getId();
            $data['goods_id'] = $goodsId;
            $data['order_num'] = $orderNum;
            $data['create_time'] = date('Y-m-d H:i:s');
            $res = static::insertData($data);
        }

        if ($res === false) {
            return Functions::formatJson(2000, '购物车添加失败');
        }
        return Functions::formatJson(1000, '添加成功');
    }

    /**
     * 获取购物车列表
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getCartList() {
        $where['user_id'] = Yii::$app->user->getId();
        $list = static::getByWhere($where, ['goods_id', 'order_num']);
        $list = Functions::extractKey($list, 'goods_id', 'order_num');
        $where = [];

        $goods = new Goods();
        $where['goods_status'] = Status::GOODS_STATUS_PUT_ON_SHELVES;
        $where['id'] = array_keys($list);
        $goodsDetail = $goods->getGoodsListForWhere($where);
        foreach ($goodsDetail as $k => $v) {
            $goodsDetail[$k]['order_num'] = $list[$v['id']];
        }
        return $goodsDetail;
    }

    /**
     * 删除购物车内信息
     * @return array
     */
    public function deleteCart() {
        $goodsId = Yii::$app->request->post('goodsId');
        $goodsId = intval($goodsId);
        if (empty($goodsId)) {
            return Functions::formatJson(2000, '参数错误');
        }
        $where['user_id'] = Yii::$app->user->getId();
        $where['goods_id'] = $goodsId;
        $res = static::deleteAll($where);
        if ($res === false) {
            return Functions::formatJson(2000, '删除失败');
        }
        return Functions::formatJson(1000, '删除成功');
    }
}
