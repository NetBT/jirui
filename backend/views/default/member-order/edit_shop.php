<div class="page-container">
    <div class="vip">
        <div class="vip_top clear-margin-bottom">
            <p class="bt"><b>套系信息</b></p>
            <div class="clear"></div>
        </div>
        <div class="vip_box clear-margin-bottom" style="margin-bottom:10px;padding:0 40px;">
            <div class="vip_box_input" style="height:65px;">
                <!--<a class="box">下拉选择</a>-->
                <div class="col-xs-6 col-sm-6 cl text-center">
                    <div class="form-group field-selectCombo required">
                        <label class="form-label col-xs-3 text-right" for="selectCombo">套系名称</label>
                        <div class="formControls col-xs-7">
                            <select id="selectCombo" class="selectpicker" name="combo_id" onchange="changeCombo()" aria-required="true" disabled="disabled">
                                <?php foreach (\backend\models\Combo::getFormArray(['is_delete' => \common\models\Status::COMBO_NOT_DELETE,'business_id' => \backend\models\Common::getBusinessId()],'id','combo_name') as $key => $value) :?>
                                    <option value="<?= $key?>" <?php if($key == $orderCombo['combo_id']):?> selected <?php endif;?>><?= $value?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="vip_tab">
                <p class="tab"><a class="hover" style="width: 155px;">详细信息</a></p>
                <table class="box" id="comboTable" width="100%" border="0" cellspacing="0" cellpadding="0">
                    <thead>
                    <tr>
                        <?php if(\common\models\Functions::getABCommonByKey('combo_name_on_off') == 1) :?>
                            <th scope="col">套系名称</th>
                        <?php endif;?>
                        <?php if(\common\models\Functions::getABCommonByKey('combo_price_on_off') == 1) :?>
                            <th scope="col">套系价格</th>
                        <?php endif;?>
                        <?php if(\common\models\Functions::getABCommonByKey('combo_volume_on_off') == 1) :?>
                            <th scope="col">入底 / 入册</th>
                        <?php endif;?>
                        <?php if(\common\models\Functions::getABCommonByKey('combo_clothing_on_off') == 1) :?>
                            <th scope="col">服装造型</th>
                        <?php endif;?>
                    </tr>
                    </thead>
                    <tbody id="comboTableTbody">
                    <!--                    <tr>-->
                    <!--                        <td>1</td>-->
                    <!--                        <td>BBJM0001201707020001</td>-->
                    <!--                        <td>2000</td>-->
                    <!--                        <td>30</td>-->
                    <!--                    </tr>-->
                    </tbody>
                    <tr>
                        <td colspan="12">
                            <div class="vip_tab_box">
                                <p class="left">套餐商品：</p>
                                <form id="refund" class="form form-horizontal" action="" method="post">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" id="comboGoodsTable">
                                    <thead>
                                    <tr>
                                        <?php if(\common\models\Functions::getABCommonByKey('combo_goods_name_on_off') == 1) :?>
                                            <th style="border-left:none;" scope="col">商品名称</th>
                                        <?php endif;?>
                                        <?php if(\common\models\Functions::getABCommonByKey('combo_goods_pic_number_on_off') == 1) :?>
                                            <th scope="col">商品编号</th>
                                        <?php endif;?>
                                        <?php if(\common\models\Functions::getABCommonByKey('combo_goods_num_on_off') == 1) :?>
                                            <th scope="col">商品数量</th>
                                        <?php endif;?>
                                        <?php if(\common\models\Functions::getABCommonByKey('combo_goods_rule_on_off') == 1) :?>
                                            <th scope="col">商品规格</th>
                                        <?php endif;?>
                                        <?php if(\common\models\Functions::getABCommonByKey('combo_goods_default_p_on_off') == 1) :?>
                                            <th scope="col">默认P数</th>
                                        <?php endif;?>
                                        <?php ?>
                                            <th scope="col">商品价格</th>
                                        <?php ?>
                                        <?php ?>
                                            <th scope="col">商品折扣价</th>
                                        <?php ?>
                                        <?php ?>
                                            <th scope="col">商品颜色</th>
                                        <?php ?>
                                        <?php ?>
                                            <th scope="col">商品尺寸</th>
                                        <?php ?>
                                        <?php ?>
                                            <th scope="col">商品材质</th>
                                        <?php ?>
                                        <?php ?>
                                            <th scope="col">删除</th>
                                        <?php ?>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <!--                                    <tr>-->
                                    <!--                                        <td style="border-left:none;">双人间</td>-->
                                    <!--                                        <td>-1</td>-->
                                    <!--                                        <td>-1</td>-->
                                    <!--                                        <td>123</td>-->
                                    <!--                                        <td>123</td>-->
                                    <!--                                    </tr>-->

                                    </tbody>
                                </table>
                                </form>
                                <!--<button type="button" onClick="member.addShopModal()" class="btn btn-default btn-md layui-layer-close">添加</button>-->
                                <label class="form-label col-xs-3" style="width:150px;top:20px">添加商品</label>
                                <div class="col-xs-6 col-sm-6 cl text-center" style="width: 500px;top:18px;">
                                    <div class="form-group field-selectCombo required">
                                        <input type="hidden" value="<?= $orderCombo['combo_order_number']?>" name="combo_order_number" class="combo_order_number">
                                        <input type="hidden" value="<?= $orderCombo['member_id']?>" name="member_id" class="member_id">
                                        <div class="formControls col-xs-7">
                                            <select id="goods_code" class="selectpicker" name="goods_code" aria-required="true">
                                                <?php foreach ($goodArr as $key => $value) :?>
                                                    <option value="<?= $value['goods_code']?>"><?= $value['goods_name']?></option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>
                                        <button type="button" onClick="addShop()" class="btn btn-default btn-md layui-layer-close">确定</button>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="clear"></div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 cl text-center margin-top-20 margin-bottom-10">
        <button type="button" onclick="saveEdit()" class='btn btn-hot btn-md margin-right-30'>保存编辑</button>
        <button type="button" onclick="removeIframe()" class="btn btn-default btn-md layui-layer-close">取消</button>
    </div>
</div>
<style>
    a.btn{
        width: 65px !important;
        line-height: 28px !important;
    }
    #comboGoodsTable td{
        min-width: 80px;
        max-width: 200px;
    }
    #comboGoodsTable th{
        min-width: 80px;
        max-width: 200px;
    }
    #comboGoodsTable input{
        width: 150px;
    }
</style>
<script>
    $(function(){
        $("#selectCombo").selectpicker({
            title: '选择套系',
            style: 'btn-default',
            width: '100%',
            liveSearch: true,
        });

        $("#selectFund").selectpicker({
            title: '选择收款款项',
            style: 'btn-default',
            width: '100%',
            liveSearch: true
        });

        $("#selectComboDiscount").selectpicker({
            title: '选择折扣',
            style: 'btn-default',
            width: '100%',
            liveSearch: true
        });

        $("#selectPayType").selectpicker({
            title: '选择支付方式',
            style: 'btn-default',
            width: '100%',
            liveSearch: true
        });
        numberControl.init();

//        changeNumber();

        changeCombo();
    });

    //保持编辑
    function saveEdit() {
        ajaxSubmitForm('#refund', '<?= \yii\helpers\Url::to(['member-order/save-edit-shop'])?>');
    }


    //改变套系
    function changeCombo(){
        var id = $('#selectCombo').val();
        var combo_order_number = $('.combo_order_number').val();
        var number = $('#number').val();
        changeNumberCombo(number,id,combo_order_number);
    }

    //改变折扣
    function changeComboDiscount() {
        var discount = $("#selectComboDiscount").selectpicker('val');
        var comboPrice = $('#memberorder-combo_price').val();
        var price = ((discount * comboPrice).toFixed(2)) * 100 / 100;
        $('#memberorder-price').val(price);

        changeEarnest();
    }

    //改变数量
    function changeNumber(){
        $('a','.number-change').on('click',function(){
            var id = $('#selectCombo').val();
            var number = $('#number').val();
            var combo_order_number = $('.combo_order_number').val();

            if(!id){
                layer.msg('请先选择套系',{icon:0,time:2000});
                $('#number').val(1);
                return false;
            } else {
                changeNumberCombo(number,id,combo_order_number);
            }
        });
    }

    //选择编码方式的方法
    function changeOrderNumberType()
    {
        var type = $("input[name='MemberOrder[order_number_type]']:checked").val();
        switch (type) {
            case '<?= \common\models\Status::MEMBER_ORDER_NUMBER_TYPE_AUTO?>' :
                $('#memberorder-order_number').val($('#memberorder-reserved_order_number').val());
                $('#memberorder-order_number').attr('readonly',true);
                break;
            case '<?= \common\models\Status::MEMBER_ORDER_NUMBER_TYPE_HAND?>' :
                $('#memberorder-order_number').val('');
                $('#memberorder-order_number').attr('readonly',false);
                break;
        }
    }

    //选择套系时的综合方法
    function changeNumberCombo(number, comboId, combo_order_number){
        $.ajax({
            url : '<?= \yii\helpers\Url::to(['combo/get-shop-by-where'])?>',
            type : 'POST',
            async: false,
            data : {id : comboId,combo_order_number:combo_order_number},
            dataType : 'JSON',
            success: function(data)
            {
                if(data.code == 1000) {
                    var combo = data.data.combo;
                    var goods = data.data.goods;

                    var comboDiscount = $('#selectComboDiscount').selectpicker('val') ? $('#selectComboDiscount').selectpicker('val') : 1;
                    if(number == 1) {
                        comboDiscount = 1;
                    }
                    $('#memberorder-combo_price').val(combo.combo_price * number);
                    var price = ((combo.combo_price * number * comboDiscount).toFixed(2)) * 100 / 100;
//                    var price = ((combo.combo_price * number * comboDiscount).toFixed(2));
                    $('#memberorder-price').val(price);
//                    $('#memberorder-discount').val();
                    $('#memberorder-integral').val(combo.combo_integral * number);
                    $('#memberorder-final_payment').val((combo.combo_price * number) - $('#memberorder-earnest').val());

                    var discountHtml = '';
                    var checked = '';
                    if(combo.combo_discount) {
                        discountHtml += '<option value="1">原价</option>';
                        $.each(combo.combo_discount,function(k,v) {
                            if (comboDiscount == v) {
                                checked = 'selected';
                            }
                            discountHtml += '<option value="'+v+'" '+checked+'>'+v+'</option>';
                        });
                        $('#selectComboDiscount').html('');
                        $('#selectComboDiscount').append(discountHtml);
                        $('#selectComboDiscount').selectpicker('refresh');
                    }

                    var comoboTableHtml = '';
                    comoboTableHtml += '<tr>';
                    <?php if(\common\models\Functions::getABCommonByKey('combo_name_on_off') == 1) :?>
                    comoboTableHtml += '<td>'+combo.combo_name+'</td>';
                    <?php endif;?>
                    <?php if(\common\models\Functions::getABCommonByKey('combo_price_on_off') == 1) :?>
                    comoboTableHtml += '<td>'+combo.combo_price+'</td>';
                    <?php endif;?>
                    <?php if(\common\models\Functions::getABCommonByKey('combo_volume_on_off') == 1) :?>
                    comoboTableHtml += '<td>'+combo.register_count+'</td>';
                    <?php endif;?>
                    <?php if(\common\models\Functions::getABCommonByKey('combo_clothing_on_off') == 1) :?>
                    comoboTableHtml += '<td>'+combo.combo_clothing+'</td></tr>';
                    <?php endif;?>
                    $('#comboTableTbody').html('');
                    $('#comboTableTbody').append(comoboTableHtml);

                    var goodsTableHtml = '';
                    $.each(goods,function(k,v){
                        goodsTableHtml += '<tr>';
                        goodsTableHtml += '<input type="hidden" name="order_detail_id[]" value="'+v.id+'"/>';
                        <?php if(\common\models\Functions::getABCommonByKey('combo_goods_name_on_off') == 1) :?>
                        goodsTableHtml += '<td>'+v.goods_name+'</td>';
                        <?php endif;?>
                        <?php if(\common\models\Functions::getABCommonByKey('combo_goods_pic_number_on_off') == 1) :?>
                        goodsTableHtml += '<td>'+v.goods_code+'</td>';
                        <?php endif;?>
                        <?php if(\common\models\Functions::getABCommonByKey('combo_goods_num_on_off') == 1) :?>
                        goodsTableHtml += '<td><input name="goods_num[]" value="'+v.goods_num+'"/></td>';
                        <?php endif;?>
                        <?php if(\common\models\Functions::getABCommonByKey('combo_goods_rule_on_off') == 1) :?>
                        goodsTableHtml += '<td><input name="goods_style[]" value="'+v.goods_style+'"/></td>';
                        <?php endif;?>
                        <?php if(\common\models\Functions::getABCommonByKey('combo_goods_default_p_on_off') == 1) :?>
                        goodsTableHtml += '<td><input name="goods_p[]" value="'+v.goods_p+'"/></td>';
                        <?php endif;?>
                        <?php ?>
                        goodsTableHtml += '<td><input name="goods_price[]" value="'+v.goods_price+'"/></td>';
                        <?php ?>
                        <?php ?>
                        goodsTableHtml += '<td><input name="goods_discount[]" value="'+v.goods_discount+'"/></td>';
                        <?php ?>
                        <?php ?>
                        goodsTableHtml += '<td><input name="goods_color[]" value="'+v.goods_color+'"/></td>';
                        <?php ?>
                        <?php ?>
                        goodsTableHtml += '<td><input name="goods_size[]" value="'+v.goods_size+'"/></td>';
                        <?php ?>
                        <?php ?>
                        goodsTableHtml += '<td><input name="goods_texture[]" value="'+v.goods_texture+'"/></td>';
                        <?php ?>
                        <?php ?>
                        goodsTableHtml += '<td onClick="deleteshop('+v.id+')"><i class="fa fa-remove"></i></td>';
                        <?php ?>
                        goodsTableHtml += '</tr>';
                    });

                    $('tbody','#comboGoodsTable').html('');
                    $('tbody','#comboGoodsTable').append(goodsTableHtml);

                } else {
                    layer.msg(data.message,{icon:5,time:2000});
                }

            },
            error: function()
            {
                layer.msg('网络错误',{icon:5,time:2000});
            }
        })
    }

    //改变定金
    function changeEarnest(){
        var totalMoney = $('#memberorder-price').val();
        var earnestMonet = $('#memberorder-earnest').val();

        $('#memberorder-final_payment').val(totalMoney - earnestMonet);

        changeGatheringFund();
    }

    //改变收款款项
    function changeGatheringFund(){
        var fund = $('#selectFund').val();
        var payMoney = '';
        switch (fund) {
            case '<?= \common\models\Status::MEMBER_ORDER_GATHERING_FUND_EARNEST?>'://定金
                payMoney = $('#memberorder-earnest').val();
                break;
            case '<?= \common\models\Status::MEMBER_ORDER_GATHERING_FUND_FULL?>'://全部
                payMoney = $('#memberorder-price').val();
                break;
            case '<?= \common\models\Status::MEMBER_ORDER_GATHERING_FUND_TAIL?>'://尾款
                payMoney = $('#memberorder-final_payment').val();
                break;
            case '<?= \common\models\Status::MEMBER_ORDER_GATHERING_FUND_SELF?>'://自定义
                break;
        }
        $('#memberorder-gathering_money').val(payMoney);
    }


    //已创建会员  查询会员信息
    function getMemberInfo(name)
    {
        var memberName = name ? name : $('#memberName').val();
        $.ajax({
            url : '<?= \yii\helpers\Url::to(['member/get-member-info-by-where'])?>',
            async: false,
            data : {memberName : memberName},
            type : 'post',
            dataType : 'json',
            success : function (data) {
                if(data.code == 1000) {
                    addMemberInfo(data.data);
                } else {
                    layer.msg(data.message,{icon:5,time:2000});
                }
            },
            error : function() {
                layer.msg('请求出错，请联系管理员',{icon:5,time:2000});
            }
        });
    }

    //追加用户信息
    function addMemberInfo(data)
    {
        var info = data;
        var tbody = '';
        tbody += '<tr>';
        tbody += '<td>'+info.id+'</td>';
        tbody += '<td>'+info.name+'</td>';
        tbody += '<td>'+info.sex+'</td>';
        tbody += '<td>'+info.tel+'</td>';
        tbody += '<td>'+info.age+'</td>';
        tbody += '<td>'+info.parents_name+'</td>';
        tbody += '<td>'+info.parents_baby_link+'</td>';
        tbody += '<td>'+info.wechat+'</td>';
        tbody += '<td>'+info.valid_money+'</td>';
        tbody += '<td>'+info.integral+'</td>';
        tbody += '<td>'+info.total_consume+'</td>';
        tbody += '<td>'+info.spare_tel+'</td>';
        tbody += '</tr>';
        $('tbody','#memberTable').html('');
        $('tbody','#memberTable').append(tbody);
//        var pHtml = '';
//        pHtml += '<p class="right">姓名：'+info.name+'</p><p class="right">余额：'+info.valid_money+'</p><p class="right">积分：'+info.integral+'</p>'
//        $('#memberSimpleInfo').html('');
//        $('#memberSimpleInfo').append(pHtml);

        var name = $('#memberName').val();
        if(!name) {
            $('#memberName').val(info.tel);
        }

        $('#memberorder-member_id').val(info.id);
    }
    function addShop() {
        var goods_code = $('#goods_code').val();
        var combo_order_number = $('.combo_order_number').val();
        var member_id = $('.member_id').val();
        var param = {
            goods_code : goods_code,
            combo_order_number : combo_order_number,
            member_id : member_id,
        };
        ajaxSubmit('<?= \yii\helpers\Url::to(['member-order/add-shop'])?>', param,function(){
            var id = $('#selectCombo').val();
            var combo_order_number = $('.combo_order_number').val();
            var number = $('#number').val();
            changeNumberCombo(number,id,combo_order_number);
        });
    }
    function deleteshop(id) {
        ajaxSubmit('<?= \yii\helpers\Url::to(['member-order/delete-shop'])?>', {id:id},function(){
            var id = $('#selectCombo').val();
            var combo_order_number = $('.combo_order_number').val();
            var number = $('#number').val();
            changeNumberCombo(number,id,combo_order_number);
        });
    }

</script>
