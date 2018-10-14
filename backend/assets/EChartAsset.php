<?php

namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\View;
/**
 * bootstrap-icheck插件
 * Class IcheckAsset
 * @package frontend\assets
 */
class EChartAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        "js-unit/echarts.min.js"
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    public $jsOptions = [
        'position' => View::POS_HEAD
    ];
}
