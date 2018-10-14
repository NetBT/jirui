<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-12"><b>加盟商帐变明细</b>List of Coin Change</div>
        <div class="header-search col-xs-12">
            <div class="col-xs-3 input-area">
                <label class="col-xs-5 text-right">合同编号：</label>
                <div class="col-xs-7">
                    <input type="text" value="" class="form-control" id="ABNumber" placeholder="请输入合同号">
                </div>
            </div>
            <div class="col-xs-3 input-area">
                <label class="col-xs-5 text-right">店铺名称：</label>
                <div class="col-xs-7">
                    <input type="text" value="" class="form-control" id="ABName" placeholder="请输入店铺名称">
                </div>
            </div>
            <div class="col-xs-4 input-area">
                <label class="col-xs-3 text-right">时间</label>
                <div class="col-xs-4">
                    <input type="text" style="width: 100px" value="<?= date('Y-m-d', strtotime("-7 days"))?>" onclick="WdatePicker({readOnly: true, dateFmt: 'yyyy-MM-dd',maxDate:'#F{$dp.$D(\'endTime\',{d:-1});}'});" class="form-control" id="startTime" placeholder="开始日期">
                </div>
                <label class="col-xs-1 clear-padding text-right">至</label>
                <div class="col-xs-4">
                    <input type="text" style="width: 100px" value="<?= date('Y-m-d')?>" onclick="WdatePicker({readOnly: true, dateFmt: 'yyyy-MM-dd', minDate: '#F{$dp.$D(\'startTime\', {d:1});}'});" class="form-control" id="endTime" placeholder="结束日期">
                </div>
            </div>
            <div class="col-xs-2 pull-right text-r">
                <button class="btn btn-hot " id="doSearch">查 询</button>
                <button class="btn btn-success" id="doRefresh">刷新</button>
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

        DataTable.init('initCoinChangeList','#List','<?= \yii\helpers\Url::to(['a-b/coin-change-data'])?>',getParams());
        //点击事件
        $("#doSearch").bind('click',function(){
            DataTable.reloadTable(getParams());
        });

        //刷新事件
        $("#doRefresh").bind('click',function(){
            DataTable.id = '#List';
            DataTable.drawTable();
        });
    });
    /*
     * 搜集搜索条件
     */
    function getParams () {
        return {
            ABNumber : $("#ABNumber").val(),
            ABName : $("#ABName").val(),
            startTime : $("#startTime").val(),
            endTime : $("#endTime").val(),
        };
    }
</script>