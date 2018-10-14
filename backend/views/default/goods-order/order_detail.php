<article class="page-container col-xs-12">
    <div class="main-body col-xs-12">
        <table class="tab" width="100%" border="0" cellspacing="0" cellpadding="0" id="detailList"></table>
    </div>
</article>
<script>
    $(function(){
        DataTable.init('initABGoodsOrderDetailList','#detailList','<?= \yii\helpers\Url::to(['goods-order/get-detail-data', 'orderNumber' => $orderNumber])?>',getDetailParams());
    });
    function getDetailParams (){
        return [];
    }

    function goods_import(id,goods_id,name,nums)
    {
        layer.confirm('名称【'+name+'】: 数量【'+nums+'】,确定入库？',function(index){
            var params = {
                id: id,
                goods_id: goods_id,
            };
            ajaxSubmit('<?= \yii\helpers\Url::to(['goods-order/goods-import'])?>', params, function () {
                DataTable.id = '#detailList';
                DataTable.drawTable();
            });
        });
    }
</script>
