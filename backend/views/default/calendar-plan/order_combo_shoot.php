<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-12"><b>拍摄完成列表</b>List of Not Shoot</div>
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
        <table class="tab" width="100%" border="0" cellspacing="0" cellpadding="0" id="ShootList"></table>
    </div>
</div>
<script>
    $(function(){

        DataTable.init('initMemberOrderComboShootList','#ShootList','<?= \yii\helpers\Url::to(['calendar-plan/list-order','type' => \common\models\Status::MEMBER_ORDER_COMBO_NOT_SELECT])?>',getParams());

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

    var notShoot = {
        planUrl : '<?= \yii\helpers\Url::to(['calendar-plan/list'])?>',
        startShootUrl : '<?= \yii\helpers\Url::to(['calendar-plan/change-combo-order-status'])?>',
        endShootUrl : '<?= \yii\helpers\Url::to(['calendar-plan/change-combo-order-status'])?>',
        startShootModal : function(comboOrderNumber)
        {
            var _this = this;
            layer.confirm('确定对【'+comboOrderNumber+'】订单开始拍摄？',function(index){
                var params = {
                    comboOrderNumber: comboOrderNumber,
                    type : <?= \common\models\Status::MEMBER_ORDER_COMBO_NOT_SHOOT?>,
                    beforeStatus : <?= \common\models\Status::MEMBER_ORDER_SHOOT_STATUS_NO?>,
                    afterStatus : <?= \common\models\Status::MEMBER_ORDER_SHOOT_STATUS_ING?>,
                };
                ajaxSubmit(_this.startShootUrl, params, function () {
                    DataTable.drawTable();
                });
            });
        },
        endShootModal : function(comboOrderNumber)
        {
            var _this = this;
            layer.confirm('【'+comboOrderNumber+'】该订单拍摄完成？',function(index){
                var params = {
                    comboOrderNumber: comboOrderNumber,
                    type : <?= \common\models\Status::MEMBER_ORDER_COMBO_NOT_SHOOT?>,
                    beforeStatus : <?= \common\models\Status::MEMBER_ORDER_SHOOT_STATUS_ING?>,
                    afterStatus : <?= \common\models\Status::MEMBER_ORDER_SHOOT_STATUS_YES?>,
                };
                ajaxSubmit(_this.endShootUrl, params, function () {
                    DataTable.drawTable();
                });
            });
        },
        planModal : function(orderNumber)
        {
            layer_show_full('添加排项', this.planUrl + "?orderNumber=" + orderNumber);
        },

        /**
         * 标记为未拍完
         * @param orderNumber
         */
        notFinished: function (orderNumber) {
            layer.confirm("未拍完？是否标记为'未拍完'?", {icon: 1}, function() {
                $.ajax({
                    url: "<?= \yii\helpers\Url::to(['calendar-plan/do-replan'])?>",
                    type: "post",
                    data: {orderNumber: orderNumber},
                    beforeSend: function () {
                        if (orderNumber.length <= 0) {
                            layer.msg('订单信息错误', {icon: 2});
                            return false;
                        }
                    },
                    success: function(result) {
                        if (result.code == 1000) {
                            layer.msg('标记成功', {icon: 1});
                            DataTable.reloadTable(getParams());
                        } else {
                            layer.msg(result.message, {icon: 2})
                        }
                    },
                    error: function() {
                        layer.msg('网络错误', {icon: 2});
                    }
                });
            });
        }

    };
</script>