<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-3"><b>广告列表</b>Advert List</div>
        <div class="header-search col-xs-8">
            <div class="col-xs-6 input-area">
                <label class="col-xs-3 text-right">广告名称</label>
                <div class="col-xs-9">
                    <input type="text" value="" class="form-control" id="advertName" placeholder="输入广告名称">
                </div>
            </div>
            <div class="col-xs-4 pull-right text-r">
                <button class="btn btn-hot " id="doSearch">查 询</button>
                <button class="btn btn-yellow" onclick="advert_add()">新 建</button>
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
        DataTable.init('initAdvertList','#List','<?= \yii\helpers\Url::to(['advert/list-data'])?>',getParams());

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
            'width' : '110px',
        });
    });
    /*
     * 搜集搜索条件
     */
    function getParams () {
        return {
            advert_name : $("#advertName").val(),
        };
    }

    function advert_add() {
        layer_show({},'添加广告', '<?= \yii\helpers\Url::to(['advert/add'])?>');
    }

    function advert_edit(id) {
        layer_show({id: id}, '编辑广告', '<?= \yii\helpers\Url::to(['advert/edit'])?>');
    }

    function advert_delete(id) {
        layer.confirm('是否删除？', {icon: 0, title: '提示'}, function (index) {
            ajaxSubmit('<?= \yii\helpers\Url::to(['advert/do-delete'])?>', {id: id}, function () {
                layer_close(index);
                DataTable.drawTable();
            });
        });
    }
    /**
     * 加盟商延期
     * @param id
     */
    function advert_stop(id) {
        layer.confirm('是否禁用？', {icon: 0, title: '提示'}, function (index) {
            ajaxSubmit('<?= \yii\helpers\Url::to(['advert/toggle-status'])?>', {id: id}, function () {
                layer_close(index);
                DataTable.drawTable();
            });
        });
    }
    function advert_start(id) {
        layer.confirm('是否启用？', {icon: 0, title: '提示'}, function (index) {
            ajaxSubmit('<?= \yii\helpers\Url::to(['advert/toggle-status'])?>', {id: id}, function () {
                DataTable.drawTable();
                layer_close(index);
            });
        });
    }
    /**
     * 充值
     * @param id
     */
    function advert_recharge(id) {
        layer_show({id: id}, '广告充值', '<?= \yii\helpers\Url::to(['advert/recharge'])?>', 400, 260);
    }
    /**
     * 延期
     * @param id
     */
    function advert_postpone(id) {
        layer_show({id: id}, '广告延期', '<?= \yii\helpers\Url::to(['advert/postpone'])?>', 600, 290);
    }
</script>