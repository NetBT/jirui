<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * 虚拟滚动条
 * Class SlimScrollAsset
 * @package frontend\assets
 */
class DateTimePickerAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'js-unit/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css'
    ];
    public $js = [
        'js-unit/js/bootstrap-datetimepicker.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
