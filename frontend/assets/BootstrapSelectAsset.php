<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * bootstrap-selectpicker相关
 * Class BootstrapSelectAsset
 * @package frontend\assets
 */
class BootstrapSelectAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'js-unit/bootstrap-select/css/bootstrap-select.min.css',
    ];
    public $js = [
        'js-unit/bootstrap-select/js/bootstrap-select.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
