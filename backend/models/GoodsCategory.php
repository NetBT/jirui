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
class GoodsCategory extends Common
{
    public static function tableName()
    {
        return '{{%goods_category}}';
    }

    public static function getCategoryMap () {
        $list = static::getByWhere([], ['id', 'category_name']);
        return Functions::extractKey($list, 'id', 'category_name');
    }
}
