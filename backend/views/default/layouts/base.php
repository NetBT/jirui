<?php
use backend\assets\AppAsset;
\backend\assets\HuiAsset::register($this);
\backend\assets\HuiLTE9Asset::register($this);
\backend\assets\HuiLTE7Asset::register($this);
AppAsset::register($this);
//\backend\assets\BootstrapColorPickerAsset::register($this);
?>
<?php $this->beginPage() ?>

<!--此处是处理 '您提交的表单无法验证'的问题-->
<?//= Html::csrfMetaTags() ?>
<!--此处是处理 '您提交的表单无法验证'的问题-->

<!DOCTYPE HTML>
<html>
<head>
    <title><?= $this->params['web_name'] ?></title>
    <meta name="keywords" content="交易管理平台-后台管理">
    <meta name="description" content="交易管理平台-后台管理">
    <meta charset="utf-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="Bookmark" href="/favicon.ico" >
    <link rel="Shortcut Icon" href="/favicon.ico" />
    <?php $this->head() ?>
</head>
<body style="background: #f1f2f7">

<?php $this->beginBody() ?>

<?= \backend\components\HeaderWidget::widget()?>

<?= \backend\components\NavWidget::widget()?>

<?= $content?>

<footer class="footer">
    <div class="container">
        <div class="content">联系电话：<?= \common\models\Functions::getCommonByKey('tel_bottom')?>&emsp;联系地址：<?= \common\models\Functions::getCommonByKey('link_address')?>&emsp; 备案号ICP：<?= \common\models\Functions::getCommonByKey('web_ICP')?></div>
        <div class="content"><?= \common\models\Functions::getCommonByKey('web_other')?></div>
    </div>
</footer>
<?php $this->endBody() ?>


</body>
<script>
    $(function () {
        stopRefresh();
    });
</script>
<!--请在下方写此页面业务相关的脚本-->

</html>
<?php $this->endPage() ?>


