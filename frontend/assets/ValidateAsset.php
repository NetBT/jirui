<?php

namespace frontend\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * bootstrap-icheck插件
 * Class IcheckAsset
 * @package frontend\assets
 */
class ValidateAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'js-unit/jquery-validation/jquery.validate.min.js',
        'js-unit/jquery-validation/localization/messages_zh.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
    public $jsOptions = [
        'position' => View::POS_HEAD
    ];
}
