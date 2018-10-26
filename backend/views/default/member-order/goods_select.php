<?
/**
 * @var $this \yii\web\View
 * @var $comboOrder \backend\models\MemberOrderCombo
 */
?>
<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-12"><b>选择商品</b></div>
        <div class="col-xs-12"><h4 class="col-xs-12">订单#<?= $comboOrder->order_number ?></h4></div>
        <div class="col-xs-12">
            <div class="col-xs-2">拍摄时间：</div>
            <div class="col-xs-3 text-info"><?= $comboOrder->viewShootFinishTime() ?></div>
            <div class="col-xs-2">顾客称呼：</div>
            <div class="col-xs-2 text-info"><?= $comboOrder->member->name ?></div>
        </div>
        <div class="col-xs-12">
            <div class="col-xs-2">订单状态：</div>
            <div class="col-xs-2 text-info">正在选片中</div>
        </div>
    </div>
    <div class="main-body col-xs-12">
        <p class="fancybox-thumbs__list">
            <? foreach ($comboOrder->orderDetails as $goodsDetail): ?>
        <div class="thumbnail" style="cursor: pointer;" title="<?= $goodsDetail->goods_name ?>"
             data-uri="<?= \yii\helpers\Url::to([
                 'member-order/choose',
                 'combo_order_number' => $comboOrder->combo_order_number,
                 'goods_code' => $goodsDetail->goods_code,
                 'images_key' => $images_key
             ]) ?>">
            <? if ($goodsDetail->getComboGoodsFirstImage($comboOrder->combo_order_number)): ?>
                <img src="<?= $goodsDetail->getComboGoodsFirstImage($comboOrder->combo_order_number)->getImageUrl() ?>" width="240"
                     height="160"/>
            <? else: ?>
                <div style="width:240px;height:160px">empty!</div>
            <? endif; ?>
            <div><?= $goodsDetail->goods_name ?></div>
        </div>
        <? endforeach; ?>
    </div>
</div>
<script>
    $(function () {
        $('.thumbnail').click(function () {
            creatIframe($(this).attr('data-uri'), $(this).attr('title'));
        })
    })
</script>
