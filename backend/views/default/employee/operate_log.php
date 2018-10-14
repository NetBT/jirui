<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-12"><b>操作日志</b>Operate Log</div>
        <div class="header-search col-xs-12">
            <div class="col-xs-3 input-area">
                <label class="col-xs-3">操作者</label>
                <div class="col-xs-7 input-box">
                    <input type="text" value="" class="form-control" id="operateUserId" placeholder="输入操作者姓名">
                </div>
            </div>
            <div class="col-xs-6 input-area">
                <label class="col-xs-2">操作时间</label>
                <div class="col-xs-4 input-box">
                    <input type="text" value="" class="form-control" id="startTime" placeholder="开始时间">
                </div>
                <label class="col-xs-1">至</label>
                <div class="col-xs-4 input-box">
                    <input type="text" value="" class="form-control" id="endTime" placeholder="结束时间">
                </div>
            </div>
            <div class="col-xs-2 pull-right text-r">
                <button class="btn btn-hot " id="doSearch">查 询</button>
                <button class="btn btn-success" id="doRefresh">刷 新</button>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div style="" class="main-body col-xs-12">
        <table class="tab" width="100%" border="0" cellspacing="0" cellpadding="0" id="operateLogList">

        </table>
    </div>
</div>
<script>
    $(function(){

        DataTable.init('initOperateLogList','#operateLogList','<?= \yii\helpers\Url::to(['employee/list-operate-log'])?>',getParams());

        //点击事件
        $("#doSearch").bind('click',function(){
            DataTable.reloadTable(getParams());
        });

        //刷新事件
        $("#doRefresh").bind('click',function(){
            DataTable.id = '#operateLogList';
            DataTable.drawTable();
        });

        $("#startTime").on('click', function() {
            WdatePicker({readOnly: true, dateFmt: 'yyyy-MM-dd HH:mm:ss'});
        });
        $("#endTime").on('click', function() {
            WdatePicker({readOnly: true, dateFmt: 'yyyy-MM-dd HH:mm:ss', minDate: '#F{$dp.$D(\'startTime\')}'});
        });
    });
    /*
     * 搜集搜索条件
     */
    function getParams () {
        return {
            operateUserId : $("#operateUserId").val(),
            startTime : $("#startTime").val(),
            endTime : $("#endTime").val()
        };
    }
</script>