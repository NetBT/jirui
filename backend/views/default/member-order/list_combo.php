<article class="page-container col-xs-12">
    <div class="header-search col-xs-3 text-l">
        <button class="btn btn-success " id="doRefreshCombo">刷新</button>
    </div>
    <div class="main-body col-xs-12">
        <table class="tab" width="100%" border="0" cellspacing="0" cellpadding="0" id="memberOrderComboList"></table>
    </div>
</article>
<script>
    $(function(){

        DataTable.init('initMemberOrderComboList','#memberOrderComboList','<?= \yii\helpers\Url::to(['member-order/list-order-combo'])?>',getParamsCombo());
        //刷新
        $("#doRefreshCombo").bind('click',function(){
            DataTable.id = '#memberOrderComboList';
            DataTable.drawTable();
        });
    });
    /*
     * 搜集搜索条件
     */
    function getParamsCombo () {
        return {
            orderNumber : $('#orderNumber').val(),
        }
    }

</script>