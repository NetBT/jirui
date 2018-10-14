<article class="page-container">
    <div class="col-xs-4 text-right">订单编号：</div>
    <div class="col-xs-6 text-left"><?= $info['order_number']?></div>
    <div class="col-xs-4 text-right">商品名称：</div>
    <div class="col-xs-6 text-left"><?= $info['goods_name']?></div>
    <div class="col-xs-4 text-right">店铺名称：</div>
    <div class="col-xs-6 text-left"><?= $ABInfo['AB_name']?></div>
    <div class="col-xs-4 text-right">退货数量：</div>
    <div class="col-xs-6 text-left"><?= $info['goods_nums']?></div>
    <div class="shop_tab_bottom col-xs-12 text-center">
        <button class="btn btn-hot" href="javascript:void(0);" onclick="doRefundGoods()">退货</button>
    </div>
</article>
<script>
    $(function () {
       $(".selectpicker").selectpicker({
           style: 'btn-default',
           width: '100%'
       });
    });
    function doRefundGoods() {
        var params = {
            id: '<?= $info['id']?>'
        };
        ajaxSubmit('<?= \yii\helpers\Url::to(['goods-order/do-refund-goods'])?>', params, function () {
            $(".layui-layer-close1").click();
            $("#doSearch").click();
        });
    }
</script>
