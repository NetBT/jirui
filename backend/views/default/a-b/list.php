<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-4"><b>加盟商列表</b>List of franchisees</div>
        <div class="header-search col-xs-7">
            <div class="col-xs-7 input-area">
                <label class="col-xs-5 text-right">合同号或手机号：</label>
                <div class="col-xs-7">
                    <input type="text" value="" class="form-control" id="JMSnumber" placeholder="请输入合同号或手机号">
                </div>
            </div>
            <div class="col-xs-5 pull-right text-r">
                <button class="btn btn-hot " id="doSearch">查 询</button>
                <button class="btn btn-yellow" onclick="jms_add('添加加盟商', '<?= \yii\helpers\Url::to(['a-b/add'])?>', '800', 500)">新 建</button>
                <button class="btn btn-success" id="doRefresh">刷 新</button>
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

        DataTable.init('initABList','#List','<?= \yii\helpers\Url::to(['a-b/list'])?>',getParams());

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
            number : $("#JMSnumber").val(),
        };
    }

    function jms_add(title, url, w, h) {
//        layer_show_iframe(title, url, w);
        layer_show({},title, url, '950');
    }

    function jms_edit(id) {
        layer_show({id: id}, '编辑加盟商', '<?= \yii\helpers\Url::to(['a-b/edit'])?>');
    }

    function jms_delete(id) {
        layer.confirm('是否删除？', {icon: 0, title: '提示'}, function (index) {
            ajaxSubmit('<?= \yii\helpers\Url::to(['a-b/do-delete'])?>', {id: id});
            layer_close(index);
        });
    }

    /**
     * 加盟商充值
     * @param id
     */
    function jms_recharge(id) {
        layer_show({id: id}, '加盟商充值', '<?= \yii\helpers\Url::to(['a-b/recharge'])?>', 400, 260);
    }
    /**
     * 加盟商延期
     * @param id
     */
    function jms_postpone(id) {
        layer_show({id: id}, '加盟商延期', '<?= \yii\helpers\Url::to(['a-b/postpone'])?>', 600, 360);
    }
    /**
     * 加盟商延期
     * @param id
     */
    function jms_stop(id) {
        layer.confirm('是否禁用？', {icon: 0, title: '提示'}, function (index) {
            ajaxSubmit('<?= \yii\helpers\Url::to(['a-b/toggle-status'])?>', {id: id}, function () {
                DataTable.drawTable();
                layer_close(index);
            });
        });
    }
    function jms_start(id) {
        layer.confirm('是否启用？', {icon: 0, title: '提示'}, function (index) {
            ajaxSubmit('<?= \yii\helpers\Url::to(['a-b/toggle-status'])?>', {id: id}, function () {
                DataTable.drawTable();
                layer_close(index);
            });
        });
    }
</script>