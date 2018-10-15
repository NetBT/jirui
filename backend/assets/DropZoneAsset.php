<?php

namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * jQueryForm资源包。
 */
class DropZoneAsset extends AssetBundle
{
    public $basePath = '@webroot';//指定资源文件放在@webroot目录下
    public $baseUrl = '@web';//对应的URL为@web
    //资源包中包含的css文件
    public $css = [
        '/js-unit/drop-zone/css/basic.css',
        '/js-unit/drop-zone/css/dropzone.css',
    ];

    //资源包中包含的js文件
    public $js = [
        '/js-unit/drop-zone/js/dropzone.js',
    ];

    public $jsOptions = ['position'=>View::POS_BEGIN];
}
