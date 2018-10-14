<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-12"><b>会员提成列表</b>List of Member Rate</div>
        <div class="header-search col-xs-12 clear-padding">

            <div class="col-xs-1" style="margin-left:25px">
                <div class="vip_box_input">
                    <a class="box" style="width: 100px">筛选条件</a>
                </div>
            </div>

            <div class="col-xs-2 input-area">
                <label class="col-xs-4 text-right">员工</label>
                <div class="col-xs-7">
                    <input type="text" style="width: 95px" class="form-control" id="searchEmployeeName" placeholder="请输入姓名">
                </div>
            </div>

            <div class="col-xs-2 input-area">
                <label class="col-xs-4 text-right">类型</label>
                <div class="col-xs-5">
                    <select class="selectpicker" data-width="100" id="searchRateType" aria-required="true">
                        <?php foreach (\common\models\Status::employeeRateTypeMap() as $k => $v) :?>
                            <option value="<?= $k?>"><?= $v?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>

            <div class="col-xs-4 input-area">
                <label class="col-xs-3 text-right">提成日期</label>
                <div class="col-xs-4">
                    <input type="text" style="width: 90px" onclick="WdatePicker({readOnly: true, dateFmt: 'yyyy-MM-dd',maxDate:'#F{$dp.$D(\'endTime\',{d:-1});}'});" class="form-control" id="startTime" placeholder="开始日期">
                </div>
                <label class="col-xs-1 clear-padding text-center">至</label>
                <div class="col-xs-4">
                    <input type="text" style="width: 90px" onclick="WdatePicker({readOnly: true, dateFmt: 'yyyy-MM-dd', minDate: '#F{$dp.$D(\'startTime\', {d:1});}'});" class="form-control" id="endTime" placeholder="结束日期">
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
        <table class="tab" width="100%" border="0" cellspacing="0" cellpadding="0" id="employeeRateLog"></table>
    </div>
</div>
<script>
    $(function(){

        $("#searchRateType").selectpicker({
            title: '选择类型',
            style: 'btn-default',
            width: '100px',
            liveSearch: true
        });

        DataTable.init('initEmployeeRateLog','#employeeRateLog','<?= \yii\helpers\Url::to(['employee/list-rate'])?>',getParams());

        //点击事件
        $("#doSearch").bind('click',function(){
            DataTable.reloadTable(getParams());
        });

        //刷新事件
        $("#doRefresh").bind('click',function(){
            DataTable.id = '#employeeRateLog';
            DataTable.drawTable();
        });

    });
    /*
     * 搜集搜索条件
     */
    function getParams () {
        return {
            employeeName : $('#searchEmployeeName').val(),
            rateType : $("#searchRateType").selectpicker('val'),
            startTime : $('#startTime').val(),
            endTime : $('#endTime').val(),
        }
    }
</script>