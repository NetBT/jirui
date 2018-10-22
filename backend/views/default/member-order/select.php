<?
/**
 * @var $this \yii\web\View
 * @var $memberOrder \backend\models\MemberOrder
 * @var $comboOrder \backend\models\MemberOrderCombo
 * @var $abGoods \backend\models\AbGoods
 */
\backend\assets\FancyBoxAsset::register($this);
$i = 0;
?>
<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-12"><b>开始选片</b>List of Member Order</div>
    </div>
    <div class="main-body col-xs-12">
        <div class="fancybox-thumbs__list">
            <form method="post" action="<?= \yii\helpers\Url::to(['member-order/choose']) ?>">
                <button type="submit" class="btn btn-sm btn-primary">进入细选</button>
                <input type="hidden" name="order_number" value="<?= $comboOrder->order_number?>">
                <input type="hidden" name="combo_order_number" value="<?= $comboOrder->combo_order_number?>">
                <input type="hidden" name="goods_id" value="<?= $abGoods->id?>">
                <? foreach ($comboOrder->memberOrder->images as $image): ?>
                    <div id="imagelist">
                        <a href="<?= $image->getImageUrl() ?>" data-fancybox="<?= $comboOrder->inImages($image->id,
                            $abGoods->id) ? 'images' : '' ?>">
                            <img src="<?= $image->getImageUrl() ?>" width="100" height="75"/>
                        </a>
                        <input name="images[]" type="checkbox" <?= $comboOrder->inImages($image->id,
                            $abGoods->id) ? 'checked' : '' ?>
                               value="<?= $image->id ?>" style="float: left;">
                    </div>
                <? endforeach; ?>
            </form>
        </div>
    </div>
</div>