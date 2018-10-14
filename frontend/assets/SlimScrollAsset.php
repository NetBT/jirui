<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * 虚拟滚动条
 * Class SlimScrollAsset
 * @package frontend\assets
 */
class SlimScrollAsset extends AppAsset
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'js-unit/jquery.slimscroll.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
