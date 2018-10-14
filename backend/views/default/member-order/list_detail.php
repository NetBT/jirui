<article class="page-container col-xs-12">
    <div class="main-body col-xs-12">
        <table class="tab" width="100%" border="0" cellspacing="0" cellpadding="0" id="memberOrderDetailList"></table>
    </div>
</article>
<script>
    $(function(){

        DataTable.init('initMemberOrderDetailList','#memberOrderDetailList','<?= \yii\helpers\Url::to(['member-order/list-order-detail'])?>',getParamsDetail());

    });
    /*
     * 搜集搜索条件
     */
    function getParamsDetail () {
        return {
            orderComboNumber : $('#orderComboNumber').val(),
        }
    }
    var orderDetail = {
        url : '<?= \yii\helpers\Url::to(['member-order/change-order-detail-status'])?>',
        goBackModal : function(id)
        {
            var _this = this;
            layer.confirm('确定对该商品进行返厂处理？',function(index){
                var params = {
                    id: id,
                    type : <?= \common\models\Status::MEMBER_ORDER_DETAIL_DEAL_STATUS_FC?>,
                    beforeStatus : <?= \common\models\Status::MEMBER_ORDER_DETAIL_DEAL_STATUS_WCL?>,
                    afterStatus : <?= \common\models\Status::MEMBER_ORDER_DETAIL_DEAL_STATUS_FC?>,
                };
                ajaxSubmit(_this.url, params, function () {
                    DataTable.drawTable();
                });
            });
        },
        doneModal : function(id)
        {
            var _this = this;
            layer.confirm('该商品理件完成？',function(index){
                var params = {
                    id: id,
                    type : <?= \common\models\Status::MEMBER_ORDER_DETAIL_DEAL_STATUS_WC?>,
                    afterStatus : <?= \common\models\Status::MEMBER_ORDER_DETAIL_DEAL_STATUS_WC?>,
                };
                ajaxSubmit(_this.url, params, function () {
                    DataTable.drawTable();
                });
            });
        },

    };
</script>