<?php \backend\assets\JquerySuperSlideAsset::register($this);?>
<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-7"><b>商品列表</b>List of Goods</div>
        <div class="header-search col-xs-4 pull-right text-r" style="padding-right: 30px;">
            <button class="btn btn-success btn-xs" onclick="ab_goods_stock_in()">入 库</button>
            <button class="btn btn-success btn-xs" onclick="ab_goods_stock_out()">出 库</button>
        </div>
        <div class="header-search col-xs-12">
            <div class="col-xs-4 input-area">
                <label class="col-xs-3 text-right">创建日期</label>
                <div class="col-xs-4">
                    <input type="text" style="width: 95px" onclick="WdatePicker({readOnly: true, dateFmt: 'yyyy-MM-dd',maxDate:'#F{$dp.$D(\'endTime\',{d:-1});}'});" class="form-control" id="startTime" placeholder="开始日期">
                </div>
                <label class="col-xs-1 clear-padding text-center">至</label>
                <div class="col-xs-4">
                    <input type="text" style="width: 95px" onclick="WdatePicker({readOnly: true, dateFmt: 'yyyy-MM-dd', minDate: '#F{$dp.$D(\'startTime\', {d:1});}'});" class="form-control" id="endTime" placeholder="结束日期">
                </div>
            </div>
            <div class="col-xs-3 input-area">
                <label class="col-xs-4 text-right">商品编号</label>
                <div class="col-xs-7">
                    <input type="text" value="" class="form-control" id="goodsCode" placeholder="商品编号">
                </div>
            </div>
            <div class="col-xs-2 input-area">
                <label class="col-xs-4 text-right">名称</label>
                <div class="col-xs-7">
                    <input type="text" style="width: 95px" class="form-control" id="goodsName" placeholder="商品名称">
                </div>
            </div>
            <div class="col-xs-3 pull-right text-r">
                <button class="btn btn-hot " id="doSearch">查 询</button>
                <button class="btn btn-yellow" onclick="ab_goods_add()">新 建</button>
                <button class="btn btn-success" id="doRefresh">刷 新</button>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="main-body col-xs-12">
        <table class="tab" width="100%" border="0" cellspacing="0" cellpadding="0" id="adGoodsList"></table>
    </div>
</div>
<script>
    $(function(){
        DataTable.init('initABGoodsList','#adGoodsList','<?= \yii\helpers\Url::to(['ab-goods/list-data'])?>',getParams());

        //点击事件
        $("#doSearch").bind('click',function(){
            DataTable.reloadTable(getParams());
        });

        //刷新事件
        $("#doRefresh").bind('click',function(){
            DataTable.id = '#adGoodsList';
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

    function ab_goods_add() {
        layer_show_full('新增商品', '<?= \yii\helpers\Url::to(['ab-goods/add'])?>');
//        layer_sho({},title, url, '950');
    }

    function ab_goods_edit(id) {
        layer_show_full('编辑商品', '<?= \yii\helpers\Url::to(['ab-goods/edit'])?>' + '?id=' + id);
    }

    function ab_goods_sell (id, msg) {
        layer.confirm(msg, {icon: 0, title: '提示'}, function (index) {
            ajaxSubmit('<?= \yii\helpers\Url::to(['ab-goods/do-shelf'])?>', {goodsId: id}, function () {
                DataTable.reloadTable(getParams());
            });
            layer_close(index);
        });
    }
    function ab_goods_delete(id) {
        layer.confirm('是否删除？', {icon: 0, title: '提示'}, function (index) {
            ajaxSubmit('<?= \yii\helpers\Url::to(['ab-goods/do-delete'])?>', {id: id});
            layer_close(index);
        });
    }
    function ab_goods_show_image(id) {
        layer_show({id: id}, '', '<?= \yii\helpers\Url::to(['ab-goods-images/edit'])?>');
    }

    function ab_goods_stock_in(goodsCode) {
        layer_show({goodsCode: goodsCode}, '入库', '<?= \yii\helpers\Url::to(['ab-goods/stock-in'])?>');
    }

    function ab_goods_stock_out(goodsCode) {
        layer_show({goodsCode: goodsCode}, '入库', '<?= \yii\helpers\Url::to(['ab-goods/stock-out'])?>');
    }
</script>