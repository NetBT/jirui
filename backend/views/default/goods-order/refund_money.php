<article class="page-container">
    <div class="col-xs-4 text-right">订单编号：</div>
    <div class="col-xs-6 text-left"><?= $info['order_number']?></div>
    <div class="col-xs-4 text-right">商铺名称：</div>
    <div class="col-xs-6 text-left"><?= $ABInfo['AB_name']?></div>
    <div class="col-xs-4 text-right">实收金额：</div>
    <div class="col-xs-6 text-left"><?= $info['order_real_money']?></div>
    <div class="col-xs-4 text-right">退款金额：</div>
    <div class="col-xs-6 text-left"><input class="input-text" name="refundMoney" size="4" value="0"></div>
    <div class="col-xs-4 text-right">退款方式：</div>
    <div class="col-xs-8 text-left margin-bottom-15">
        <?php foreach (\common\models\Status::HeadRefundMoneyTypeMap() as $k => $v) :?>
        <label class="radio-inline">
            <input type="radio" name="refundType" <?= $k == \common\models\Status::HEAD_REFUND_TYPE_BALANCE ? 'checked' : ''?> value="<?= $k?>"> <?= $v?>
        </label>
        <?php endforeach;?>
    </div>
    <div class="shop_tab_bottom col-xs-12 text-center">
        <button class="btn btn-hot" href="javascript:void(0);" onclick="doRefundMoney()">退款</button>
    </div>
</article>
<script>
    $(function () {
       $(".selectpicker").selectpicker({
           style: 'btn-default',
           width: '100%'
       });
    });
    function doRefundMoney() {
        var params = {
            orderNumber: '<?= $info['order_number']?>',
            refundMoney: $("input[name='refundMoney']").val(),
            refundType: $("input[name='refundType']:checked").val()
        };
        ajaxSubmit('<?= \yii\helpers\Url::to(['goods-order/do-refund-money'])?>', params, function () {
            $(".layui-layer-close1").click();
            $("#doSearch").click();
        });
    }
</script>
