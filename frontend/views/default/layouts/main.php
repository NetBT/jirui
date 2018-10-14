<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use frontend\assets\AppAsset;
AppAsset::register($this);
\frontend\assets\BootstrapSelectAsset::register($this);
\frontend\assets\IcheckAsset::register($this);
$this->beginPage();
?>
<?= Html::csrfMetaTags() ?>
    <!doctype html>
    <html>
    <head>
        <title><?= $this->title?></title>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <?php $this->head()?>
    </head>
    <body>
    <?php $this->beginBody() ?>

    <?= $content?>

    <?php $this->endBody()?>
    </body>

    </html>
<?php $this->endPage() ?>