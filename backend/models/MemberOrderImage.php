<?php

namespace backend\models;

use common\models\Status;
use Yii;
use yii\base\Exception;
use common\models\Functions;

class MemberOrderImage extends Common
{
    public $uploadedImage;
    private $fieldArray = [
        "id",
        'member_order_id',
        'path',
        'type',
        'filename',
        'size',
        'created_at',
        'updated_at',
    ];

    public static function tableName()
    {
        return '{{%ab_member_order_images}}';
    }

}
