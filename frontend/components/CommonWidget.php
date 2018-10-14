<?php
namespace frontend\components;

use common\models\Functions;
use frontend\models\Images;
use yii\base\Widget;
use yii\helpers\Html;

class CommonWidget extends Widget
{
    public $url = '';
    public $id;
    public $isMobile = false;
}