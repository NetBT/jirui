<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-12"><b>选片列表</b>List of Not Select</div>
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
                <button class="btn btn-success" id="refresh">刷 新</button>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="main-body col-xs-12">
        <table class="tab" width="100%" border="0" cellspacing="0" cellpadding="0" id="notSelectList"></table>
    </div>
</div>
<script>
    $(function () {

        DataTable.init('initMemberOrderComboNotSelectList', '#notSelectList', '<?= \yii\helpers\Url::to([
            'calendar-plan/list-order',
            'type' => \common\models\Status::MEMBER_ORDER_COMBO_NOT_SELECT
        ])?>', getParams());

        //点击事件
        $("#doSearch").bind('click', function () {
            DataTable.reloadTable(getParams());
        });
        //刷新
        $("#refresh").bind('click', function () {
            DataTable.id = '#notSelectList';
            DataTable.drawTable();
        });
    });

    /*
     * 搜集搜索条件
     */
    function getParams() {
        return {
            orderComboNumber: $('#searchOrderComboNum').val(),
            memberName: $('#searchMemberName').val(),
        }
    }

    var notSelect = {
        startSelectUrl: '<?= \yii\helpers\Url::to(['calendar-plan/change-combo-order-status'])?>',
        endSelectUrl: '<?= \yii\helpers\Url::to(['calendar-plan/change-combo-order-status'])?>',
        secondUrl: '<?= \yii\helpers\Url::to(['member-order/second']) ?>',
        startSelectModal: function (comboOrderNumber) {
            var _this = this;
            layer.confirm('确定对【' + comboOrderNumber + '】订单开始选片？', function (index) {
                var params = {
                    comboOrderNumber: comboOrderNumber,
                    type: <?= \common\models\Status::MEMBER_ORDER_COMBO_NOT_SELECT?>,
                    beforeStatus: <?= \common\models\Status::MEMBER_ORDER_SELECT_STATUS_NO?>,
                    afterStatus: <?= \common\models\Status::MEMBER_ORDER_SELECT_STATUS_ING?>,
                };
                ajaxSubmit(_this.startSelectUrl, params, function () {
                    creatIframe('<?= \yii\helpers\Url::to(['membber-order/select'])?>?member_order_number=' + comboOrderNumber,'选片');
                });
            });
        },
        continueSelectModal: function (comboOrderNumber) {
            creatIframe('<?= \yii\helpers\Url::to(['member-order/select'])?>?combo_order_number=' + comboOrderNumber,'选片');
        },
        endSelectModal: function (comboOrderNumber) {
            var _this = this;
            layer.confirm('【' + comboOrderNumber + '】该订单选片完成？', function (index) {
                var params = {
                    comboOrderNumber: comboOrderNumber,
                    type: <?= \common\models\Status::MEMBER_ORDER_COMBO_NOT_SELECT?>,
                    beforeStatus: <?= \common\models\Status::MEMBER_ORDER_SELECT_STATUS_ING?>,
                    afterStatus: <?= \common\models\Status::MEMBER_ORDER_SELECT_STATUS_YES?>,
                };
                ajaxSubmit(_this.startSelectUrl, params, function () {
                    DataTable.drawTable();
                });
            });
        },
        secondModal: function (order_number) {
            var param = {
                order_number: order_number,
            };
            layer_show(param, '二销售款', this.secondUrl, 780, 500);
        },
    };
</script>