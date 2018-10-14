<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-4"><b>数据备份</b>Export Excel</div>
            <div class="header-search col-xs-3 pull-right text-r">
                <button class="btn btn-success " id="doRefresh">刷新</button>
            </div>
        <div class="clear"></div>
    </div>
    <div class="main-body col-xs-12">
        <table class="tab" width="100%" border="0" cellspacing="0" cellpadding="0" id="exportExcelList"></table>
    </div>
</div>
<script>
    $(function(){

        DataTable.init('initExportExcelList','#exportExcelList','<?= \yii\helpers\Url::to(['security/list','type' => \common\models\Status::MODULE_TYPE_FRANCHISEE])?>',{});
        //刷新事件
        $("#doRefresh").bind('click',function(){
            DataTable.id = '#exportExcelList';
            DataTable.drawTable();
        });
    });

    function exportExcel(id,url)
    {
        layer.confirm('确认要导出excel表吗？',function(index){
            ajaxSubmit(url, {id: id});
        });
    }

</script>