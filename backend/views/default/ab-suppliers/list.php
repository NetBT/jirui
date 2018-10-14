<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-7"><b>加盟商列表</b>List of Suppliers</div>
        <div class="header-search col-xs-4 pull-right">
            <div class="col-xs-12 pull-right text-r">
                <button class="btn btn-hot " id="doSearch">刷新</button>
                <button class="btn btn-yellow " onclick="add()">添加</button>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="main-body col-xs-12">
        <table class="tab" width="100%" border="0" cellspacing="0" cellpadding="0" id="list"></table>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function()
    {
        DataTable.init('initABSuppliers', '#list', '<?= \yii\helpers\Url::to(['ab-suppliers/list-data']);?>', getParam());

        //点击事件
        $("#doSearch").bind('click',function(){
            DataTable.reloadTable();
        });
    });

    function getParam() {
        return {};
    }

    function add() {
        layer_show({}, '添加供应商', '<?= \yii\helpers\Url::to(['ab-suppliers/add'])?>', 600, 400);
    }
    /**
     * 编辑供应商
     */
    function edit(id) {
        layer_show({id: id}, '编辑公告', '<?= \yii\helpers\Url::to(['ab-suppliers/edit'])?>', 600, 400);
    }

    function doDelete(id) {
        layer.confirm('是否删除该加盟商？', {icon: 0, title: '提示'}, function (index) {
            ajaxSubmit('<?= \yii\helpers\Url::to(['ab-suppliers/do-delete'])?>', {id: id}, function () {
                DataTable.reloadTable();
            });
        });
    }
</script>
