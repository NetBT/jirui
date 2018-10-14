<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * dataTable资源包。
 */
class LoginAsset extends AssetBundle
{
    public $basePath = '@webroot';//指定资源文件放在@webroot目录下
    public $baseUrl = '@web';//对应的URL为@web
    const THEME = 'default';
    //资源包中包含的css文件
    public $css = [
//        'theme/' . self::THEME . '/css/style.min.css',
        'theme/' . self::THEME . '/css/style.css',
    ];

    //资源包中包含的js文件
    public $js = [
        'theme/' . self::THEME . '/js/main.min.js',
        'theme/' . self::THEME . '/js/sub.min.js',
    ];

    //依赖包
    public $depends = [
        'yii\web\YiiAsset',//主要包含yii.js 文件，该文件完成模块JavaScript代码组织功能， 也为 data-method 和 data-confirm属性提供特别支持和其他有用的功能
    ];
}
