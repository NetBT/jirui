<article class="page-container main-body shop col-xs-12">
    <div class="shop_tab col-xs-12">
        <p class="bt">购物车</p>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tbody>
            <tr style="margin-bottom:10px;">
                <th colspan="4" scope="col"><label><input type="checkbox" name="checkedAll" value="复选框" id="CheckboxGroup1_0">全选</label><p style="float:left;">商品</p></th>
                <th scope="col">数量</th>
                <th scope="col">单价</th>
                <th scope="col">操作</th>
            </tr>
            <tr>
                <td style="width:100%; height:10px; padding:0; border-bottom:3px solid #ddd;" colspan="7"></td>
            </tr>
            <?php foreach ($list as $k =>$v):?>
            <tr>
                <td colspan="2">
                    <input type="checkbox" name="goodsIds[]" value="<?= $v['id']?>" />
                    <img src="/uploads/<?= $v['image_url']?>">
                    <p class="box">商品名称：<?= $v['goods_name']?><br>商品编号：<?= $v['goods_code']?></p>
                </td>
                <td ><p class="box">颜色：<?= $v['goods_color']?><br>材质：<?= $v['goods_texture']?></p></td>
                <td ><p class="box">内页风格：<?= $v['goods_style']?></p></td>
                <td style="text-align:center;"><div class="shop_tab_input"><a class="left controlLeft"  href="javascript: void(0)"></a><input class="cartOrderNum" data-id="<?= $v['id']?>" type="text" onchange="changeCartNum('<?= $v['id']?>', $(this))" value="<?= $v['order_num']?>"><a class="right controlRight" href="javascript: void(0)"></a></div></td>
                <td style="text-align:center;"><p style="font-size:14px; font-weight:bold; color:#e70012;">¥200</p></td>
                <td style="text-align:center;">
                    <a class="box" href="javascript:void(0);" onclick="deleteCart('<?= $v['id']?>')">删除</a>
                    <a class="box" href="javascript: void(0);" onclick="confirmOrderSingle('<?= $v['id']?>', $(this))">下单</a>
                </td>
            </tr>
            <?php endforeach;?>
            </tbody>
        </table>
        <div class="shop_tab_bottom">
            <label><input type="checkbox" name="checkedAll" value="复选框" id="checkedAll"><p>全选</p></label>
            <a class="btn" href="javascript:void(0);" onclick="confirmAllOrder()">全部下单</a>
            <a class="box" href="javascript:void(0);" onclick="confirmOrder()">下单</a>
        </div>
    </div>
</article>
<script>
    $(function(){
        inputControl.init();
        $("input[name='checkedAll']").click(function () {
            $("input[name^='goodsIds'").prop('checked', $(this).is(":checked"));
        });
    });

    /**
     * 删除购物车里的项目
     * @param id
     */
    function deleteCart(id) {
        layer.confirm('是否删除？', {icon: 0, title: '提示'}, function (index) {
            ajaxSubmit('<?= \yii\helpers\Url::to(['headquarters-goods/delete-cart'])?>', {goodsId: id});
            window.location.reload();
        });
    }

    /**
     * 修改购物车里面的数量
     * @param goodsId
     * @param obj
     */
    function changeCartNum(goodsId, obj) {
        $.post('<?= \yii\helpers\Url::to(['headquarters-goods/change-cart-num'])?>', {goodsId: goodsId, num:  obj.val()}, function () {
            
        });
    }

    function confirmAllOrder () {
        $('#checkedAll').click();
        confirmOrder();
    }
    function confirmOrder() {
        var params = [
        ];
        $("input[name^=goodsIds]:checked").each(function () {
            var num = $(this).parents('tr').find('.cartOrderNum').val();
            var goodsId = $(this).val();
            if(num > 0) {
                params.push({goodsId: goodsId, orderNum : num});
            }
        });
        if (params.length <= 0) {
            layer.msg('请选择商品', {icon: 2});
            return false;
        }
        layer_show({params: params}, '直接下单', '<?= \yii\helpers\Url::to(['goods-order/confirm-order'])?>', 1000);
    }

    function confirmOrderSingle(goodsId, obj) {
        var num = obj.parents('tr').find('.cartOrderNum').val();
        var params = [
            {goodsId: goodsId, orderNum : num}
        ];
        layer_show({params: params}, '直接下单', '<?= \yii\helpers\Url::to(['goods-order/confirm-order'])?>', 1000);
    }
</script>