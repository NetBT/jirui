<?php

namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    const THEME = 'default';
    public $css = [
//        'theme/' . self::THEME . '/css/style.min.css',
        'theme/' . self::THEME . '/css/style.css',
        'theme/' . self::THEME . '/css/sjy.css',
        "js-unit/font-awesome-4.7.0/css/font-awesome.min.css",
    ];
    public $js = [
//        'theme/' . self::THEME . '/js/common.min.js',
//        'theme/' . self::THEME . '/js/function.min.js',
//        'theme/' . self::THEME . '/js/main.min.js',
//        'theme/' . self::THEME . '/js/sub.min.js',
        'theme/' . self::THEME . '/js/common.js',
        'theme/' . self::THEME . '/js/function.js',
        'theme/' . self::THEME . '/js/main.js',
        'theme/' . self::THEME . '/js/sub.js',
    ];


    //依赖包
    public $depends = [
        'yii\web\YiiAsset',//主要包含yii.js 文件，该文件完成模块JavaScript代码组织功能， 也为 data-method 和 data-confirm属性提供特别支持和其他有用的功能
        'yii\bootstrap\BootstrapAsset',//包含从Twitter Bootstrap 框架的CSS文件。
    ];

}
