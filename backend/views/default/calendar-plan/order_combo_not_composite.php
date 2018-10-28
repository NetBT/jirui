<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-12"><b>后期列表</b>List of Not Composite</div>
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
        <table class="tab" width="100%" border="0" cellspacing="0" cellpadding="0" id="notCompositeList"></table>
    </div>
</div>
<script>
    $(function(){

        DataTable.init('initMemberOrderComboNotCompositeList','#notCompositeList','<?= \yii\helpers\Url::to(['calendar-plan/list-order','type' => \common\models\Status::MEMBER_ORDER_COMBO_NOT_COMPOSITE])?>',getParams());

        //点击事件
        $("#doSearch").bind('click',function(){
            DataTable.reloadTable(getParams());
        });
        //刷新
        $("#refresh").bind('click',function(){
            DataTable.id = '#notCompositeList';
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

    function downloadImages(comboOrderNumber){
        let url = '<?= \yii\helpers\Url::to(['member-order/download-images'])?>';
        let params = {combo_order_number: comboOrderNumber};
        ajaxSubmit(url,params,'',function (data) {
            let uri = data.data['uri'];
            if(data.code==1000 && uri){
                window.open(uri);
            }
        })
    }

    var notComposite = {
        url : '<?= \yii\helpers\Url::to(['calendar-plan/change-combo-order-status'])?>',
        designModal : function(comboOrderNumber)
        {
            var _this = this;
            layer.confirm('确定对【'+comboOrderNumber+'】订单开始精修？',function(index){
                var params = {
                    comboOrderNumber: comboOrderNumber,
                    type : <?= \common\models\Status::MEMBER_ORDER_COMBO_NOT_COMPOSITE?>,
                    beforeStatus : <?= \common\models\Status::MEMBER_ORDER_COMPOSITE_STATUS_WCL?>,
                    afterStatus : <?= \common\models\Status::MEMBER_ORDER_COMPOSITE_STATUS_JX?>,
                };
                ajaxSubmit(_this.url, params, function () {
                    DataTable.drawTable();
                    downloadImages(comboOrderNumber);
                });
            });
        },
        downloadImages : function(comboOrderNumber)
        {
            downloadImages(comboOrderNumber);
        },
        truingModal : function(comboOrderNumber)
        {
            var _this = this;
            layer.confirm('【'+comboOrderNumber+'】该订单设计？',function(index){
                var params = {
                    comboOrderNumber: comboOrderNumber,
                    type : <?= \common\models\Status::MEMBER_ORDER_COMBO_NOT_COMPOSITE?>,
                    beforeStatus : <?= \common\models\Status::MEMBER_ORDER_COMPOSITE_STATUS_JX?>,
                    afterStatus : <?= \common\models\Status::MEMBER_ORDER_COMPOSITE_STATUS_SJ?>,
                };
                ajaxSubmit(_this.url, params, function () {
                    DataTable.drawTable();
                });
            });
        },

        goBackModal : function(comboOrderNumber)
        {
            var _this = this;
            layer.confirm('【'+comboOrderNumber+'】该订单返厂处理？',function(index){
                var params = {
                    comboOrderNumber: comboOrderNumber,
                    type : <?= \common\models\Status::MEMBER_ORDER_COMBO_NOT_COMPOSITE?>,
                    beforeStatus : <?= \common\models\Status::MEMBER_ORDER_COMPOSITE_STATUS_SJ?>,
                    afterStatus : <?= \common\models\Status::MEMBER_ORDER_COMPOSITE_STATUS_YFCJ?>,
                };
                ajaxSubmit(_this.url, params, function () {
                    DataTable.drawTable();
                });
            });
        },

        doneModal : function(comboOrderNumber)
        {
            var _this = this;
            layer.confirm('【'+comboOrderNumber+'】该订单后期完成？',function(index){
                var params = {
                    comboOrderNumber: comboOrderNumber,
                    type : <?= \common\models\Status::MEMBER_ORDER_COMBO_NOT_COMPOSITE?>,
                    beforeStatus : <?= \common\models\Status::MEMBER_ORDER_COMPOSITE_STATUS_YFCJ?>,
                    afterStatus : <?= \common\models\Status::MEMBER_ORDER_COMPOSITE_STATUS_DONE?>,
                };
                ajaxSubmit(_this.url, params, function () {
                    DataTable.drawTable();
                });
            });
        },

    };
</script>