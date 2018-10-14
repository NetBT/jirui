<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-12"><b>员工列表</b>List of staff</div>
        <div class="header-search col-xs-12 clear-padding">

            <div class="col-xs-1" style="margin-left:25px">
                <div class="vip_box_input">
                    <a class="box" style="width: 100px">筛选条件</a>
                </div>
            </div>

            <div class="col-xs-6 input-area">
                <label class="col-xs-5 text-right">手机号</label>
                <div class="col-xs-7">
                    <input type="text" value="" class="form-control" id="searchTel" placeholder="请输入手机号">
                </div>
            </div>

            <div class="col-xs-4 pull-right text-r">
                <button class="btn btn-hot " id="doSearch">查 询</button>
                <button class="btn btn-yellow" onclick="employeeList.abEmployeeAddModal();">新 建</button>
                <button class="btn btn-success" id="doRefresh">刷 新</button>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="main-body col-xs-12">
        <table class="tab" width="100%" border="0" cellspacing="0" cellpadding="0" id="abEmployeeList"></table>
    </div>
</div>
<script>
    $(function(){
        DataTable.init('initEmployeeList','#abEmployeeList','<?= \yii\helpers\Url::to(['employee/list-employee'])?>',getParams());
        //点击事件
        $("#doSearch").bind('click',function(){
            DataTable.reloadTable(getParams());
        });

        //刷新事件
        $("#doRefresh").bind('click',function(){
            DataTable.id = '#abEmployeeList';
            DataTable.drawTable();
        });
    });
    /*
     * 搜集搜索条件
     */
    function getParams () {
        return {tel : $("#searchTel").val()};
    }

    var employeeList = {
        employeeUpdateStatusUrl : '<?= \yii\helpers\Url::to(['employee/update-status'])?>',
        employeeUpdatePsdUrl : '<?= \yii\helpers\Url::to(['employee/update-password'])?>',
        employeeEditUrl : '<?= \yii\helpers\Url::to(['employee/edit-employee'])?>',
        employeeAddUrl : '<?= \yii\helpers\Url::to(['employee/add-employee'])?>',
        abEmployeeAddUrl : '<?= \yii\helpers\Url::to(['employee/add-a-b-employee'])?>',
        employeeWorkingStatus : '<?= \yii\helpers\Url::to(['employee/working-status'])?>',
        employeeStopOrStart : function(obj,id){
            var statusVal = $(obj).attr('title');
            var reStatusVal = '';
            switch(statusVal)
            {
                case '启用':
                    reStatusVal = '禁用';
                    break;
                case '禁用':
                    reStatusVal = '启用';
                    break;
            }

            var _this = this;
            layer.confirm('确认要'+statusVal+'吗？',function(index){
                Common.tabStatus(id, obj, _this.employeeUpdateStatusUrl, reStatusVal, statusVal);
            });
        },
        employeeUpdatePsdModal : function(id)
        {
            var param = {
                id : id,
            };
            layer_show(param, '修改密码', this.employeeUpdatePsdUrl, 550, 290);
        },
        employeeAddModal  : function()
        {
            layer_show({}, '添加员工', this.employeeAddUrl);
        },

        abEmployeeAddModal  : function()
        {
            layer_show_iframe('添加员工', this.abEmployeeAddUrl,1000,550);
        },

        employeeEditModal : function(id)
        {
            var param = {
                id : id,
            };
            layer_show(param, '编辑员工', this.employeeEditUrl);
        },

        workingStatusModal : function(id)
        {
            var _this = this;
            layer.confirm('确认要离职吗？离职后不可恢复',function(index){
                ajaxSubmit( _this.employeeWorkingStatus,{id:id},function(){
                    DataTable.id = '#abEmployeeList';
                    DataTable.drawTable();
                });
            });
        }
    };
</script>