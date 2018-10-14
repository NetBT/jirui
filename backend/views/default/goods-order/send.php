<article class="page-container">
    <div class="col-xs-4 col-xs-offset-2 text-right">商品名称：</div>
    <div class="col-xs-4 text-left"><?= $info['goods_name']?></div>
    <div class="col-xs-4 col-xs-offset-2 text-right">购买数量：</div>
    <div class="col-xs-4 text-left"><?= $info['goods_nums']?></div>
    <div class="col-xs-4 col-xs-offset-2 text-right">发货数量：</div>
    <div class="col-xs-4 text-left margin-bottom-15"><input class="input-text" name="sendNum" readonly value="<?= $info['goods_nums'] - $info['send_num']?>"></div>
    <div class="shop_tab_bottom col-xs-12 text-center">
        <button class="btn btn-hot" href="javascript:void(0);" onclick="send()"><i class="fa fa-truck"></i>发货</button>
    </div>
</article>
<script>
    $(function () {
       $(".selectpicker").selectpicker({
           style: 'btn-default',
           width: '100%'
       });
    });
    function send() {
        var id = '<?= $info['id']?>';
        ajaxSubmit('<?= \yii\helpers\Url::to(['goods-order/do-send'])?>', {id: id}, function () {
            $(".layui-layer-close1").click();
            DataTable.reloadTable();
            $("#doSearch").click();
        });
    }
</script>
