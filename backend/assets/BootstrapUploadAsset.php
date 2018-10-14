<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * bootstrapSelect资源包。
 */
class BootstrapUploadAsset extends AssetBundle
{
    public $basePath = '@webroot';//指定资源文件放在@webroot目录下
    public $baseUrl = '@web';//对应的URL为@web
    //资源包中包含的css文件
    public $css = [
        '/js-unit/bootstrap/css/bootstrap-fileupload.min.css',

    ];

    //资源包中包含的js文件
    public $js = [
        '/js-unit/bootstrap/js/bootstrap-fileupload.js',

    ];

    //依赖包
    public $depends = [
        'yii\web\YiiAsset',//主要包含yii.js 文件，该文件完成模块JavaScript代码组织功能， 也为 data-method 和 data-confirm属性提供特别支持和其他有用的功能
        'yii\bootstrap\BootstrapAsset',//包含从Twitter Bootstrap 框架的CSS文件。
    ];
}
