<?php
namespace backend\models;

use common\models\Status;
use Yii;
use yii\base\Exception;
use common\models\Functions;

class MemberOrderSecond extends Common
{
    public static function tableName()
    {
        return '{{%ab_member_order_second}}';
    }
}
