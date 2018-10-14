<?php

namespace backend\assets;


/**
 * 后台模板资源包。
 */
class HuiLTE9Asset extends AppAsset
{

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    const THEME = 'default';
    public $css = [
    ];
    public $js = [
            'http://libs.useso.com/js/html5shiv/3.7/html5shiv.min.js',
            'http://libs.useso.com/js/respond.js/1.4.2/respond.min.js',
            'http://cdn.bootcss.com/css3pie/2.0beta1/PIE_IE678.js',
    ];

    public $jsOptions = [
        'condition' => 'lte IE9'
    ];

    //依赖包
    public $depends = [
        'yii\web\YiiAsset',//主要包含yii.js 文件，该文件完成模块JavaScript代码组织功能， 也为 data-method 和 data-confirm属性提供特别支持和其他有用的功能
    ];
}
