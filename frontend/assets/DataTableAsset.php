<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * DataTable 相关
 * Class DataTableAsset
 * @package frontend\assets
 */
class DataTableAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'js-unit/dataTable/css/dataTables.bootstrap.css',
    ];
    public $js = [
        'js-unit/dataTable/js/jquery.dataTables.js',
        'js-unit/dataTable/js/dataTables.bootstrap.js',
        'theme/default/js/uc_mobile.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
