<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-12"><b>直购订单</b>Order List</div>
        <div class="header-search col-xs-12 clear-padding">

            <div class="col-xs-1" style="margin-left:25px">
                <div class="vip_box_input">
                    <a class="box" style="width: 100px">筛选条件</a>
                </div>
            </div>

            <div class="col-xs-3 input-area">
                <label class="col-xs-3 text-right">单号</label>
                <div class="col-xs-7">
                    <input type="text" value="" class="form-control" id="orderCode" placeholder="商品编号">
                </div>
            </div>

            <div class="col-xs-4 input-area">
                <label class="col-xs-3 text-right">日期</label>
                <div class="col-xs-4">
                    <input type="text" style="width: 100px" onclick="WdatePicker({readOnly: true, dateFmt: 'yyyy-MM-dd',maxDate:'#F{$dp.$D(\'endTime\',{d:-1});}'});" class="form-control" id="startTime" placeholder="开始日期">
                </div>
                <label class="col-xs-1 clear-padding text-center">至</label>
                <div class="col-xs-4">
                    <input type="text" style="width: 100px;" onclick="WdatePicker({readOnly: true, dateFmt: 'yyyy-MM-dd', minDate: '#F{$dp.$D(\'startTime\', {d:1});}'});" class="form-control" id="endTime" placeholder="结束日期">
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
        DataTable.init('initABGoodsOrderList','#List','<?= \yii\helpers\Url::to(['goods-order/ab-order-list-data'])?>',getParams());

        //点击事件
        $("#doSearch").bind('click',function(){
            DataTable.reloadTable(getParams());
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
        };
    }

    function orderDetial(orderNumber) {
        layer_show({orderNumber: orderNumber}, '', '<?= \yii\helpers\Url::to(['goods-order/get-detail'])?>', 1000);
    }

    function payOrder(orderNumber) {
        layer.confirm('是否支付此订单？', {icon: 0, title: '提示'}, function (index) {
            ajaxSubmit('<?= \yii\helpers\Url::to(['goods-order/pay-order'])?>', {orderNumber: orderNumber}, function () {
                DataTable.reloadTable(getParams());
                layer.closeAll();
            });
        });
    }

    function orderRefund(orderNumber) {
        alert('未开放');
    }

</script>