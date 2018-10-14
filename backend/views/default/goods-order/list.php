<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-12"><b>订单列表</b>Order List</div>
        <div class="header-search col-xs-12">
            <div class="col-xs-4 input-area">
                <label class="col-xs-3 text-right">创建日期</label>
                <div class="col-xs-4">
                    <input type="text" value="" onclick="WdatePicker({readOnly: true, dateFmt: 'yyyy-MM-dd',maxDate:'#F{$dp.$D(\'endTime\',{d:-1});}'});" class="form-control" id="startTime" placeholder="开始日期">
                </div>
                <label class="col-xs-1 clear-padding text-center">至</label>
                <div class="col-xs-4">
                    <input type="text" value="" onclick="WdatePicker({readOnly: true, dateFmt: 'yyyy-MM-dd', minDate: '#F{$dp.$D(\'startTime\', {d:1});}'});" class="form-control" id="endTime" placeholder="结束日期">
                </div>
            </div>
            <div class="col-xs-3 input-area">
                <label class="col-xs-3 text-right">订单编号</label>
                <div class="col-xs-7">
                    <input type="text" value="" class="form-control" id="orderCode" placeholder="商品编号">
                </div>
            </div>
            <div class="col-xs-3 input-area">
                <label class="col-xs-3 text-right">合同编号</label>
                <div class="col-xs-7">
                    <input type="text" value="" class="form-control" id="AbNumber" placeholder="商品名称">
                </div>
            </div>
            <div class="col-xs-2 pull-right text-r">
                <button class="btn btn-hot " id="doSearch">查 询</button>
                <button class="btn btn-success" id="doRefresh">刷 新</button>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="main-body col-xs-12">
        <table class="tab" width="100%" border="0" cellspacing="0" cellpadding="0" id="List"></table>
    </div>
</div>
<script>
    $(function(){
        var ListData = jQuery.extend(true, {}, DataTable);
        ListData.init('initGoodsOrderList','#List','<?= \yii\helpers\Url::to(['goods-order/list-data'])?>',getParams());

        //点击事件
        $("#doSearch").bind('click',function(){
            ListData.reloadTable(getParams());
        });

        //刷新事件
        $("#doRefresh").bind('click',function(){
            DataTable.id = '#List';
            DataTable.drawTable();
        });

        $('.selectpicker').selectpicker({
            'width' : '110px',
        });
    });
    /*
     * 搜集搜索条件
     */
    function getParams () {
        return {
            startTime : $("#startTime").val(),
            endTime : $("#endTime").val(),
            order_number : $("#orderCode").val(),
            order_business_id : $("#AbNumber").val(),
        };
    }
    function orderDetial(orderNumber) {
        layer_show({orderNumber: orderNumber}, '商品详情', '<?= \yii\helpers\Url::to(['goods-order/get-head-detail'])?>', 1200);
    }

    function showOrder(orderNumber) {
        layer_show({orderNumber: orderNumber}, '订单详情', '<?= \yii\helpers\Url::to(['goods-order/get-head-detail'])?>', 1200);
    }

    function refundMoney(orderNumber) {
        layer_show({orderNumber: orderNumber}, '退货', '<?= \yii\helpers\Url::to(['goods-order/refund-money'])?>', 600, 400);
    }

    function sendOutAllGoods(order_number) {
        layer.confirm('是否全部发货(包含断货和等待发货,不包含退款和已发)？', {icon: 0, title: '提示'}, function (index) {
            ajaxSubmit('<?= \yii\helpers\Url::to(['goods-order/do-all-send'])?>', {orderNumber: order_number});
            layer_close(index);
            DataTable.reloadTable();
            $("#doSearch").click();
        });
    }

</script>