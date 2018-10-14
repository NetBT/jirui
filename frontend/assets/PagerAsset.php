<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * 分页样式和JS
 * Class PagerAsset
 * @package frontend\assets
 */
class PagerAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'js-unit/pager/pager.css',
    ];
    public $js = [
        'js-unit/pager/pager.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
