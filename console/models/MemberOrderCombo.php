<?php
namespace console\models;
use common\models\Common;

/**
 * Created by PhpStorm.
 * User: eycuit
 * Date: 2018/7/8
 * Time: 18:11
 */

class MemberOrderCombo extends Common {

    public static function tableName()
    {
        return "{{%ab_member_order_combo}}";
    }
}