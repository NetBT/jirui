<?php

namespace backend\assets;


/**
 * 后台模板资源包。
 */
class HuiLTE7Asset extends AppAsset
{

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    const THEME = 'default';
    public $css = [
        'http://www.bootcss.com/p/font-awesome/assets/css/font-awesome-ie7.min.css'
    ];
    public $js = [

    ];

    public $jsOptions = [
        'condition' => 'lte IE7'
    ];

    //依赖包
    public $depends = [
        'yii\web\YiiAsset',//主要包含yii.js 文件，该文件完成模块JavaScript代码组织功能， 也为 data-method 和 data-confirm属性提供特别支持和其他有用的功能
    ];
}
