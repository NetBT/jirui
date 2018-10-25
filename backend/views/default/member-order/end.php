<?
/**
 * @var $this \yii\web\View
 *
 */
?>
<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-12"><b>选片结束</b></div>
    </div>
    <div class="main-body col-xs-12">
        <div class="col-xs-12"><?= $msg ?></div>
        <? if ($comboOrder->allImagesSelected()): ?>
            <div class="col-xs-12">
                此订单的所有商品已经进行选片，点击后面按钮完成选片。<br/>
                <button onclick="endSelect()" class="btn btn-success" id="end_select">完成选片</button>
            </div>
        <? endif; ?>
    </div>
</div>
<script>
    function endSelect() {
        var comboOrderNumber = "<?= $comboOrder->combo_order_number?>";
        layer.confirm('【' + comboOrderNumber + '】该订单选片完成？', function (index) {
            var params = {
                comboOrderNumber: comboOrderNumber,
                type: <?= \common\models\Status::MEMBER_ORDER_COMBO_NOT_SELECT?>,
                beforeStatus: <?= \common\models\Status::MEMBER_ORDER_SELECT_STATUS_ING?>,
                afterStatus: <?= \common\models\Status::MEMBER_ORDER_SELECT_STATUS_YES?>,
            };
            ajaxSubmit('<?= \yii\helpers\Url::to(['calendar-plan/change-combo-order-status'])?>', params, function () {
                removeIframe();
            });
        });
    }
</script>