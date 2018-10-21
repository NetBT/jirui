<?
/**
 * @var $this \yii\web\View
 * @var $memberOrder \backend\models\MemberOrder
 * @var $comboOrder \backend\models\MemberOrderCombo
 * @var $abGoods \backend\models\AbGoods
 */
\backend\assets\FancyBoxAsset::register($this);
$i=0;
?>
<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-12"><b>开始选片</b>List of Member Order</div>
    </div>
    <div class="main-body col-xs-12">
        <div class="fancybox-thumbs__list">
            <? foreach ($comboOrder->memberOrder->images as $image): ?>
                <div>
                    <a href="<?= $image->getImageUrl() ?>" data-fancybox="<?= $comboOrder->inImages($image->id,
                        $abGoods->id) ? 'images' : '' ?>">
                        <img src="<?= $image->getImageUrl() ?>" width="100" height="75"/>
                    </a>
                    <input type="checkbox" <?= $comboOrder->inImages($image->id, $abGoods->id) ? 'checked' : '' ?>
                           value="<?= $image->id ?>" style="float: left;">
                </div>
            <? endforeach; ?>
        </div>
    </div>
</div>
<script>
</script>

