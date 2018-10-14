<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-3"><b>员工列表</b>List of staff</div>
        <div class="header-search col-xs-8">
            <div class="col-xs-8 input-area">
                <label class="col-xs-5 text-right">手机号</label>
                <div class="col-xs-7">
                    <input type="text" value="" class="form-control" id="searchTel" placeholder="请输入手机号">
                </div>
            </div>
            <div class="col-xs-4 pull-right text-r">
                <button class="btn btn-hot " id="doSearch">查 询</button>
                <button class="btn btn-yellow" onclick="employeeList.employeeAddModal();">新 建</button>
                <button class="btn btn-success" id="doRefresh">刷 新</button>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="main-body col-xs-12">
        <table class="tab" width="100%" border="0" cellspacing="0" cellpadding="0" id="employeeList"></table>
    </div>
</div>
<script>
    $(function(){
        DataTable.init('initEmployeeList','#employeeList','<?= \yii\helpers\Url::to(['employee/list-employee'])?>',getParams());
        //点击事件
        $("#doSearch").bind('click',function(){
            DataTable.reloadTable(getParams());
        });

        //刷新事件
        $("#doRefresh").bind('click',function(){
            DataTable.id = '#employeeList';
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

        employeeEditModal : function(id)
        {
            var param = {
                id : id,
            };
            layer_show(param, '编辑员工', this.employeeEditUrl);
        },
    };
</script>