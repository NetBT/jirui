<article class="page-container main-body shop col-xs-12">
    <div class="shop_tab col-xs-12">
        <p class="bt">确认订单</p>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tbody>
            <tr style="margin-bottom:10px;">
                <th colspan="4" scope="col">&emsp;</th>
                <th scope="col">数量</th>
                <th scope="col">单价</th>
                <th scope="col">小计</th>
            </tr>
            <tr>
                <td style="width:100%; height:10px; padding:0; border-bottom:3px solid #ddd;" colspan="7"></td>
            </tr>
            <?php
            $totalNum = 0;
            $totalMoney = 0;
            foreach ($list as $k =>$v):
                $totalMoney += $v['curr_real_price'] * $v['order_num'];
                $totalNum += $v['order_num'];
                ?>
                <tr data-id="<?= $v['id']?>" data-num="<?= $v['order_num']?>" class="dataTr">
                    <td colspan="2">
                        <img src="/uploads/<?= $v['image_url']?>">
                        <p class="box">商品名称：<?= $v['goods_name']?><br>商品编号：<?= $v['goods_code']?></p>
                    </td>
                    <td ><p class="box">颜色：<?= $v['goods_color']?><br>材质：<?= $v['goods_texture']?></p></td>
                    <td ><p class="box">内页风格：<?= $v['goods_style']?></p></td>
                    <td style="text-align:center;"><div class="shop_tab_input"><?= $v['order_num']?></div></td>
                    <td style="text-align:center;"><p style="font-size:14px; font-weight:bold; color:#e70012;">¥<?= $v['curr_real_price']?></p></td>
                    <td style="text-align:center;">
                        <?= $v['curr_real_price'] * $v['order_num']?>
                    </td>
                </tr>
            <?php endforeach;?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" align="center">总计</td>
                    <td align="center"><?= $totalNum?></td>
                    <td >&nbsp;</td>
                    <td align="center"><?= $totalMoney?></td>
                </tr>
                <tr>
                    <td colspan="7" align="center">
                        订单款项将从您本店铺余额中扣除，如余额不足，请及时充值。
                    </td>
                </tr>
                <tr>
                    <td align="center">联系人</td>
                    <td colspan="2" align="center">社会王</td>
                    <td align="center">联系电话</td>
                    <td colspan="3" align="center">0000-00000000</td>
                </tr>
                <tr>
                    <td  align="center">收货地址</td>
                    <td colspan="6" align="center">中国北京</td>
                </tr>
            </tfoot>
        </table>
        <div class="shop_tab_bottom">
            <a class="box" href="javascript:void(0);" onclick="saveOrder()">下单</a>
        </div>
    </div>
</article>
<script>
    $(function(){

    });

    /**
     * 保存订单
     */
    function saveOrder() {
        var params = [];
        $("tr.dataTr").each(function () {
            var goodsId = $(this).attr('data-id');
            var goodsNum = $(this).attr('data-num');
            params.push({goodsId: goodsId, goodsNum: goodsNum});
        });
        layer.confirm('是否支付此订单？', {icon: 0, title: '提示'}, function (index) {
            ajaxSubmit('<?= \yii\helpers\Url::to(['goods-order/save-order'])?>', {params: params}, function () {
                layer.closeAll();
            });
        });
    }
</script>