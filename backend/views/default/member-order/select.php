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
        <div class="header-title col-xs-12"><b>开始粗选</b></div>
        <div class="col-xs-12"><h4 class="col-xs-12">订单#<?= $comboOrder->order_number?></h4></div>
        <div class="col-xs-12">
            <div class="col-xs-1">拍摄时间：</div>
            <div class="col-xs-2 text-info"><?= $comboOrder->viewShootFinishTime()?></div>
            <div class="col-xs-1">顾客称呼：</div>
            <div class="col-xs-1 text-info"><?= $comboOrder->member->name?></div>
            <div class="col-xs-1">订单状态：</div>
            <div class="col-xs-1 text-info">正在选片中</div>
        </div>
    </div>
    <div class="main-body col-xs-12">
        <div class="fancybox-thumbs__list">
            <form method="post" action="<?= \yii\helpers\Url::to([
                'member-order/goods-select',
                'combo_order_number' => $comboOrder->combo_order_number
            ]) ?>">
                <button type="submit" class="btn btn-sm btn-primary">选择商品</button>
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