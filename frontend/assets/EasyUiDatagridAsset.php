<?php

namespace frontend\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Main frontend application asset bundle.
 */
class EasyUiDatagridAsset extends AssetBundle
{
    public $basePath = '@webroot';      //制定自愿从哪个网络可以访问的目录提供服务 @webroot 是指向应用 web 目录的别名。
    public $baseUrl = '@web';           //用来指定$css  $js相对的根URL
    public $css = [
        "js-unit/jquery-easyui/themes/default/easyui.css"
    ];
    public $js = [
        "js-unit/jquery-easyui/jquery.easyui.min.js",
        "/js-unit/jquery-easyui/plugins/jquery.pagination.js",
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
    public $jsOptions = [
        'position' => View::POS_END
    ];
}
