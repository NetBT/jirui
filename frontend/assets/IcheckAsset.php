<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * bootstrap-icheck插件
 * Class IcheckAsset
 * @package frontend\assets
 */
class IcheckAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'js-unit/icheck/skins/square/blue.css',
    ];
    public $js = [
        'js-unit/icheck/icheck.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
