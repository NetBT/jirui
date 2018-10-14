<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-12"><b>会员订单列表</b>List of Member Order</div>
        <div class="header-search col-xs-12 clear-padding">

            <div class="col-xs-1" style="margin-left:25px">
                <div class="vip_box_input">
                    <a class="box" style="width: 100px">筛选条件</a>
                </div>
            </div>

            <input type="hidden" id="orderNumber" value="">
            <div class="col-xs-2 input-area">
                <label class="col-xs-4 text-right">会员</label>
                <div class="col-xs-8">
                    <input type="text" style="width: 95px" class="form-control" id="searchMemberName" placeholder="会员姓名">
                </div>
            </div>

            <div class="col-xs-2 input-area">
                <label class="col-xs-4 text-right">单号</label>
                <div class="col-xs-8">
                    <input type="text" style="width: 140px" value="" class="form-control  clear-padding" id="searchOrderNum" placeholder="请输入订单编号">
                </div>
            </div>

            <!--            <div class="col-xs-3 input-area">-->
            <!--                <label class="col-xs-4 text-right">订单类型</label>-->
            <!--                <div class="col-xs-8">-->
            <!--                    <select class="selectpicker bs-select-hidden" id="searchOrderType" aria-required="true">-->
            <!--                    </select>-->
            <!--                </div>-->
            <!--            </div>-->

            <div class="col-xs-4 input-area">
                <label class="col-xs-3 text-right">订单日期</label>
                <div class="col-xs-4">
                    <input type="text" style="width: 100px" onclick="WdatePicker({readOnly: true, dateFmt: 'yyyy-MM-dd',maxDate:'#F{$dp.$D(\'endTime\',{d:-1});}'});" class="form-control" id="startTime" placeholder="开始日期">
                </div>
                <label class="col-xs-1 clear-padding text-right">至</label>
                <div class="col-xs-4">
                    <input type="text" style="width: 100px" onclick="WdatePicker({readOnly: true, dateFmt: 'yyyy-MM-dd', minDate: '#F{$dp.$D(\'startTime\', {d:1});}'});" class="form-control" id="endTime" placeholder="结束日期">
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
        <table class="tab" width="100%" border="0" cellspacing="0" cellpadding="0" id="memberOrderLog"></table>
    </div>
</div>
<script>
    $(function(){

        DataTable.init('initMemberOrderLog','#memberOrderLog','<?= \yii\helpers\Url::to(['member-order/list'])?>',getParams());

        //点击事件
        $("#doSearch").bind('click',function(){
            DataTable.reloadTable(getParams());
        });

        //刷新事件
        $("#doRefresh").bind('click',function(){
            DataTable.id = '#memberOrderLog';
            DataTable.drawTable();
        });

        $("#searchOrderType").selectpicker({
            title: '选择订单状态',
            style: 'btn-default',
            width: '120',
            liveSearch: true
        });

    });
    /*
     * 搜集搜索条件
     */
    function getParams () {
        return {
            memberName : $('#searchMemberName').val(),
            orderNumber : $('#searchOrderNum').val(),
            orderType : $('#searchOrderType').val(),
            startTime : $('#startTime').val(),
            endTime : $('#endTime').val(),
        }
    }

    var memberOrder = {
        addUrl : '<?= \yii\helpers\Url::to(['member-order/add']) ?>',
        editUrl : '<?= \yii\helpers\Url::to(['member-order/edit']) ?>',
        refundUrl : '<?= \yii\helpers\Url::to(['member-order/refund']) ?>',
        secondUrl : '<?= \yii\helpers\Url::to(['member-order/second']) ?>',
        planComboUrl : '<?= \yii\helpers\Url::to(['member-order/index-order-combo']) ?>',
        planUrl : '<?= \yii\helpers\Url::to(['calendar-plan/list'])?>',
        addModal : function()
        {
            layer_show({}, '添加订单', this.addUrl,780,500);
        },

        editModal : function(id)
        {
            var param = {
                id : id,
            };
            layer_show(param, '编辑订单', this.editUrl,780,500);
        },

        refundModal : function(id)
        {
            var param = {
                id : id,
            };
            layer_show(param, '退款', this.refundUrl,780,500);
        },

        secondModal : function(id)
        {
            var param = {
                id : id,
            };
            layer_show(param, '二销售款', this.secondUrl,780,500);
        },

        planComboModal : function(orderNumber)
        {
            var param = {
                orderNumber : orderNumber,
            };
            $("#orderNumber").val(orderNumber);
            layer_show(param, '订单排项', this.planComboUrl,1000);
        },

        planModal : function(orderNumber)
        {
            layer_show_full('添加排项', this.planUrl + "?orderNumber=" + orderNumber);
        },

    };
</script>