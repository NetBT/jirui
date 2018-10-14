<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-12"><b>全部列表</b>List of Not Select</div>
        <div class="header-search col-xs-12 claer-padding">
            <div class="col-xs-1" style="margin-left:25px">
                <div class="vip_box_input">
                    <a class="box" style="width: 100px">筛选条件</a>
                </div>
            </div>
            <div class="col-xs-3 input-area">
                <label class="col-xs-4 text-right">订单编号</label>
                <div class="col-xs-8">
                    <input type="text" value="" class="form-control" id="searchOrderComboNum" placeholder="请输入订单编号">
                </div>
            </div>

            <div class="col-xs-3 input-area">
                <label class="col-xs-4 text-right">宝宝姓名</label>
                <div class="col-xs-8">
                    <input type="text" value="" class="form-control" id="searchMemberName" placeholder="请输入宝宝姓名">
                </div>
            </div>

            <div class="col-xs-3 pull-right text-r">
                <button class="btn btn-hot " id="doSearch">查 询</button>
                <button class="btn btn-success" id="refresh">刷 新</button>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="main-body col-xs-12">
        <table class="tab" width="100%" border="0" cellspacing="0" cellpadding="0" id="allOrderComboList"></table>
    </div>
</div>
<script>
    $(function(){

        DataTable.init('initMemberOrderComboAllList','#allOrderComboList','<?= \yii\helpers\Url::to(['calendar-plan/list-order'])?>',getParams());
        //点击事件
        $("#doSearch").bind('click',function(){
            DataTable.reloadTable(getParams());
        });
        //刷新
        $("#refresh").bind('click',function(){
            DataTable.id = '#allOrderComboList';
            DataTable.drawTable();
        });
    });
    /*
     * 搜集搜索条件
     */
    function getParams () {
        return {
            orderComboNumber : $('#searchOrderComboNum').val(),
            memberName : $('#searchMemberName').val(),
        }
    }

    var memberComboOrder = {
        planUrl : '<?= \yii\helpers\Url::to(['calendar-plan/list'])?>',
        showOrderComboUrl : '<?= \yii\helpers\Url::to(['member-order/show-order-combo'])?>',
        planModal : function(orderNumber)
        {
            parent.creatIframe(this.planUrl + "?orderNumber=" + orderNumber, '月视图');
//            layer_show_full('添加排项', this.planUrl + "?orderNumber=" + orderNumber);
        },
        showOrderComboModal : function(comboOrderNumber)
        {
            creatIframe(this.showOrderComboUrl+'?comboOrderNumber='+comboOrderNumber,'订单详情');
        }

    };


</script>