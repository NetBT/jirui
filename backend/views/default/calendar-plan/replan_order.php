<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-12"><b>未完改期列表</b>List of Replan</div>
        <div class="header-search col-xs-12 clear-padding">

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
                <button class="btn btn-success" id="doRefresh">刷 新</button>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="main-body col-xs-12">
        <table class="tab" width="100%" border="0" cellspacing="0" cellpadding="0" id="list"></table>
    </div>
</div>
<script>
    $(function(){

        DataTable.init('initReplanOrderList','#list','<?= \yii\helpers\Url::to(['calendar-plan/replan-order-data','type' => \common\models\Status::MEMBER_ORDER_COMBO_NOT_SHOOT_FINISHED])?>',getParams());

        //点击事件
        $("#doSearch").bind('click',function(){
            DataTable.reloadTable(getParams());
        });
        //刷新
        $("#doRefresh").bind('click',function(){
            DataTable.id = '#notShootList';
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

    function replan(orderNumber) {
        layer.confirm("是否重新排项？", {icon: 1}, function() {
            parent.creatIframe('<?= \yii\helpers\Url::to(['calendar-plan/list'])?>' + "?orderNumber=" + orderNumber, '月视图');

        });
    }

    var notShoot = {
        planUrl: '<?= \yii\helpers\Url::to(['calendar-plan/list'])?>',
        endShootUrl: '<?= \yii\helpers\Url::to(['calendar-plan/change-combo-order-status'])?>',
        endShootModal: function (comboOrderNumber) {
            var _this = this;
            layer.confirm('【' + comboOrderNumber + '】订单拍摄完成？', function (index) {
                var params = {
                    comboOrderNumber: comboOrderNumber,
                    type: <?= \common\models\Status::MEMBER_ORDER_COMBO_NOT_SHOOT?>,
                    beforeStatus: <?= \common\models\Status::MEMBER_ORDER_SHOOT_STATUS_ING?>,
                    afterStatus: <?= \common\models\Status::MEMBER_ORDER_SHOOT_STATUS_YES?>,
                };
                ajaxSubmit(_this.endShootUrl, params, function () {
                    DataTable.drawTable();
                });
            });
        },
    }
</script>