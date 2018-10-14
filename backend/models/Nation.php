<?php
namespace backend\models;

use common\models\Functions;
use Yii;

class Nation extends Common
{

    public static function tableName()
    {
        return "{{%nation}}";
    }

    public static function getList()
    {
        $info = self::getByWhere();
        $list = Functions::extractKey($info,'id','name');
        return $list;
    }

}
