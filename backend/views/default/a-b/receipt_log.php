<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-12"><b>收款日志</b>Receipt Log</div>
        <div class="header-search col-xs-12">
            <div class="col-xs-3 input-area">
                <label class="col-xs-3">收款人</label>
                <div class="col-xs-7 input-box">
                    <select class="selectpicker form-control" data-live-search="true" name="receiptUser" id="receiptUser" data-title="收款人">
                        <?php
                            $employeeList = \backend\models\Employee::getHeadquartersEmployeeMap();
                            foreach ($employeeList as $k => $v) :
                        ?>
                        <option value="<?= $k?>"><?= $v?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>
            <div class="col-xs-3 input-area">
                <label class="col-xs-3">合同编号</label>
                <div class="col-xs-7 input-box">
                    <input type="text" value="" class="form-control" id="AbNumber" placeholder="请输入合同编号">
                </div>
            </div>
            <div class="col-xs-3 input-area">
                <label class="col-xs-3">店铺名称</label>
                <div class="col-xs-7 input-box">
                    <input type="text" value="" class="form-control" id="AbName" placeholder="请输入店铺名称">
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
        <table class="tab" width="100%" border="0" cellspacing="0" cellpadding="0" id="List">

        </table>
    </div>
</div>
<script>
    $(function(){

        DataTable.init('initAbReceiptLog','#List','<?= \yii\helpers\Url::to(['a-b/receipt-log-data'])?>',getParams());

        //点击事件
        $("#doSearch").bind('click',function(){
            DataTable.reloadTable(getParams());
        });

        //刷新事件
        $("#doRefresh").bind('click',function(){
            DataTable.id = '#List';
            DataTable.drawTable();
        });

        $('.selectpicker').selectpicker({
            title: '选择收款人',
            width : '100%',
            style: 'btn-default'
        });
    });
    /*
     * 搜集搜索条件
     */
    function getParams () {
        return {
            receiptUser : $("#receiptUser").selectpicker('val'),
            AbNumber : $("#AbNumber").val(),
            AbName : $("#AbName").val()
        };
    }
</script>