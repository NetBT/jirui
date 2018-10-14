<article class="page-container main-body shop col-xs-12">
    <div class="shop_sub">
        <p class="bt">商品详情</p>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tbody>
            <tr>
                <th width="11%" scope="col">商品名称</th>
                <th width="10%" scope="col">价格</th>
                <th width="8%" scope="col">颜色</th>
                <th width="8%" scope="col">尺寸</th>
                <th width="12%" scope="col">材质</th>
                <th width="10%" scope="col">内页/风格</th>
                <th width="18%" scope="col">数量</th>
                <th width="23%" scope="col">操作</th>
            </tr>
            <tr>
                <td><?= $info['goods_name']?></td>
                <td><?php $price = \backend\models\Goods::getGoodsCurrPriceByInfo($info);?><?= floatval($price)?></td>
                <td><?= $info['goods_color']?></td>
                <td><?= $info['goods_size']?></td>
                <td><?= $info['goods_texture']?></td>
                <td><?= $info['goods_style']?></td>
                <td><div class="shop_tab_input"><a class="left controlLeft" href="javascript:void(0)"></a><input type="text" value="1" id="orderNum"><a class="right controlRight" href="javascript:void(0)"></a></div></td>
                <td><a class="btn btn-yellow size-MINI" href="javascript: void(0)" onclick="addCart('<?= $info['id']?>')">加入购物车</a>
                    <a class="btn btn-hot size-MINI" href="javascript: void(0)" onclick="">立即下单</a></td>
            </tr>
            </tbody>
        </table>
        <div class="shop_sub_content">
            <?php foreach ($imgList as $v) :?>
            <img src="/uploads/<?= $v['image_url']?>">
            <?php endforeach;?>
        </div>
    </div>
</article>
<script>
    $(function () {
        inputControl.init();
    });
    function addCart(id) {
        var orderNum = $("#orderNum").val();
        ajaxSubmit('<?= \yii\helpers\Url::to(['headquarters-goods/add-cart'])?>', {goodsId: id, orderNum:  orderNum});
    }

</script>