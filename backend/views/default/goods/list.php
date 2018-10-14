<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-12"><b>商品列表</b>List of Goods</div>
        <div class="header-search col-xs-12">
            <div class="col-xs-4 input-area">
                <label class="col-xs-3 text-right">创建日期</label>
                <div class="col-xs-4">
                    <input type="text" value="" onclick="WdatePicker({readOnly: true, dateFmt: 'yyyy-MM-dd',maxDate:'#F{$dp.$D(\'endTime\',{d:-1});}'});" class="form-control" id="startTime" placeholder="开始日期">
                </div>
                <label class="col-xs-1 clear-padding text-center">至</label>
                <div class="col-xs-4">
                    <input type="text" value="" onclick="WdatePicker({readOnly: true, dateFmt: 'yyyy-MM-dd', minDate: '#F{$dp.$D(\'startTime\', {d:1});}'});" class="form-control" id="endTime" placeholder="结束日期">
                </div>
            </div>
            <div class="col-xs-3 input-area">
                <label class="col-xs-3 text-right">商品编号</label>
                <div class="col-xs-7">
                    <input type="text" value="" class="form-control" id="goodsCode" placeholder="商品编号">
                </div>
            </div>
            <div class="col-xs-3 input-area">
                <label class="col-xs-3 text-right">商品名称</label>
                <div class="col-xs-7">
                    <input type="text" value="" class="form-control" id="goodsName" placeholder="商品名称">
                </div>
            </div>
            <div class="col-xs-2 pull-right text-r clear-padding clear-margin">
                <button class="btn btn-hot " id="doSearch">查 询</button>
                <button class="btn btn-yellow" onclick="goods_add()">新 建</button>
                <button class="btn btn-success clear-padding clear-margin" id="doRefresh">刷新</button>
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
        DataTable.init('initGoodsList','#List','<?= \yii\helpers\Url::to(['goods/list-data'])?>',getParams());

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
            startTime : $("#startTime").val(),
            endTime : $("#endTime").val(),
            goods_code : $("#goodsCode").val(),
            goods_name : $("#goodsName").val(),
        };
    }

    function goods_add() {
        layer_show_full('新增商品', '<?= \yii\helpers\Url::to(['goods/add'])?>');
    }

    function goods_edit(id) {
        layer_show_full('编辑商品', '<?= \yii\helpers\Url::to(['goods/edit'])?>' + '?id=' + id);
    }

    function goods_delete(id) {
        layer.confirm('是否删除？', {icon: 0, title: '提示'}, function (index) {
            ajaxSubmit('<?= \yii\helpers\Url::to(['goods/do-delete'])?>', {id: id}, function () {
                console.log(arguments);
                DataTable.reloadTable(getParams());
            });
            layer_close(index);
        });
    }

    function goods_shelf(id, msg) {
        layer.confirm(msg, {icon: 0, title: '提示'}, function (index) {
            ajaxSubmit('<?= \yii\helpers\Url::to(['goods/do-shelf'])?>', {goodsId: id}, function () {
                DataTable.reloadTable(getParams());
            });
            layer_close(index);
        });
    }

    function goods_show_image(id) {
        layer_show({id: id}, '', '<?= \yii\helpers\Url::to(['goods-images/edit'])?>');
    }
</script>