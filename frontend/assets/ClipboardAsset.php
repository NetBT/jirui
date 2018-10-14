<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * bootstrap-icheck插件
 * Class IcheckAsset
 * @package frontend\assets
 */
class ClipboardAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        "js-unit/clipboard.min.js"
    ];
}
