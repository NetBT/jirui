<?php

namespace backend\models;

use common\models\Status;
use Yii;
use yii\base\Exception;
use common\models\Functions;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * 功能的简述：Class MemberOrderGoodsImages
 * 创建作者：
 * 创建时间：
 * @property MemberOrderImage $image
 * 修改日期         修改者             BUG小功能修改申请单号
 * 注意：
 */
class MemberOrderGoodsImages extends Common
{

    public $attributeLabels = [
        "id",                   //主键
        "order_number",         //总订单编号
        "combo_order_number",   //套系订单编号
        "image_id",           //图片ID
        "goods_code",             //商品ID
        "created_at"            //创建时间
    ];

    public function rules()
    {
        return [
            [
                [
                    "id",                   //主键
                    "order_number",
                    "combo_order_number",
                    "image_id",
                    "goods_code",
                    "created_at"
                ],
                'required'
            ],
            ['order_number', 'string', 'max' => 64],
            ['goods_code', 'string', 'max' => 50],
            ['combo_order_number', 'string', 'max' => 32],
            [['image_id', 'created_at'], 'integer']
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ],
        ];
    }

    public static function tableName()
    {
        return '{{%ab_member_order_goods_images}}';
    }


    public function getMemberOrder()
    {
        return $this->hasOne(MemberOrder::class, ['order_number' => 'order_number']);
    }

    public function getComboOrder()
    {
        return $this->hasOne(MemberOrderCombo::class, ['combo_order_number' => 'combo_order_number']);
    }

    public function getImage()
    {
        return $this->hasOne(MemberOrderImage::class,['id'=>'image_id']);
    }

    public function getImageUrl()
    {
        return $this->image->imageUrl;
    }
}
