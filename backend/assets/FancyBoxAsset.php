<?php

namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * jQueryForm资源包。
 */
class FancyBoxAsset extends AssetBundle
{
    public $basePath = '@webroot';//指定资源文件放在@webroot目录下
    public $baseUrl = '@web';//对应的URL为@web
    //资源包中包含的css文件
    public $css = [
        '/js-unit/fancybox-3.5.2/dist/jquery.fancybox.min.css',
    ];

    //资源包中包含的js文件
    public $js = [
        '/js-unit/fancybox-3.5.2/dist/jquery.fancybox.min.js',
    ];

    public $jsOptions = ['position'=>View::POS_BEGIN];
}
