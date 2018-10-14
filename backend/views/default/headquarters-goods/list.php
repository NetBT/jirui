<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-12"><b>总部直购</b>Headquarters direct purchase</div>
        <div class="header-search col-xs-12 clear-padding">

            <div class="col-xs-1" style="margin-left:25px">
                <div class="vip_box_input">
                    <a class="box" style="width: 100px">筛选条件</a>
                </div>
            </div>

            <form action="<?= \yii\helpers\Url::to(['headquarters-goods/list'])?>" method="get">
            <div class="col-xs-4  input-area">
                <label class="col-xs-4 text-right">商品编号</label>
                <div class="col-xs-8">
                    <input type="text" value="<?= $goodsCode?>" class="form-control" name="goodsCode" placeholder="商品编号">
                </div>
            </div>
            <div class="col-xs-2 input-area">
                <label class="col-xs-4 text-right">名称</label>
                <div class="col-xs-8">
                    <input type="text" style="width: 100px" value="<?= $goodsName?>" class="form-control" name="goodsName" placeholder="商品名称">
                </div>
            </div>
            <div class="col-xs-3 pull-right text-r">
                <button class="btn btn-hot " type="submit" id="doSearch">查 询</button>
            </form>
            <button class="btn btn-yellow" type="button" onclick="cart()">购物车</button>
            <a class="btn btn-success" id="doRefresh" href="javascript:location.replace(location.href);">刷 新</a>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="main-body shop col-xs-12">
        <div class="shop_box">
            <?php foreach ($list  as $k => $v) :?>
                <div class="shop_box_sub" >
                    <img onclick="showDetail('<?= $v['id']?>')" src="/uploads/<?= $v['image_url']?>">
                    <p class="bt">¥<?= $v['curr_real_price']?></p>
                    <p class="ct">商品名称：<br><?= $v['goods_name']?><br>商品编号：<br><?= $v['goods_code']?></p>
                    <a class="btn" href="javascript:void(0);" onclick="addCart('<?= $v['id']?>')">加入购物车</a>
                    <a class="box" href="javascript:void(0);" onclick="confirmOrder('<?= $v['id']?>')">立即下单</a>
                </div>
            <?php endforeach;?>
                <div class="clear"></div>
            <div class="bootstrap col-xs-12 text-right">
            <?=
                \yii\widgets\LinkPager::widget([
                    'pagination' => $pages,
                ]);
            ?>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
//        $("#doSearch").bind('click',function(){
//            DataTable.reloadTable(getParams());
//        });
    });
    /*
     * 搜集搜索条件
     */
    function getParams () {
        return {
            goods_code : $("#goodsCode").val(),
            goods_name : $("#goodsName").val(),
        };
    }

    function cart() {
        layer_show_full('购物车', '<?= \yii\helpers\Url::to(['headquarters-goods/cart'])?>');
    }

    function addCart(id) {
        ajaxSubmit('<?= \yii\helpers\Url::to(['headquarters-goods/add-cart'])?>', {goodsId: id});
    }

    function confirmOrder(goodsId) {
        var params = [
            {goodsId: goodsId, orderNum : 1}
        ];
        layer_show({params: params}, '直接下单', '<?= \yii\helpers\Url::to(['goods-order/confirm-order'])?>', 1000);
    }
    function showDetail(id) {
        layer_show({goodsId: id}, '', '<?= \yii\helpers\Url::to(['goods/detail'])?>', 1000);
    }
</script>