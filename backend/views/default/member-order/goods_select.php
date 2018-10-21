<?
/**
 * @var $this \yii\web\View
 * @var $comboOrder \backend\models\MemberOrderCombo
 */
?>
<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-12"><b>选择商品</b>List of Member Order</div>
    </div>
    <div class="main-body col-xs-12">
        <p class="fancybox-thumbs__list">
            <? foreach ($comboOrder->comboGoods as $abGoods): ?>
        <div class="thumbnail" style="cursor: pointer;" title="<?= $abGoods->goods_name?>" data-uri="<?= \yii\helpers\Url::to([
            'member-order/select',
            'combo_order_number' => $comboOrder->combo_order_number,
            'goods_id' => $abGoods->id
        ]) ?>">
            <? if ($abGoods->getComboGoodsFirstImage($comboOrder->combo_order_number)): ?>
                <img src="<?= $abGoods->getComboGoodsFirstImage($comboOrder->combo_order_number) ?>" width="240"
                     height="160"/>
            <? else: ?>
                <div style="width:240px;height:160px">empty!</div>
            <? endif; ?>
            <div><?= $abGoods->goods_name?></div>
        </div>
        <? endforeach; ?>
    </div>
</div>
<script>
    $(function () {
        $('.thumbnail').click(function () {
            creatIframe($(this).attr('data-uri'),$(this).attr('title'));
        })
    })
</script>
