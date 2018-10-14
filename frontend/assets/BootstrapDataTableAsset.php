<?php

namespace frontend\assets;

use yii\web\AssetBundle;
use yii\web\View;


/**
 * bootstrap-selectpicker相关
 * Class BootstrapSelectAsset
 * @package frontend\assets
 */
class BootstrapDataTableAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "js-unit/dataTable/css/dataTables.bootstrap.min.css"
    ];
    public $js = [
        'js-unit//dataTable/js/jquery.dataTables.min.js',
        'js-unit//dataTable/js/dataTables.bootstrap.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
