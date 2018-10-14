<?php

namespace frontend\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * bootstrap-icheck插件
 * Class IcheckAsset
 * @package frontend\assets
 */
class MMenuAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "js-unit/jquery-mmenu/jquery.mmenu.all.css"
    ];
    public $js = [
        "js-unit/jquery-mmenu/jquery.mmenu.all.js"
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
