<?php

namespace frontend\assets;

use yii\web\AssetBundle;
use yii\web\View;
/**
 * bootstrap-icheck插件
 * Class IcheckAsset
 * @package frontend\assets
 */
class ErcodeAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        "js-unit/jquery.qrcode.min.js",
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
    public $jsOptions = [
        'position' => View::POS_HEAD
    ];
}
