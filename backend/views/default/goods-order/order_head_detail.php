<article class="page-container col-xs-12">
    <div class="main-body col-xs-12">
        <table class="tab" width="100%" border="0" cellspacing="0" cellpadding="0" id="detailList"></table>
    </div>
    <div class="main-footer order-detail col-xs-8 col-xs-offset-2 container">
        <div class="col-xs-12 footer-line">
            <div class="col-xs-3 text-right">加盟商: </div>
            <div class="col-xs-3 text-left"><?= $ABInfo['AB_name']?> </div>
            <div class="col-xs-3 text-right">合同编号: </div>
            <div class="col-xs-3 text-left"><?= $ABInfo['AB_number']?> </div>
        </div>
        <div class="col-xs-12 footer-line">
            <div class="col-xs-3 text-right">联系人: </div>
            <div class="col-xs-3 text-left"><?= $linkUser?></div>
            <div class="col-xs-3 text-right">联系电话: </div>
            <div class="col-xs-3 text-left"><?= $ABInfo['AB_tel']?></div>
        </div>
        <div class="col-xs-12 footer-line">
            <div class="col-xs-3 text-right">收货地址: </div>
            <div class="col-xs-9 text-left"><?= $ABInfo['AB_address']?></div>
        </div>
        <div class="col-xs-12 footer-line">
            <div class="col-xs-3 text-right">总额:</div>
            <div class="col-xs-3 text-left"><?= $info['order_discount']?></div>
            <div class="col-xs-3 text-right">实际收款: </div>
            <div class="col-xs-3 text-left"><?= $info['order_real_money']?></div>
        </div>
        <div class="col-xs-12 footer-line">
            <div class="col-xs-3 text-right">订单时间: </div>
            <div class="col-xs-3 text-left"><?= $info['create_time']?></div>
            <div class="col-xs-3 text-right">付款时间: </div>
            <div class="col-xs-3 text-left"><?= $info['pay_time']?></div>
        </div>
    </div>
</article>
<script>
    $(function(){
        DataTable.init('initHeadGoodsOrderDetailList','#detailList','<?= \yii\helpers\Url::to(['goods-order/get-head-detail-data', 'orderNumber' => $orderNumber])?>',getDetailParams());
    });
    function getDetailParams (){
        return [];
    }

    function sendOutGoods(id) {
        layer_show({id: id}, '发货', '<?= \yii\helpers\Url::to(['goods-order/show-send'])?>', 500,200)
    }

    function brokenGoods(id) {
        layer.confirm('是否断货？', {icon: 0, title: '提示'}, function (index) {
            ajaxSubmit('<?= \yii\helpers\Url::to(['goods-order/do-broken'])?>', {id: id});
            layer_close(index);
            DataTable.reloadTable();
            $("#doSearch").click();
        });
    }

    function refundGoods(id) {
        layer_show({id: id}, '退货', '<?= \yii\helpers\Url::to(['goods-order/refund-goods'])?>', 600, 400);
    }
</script>
