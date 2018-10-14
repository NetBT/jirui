<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
\backend\assets\BootstrapUploadAsset::register($this);
\backend\assets\BootstrapSelectAsset::register($this);
\backend\assets\BootstrapColorPickerAsset::register($this);
\backend\assets\HuiAsset::register($this);
\backend\assets\HuiLTE9Asset::register($this);
\backend\assets\HuiLTE7Asset::register($this);
\backend\assets\DataTableAsset::register($this);
AppAsset::register($this);

?>
<?php $this->beginPage() ?>

<?= Html::csrfMetaTags() ?>
<!--此处是处理 '您提交的表单无法验证'的问题-->
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <?= $this->head();?>
</head>
<body  style="background: #f1f2f7">
    <?php $this->beginBody() ?>

    <?= $content?>

    <?php $this->endBody()?>

</body>
</html>
<?php $this->endPage() ?>

<script>
    $(function(){
        tableCheckbox();
        refreshCurrRefresh();
        addRequireFlag();
    });

    function tableCheckbox()
    {
        $('table').on("change", ":checkbox", function() {
            // 列表复选框
            if ($(this).is("[name='checkbox_wrapper']")) {
                // 全选
                $(":checkbox", 'table').prop("checked",$(this).prop("checked"));
            }
        });
    }
</script>
