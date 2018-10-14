<?php

namespace frontend\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * 全局CSS样式 + JS
 * Class AppAsset
 * @package frontend\assets
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'theme/default/css/main.min.css',
        'theme/default/css/icon.min.css'
    ];
    public $js = [
        'theme/default/js/function.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    public $jsOptions = ['position' => View::POS_HEAD];

}
