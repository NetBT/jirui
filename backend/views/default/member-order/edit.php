<div class="page-container">
    <?php $form = \yii\bootstrap\ActiveForm::begin([
            'id' => 'addMemberOrder',
            'options' => ['class' => 'form form-horizontal'],
            'fieldConfig'=>[
                'inputOptions'=>['class'=>'form-control input-text'],//改变input输入框
//                'options' => [],//改变外层自动生成的div
//                'labelOptions' => [],//改变label
                'labelOptions' => [
                    'class' => 'form-label col-xs-3 text-right'
                ],
                'errorOptions' => [                                 //修改error样式和标签
                    'tag' => 'label',
                    'class' => 'error'
                ],
                'hintOptions' => [                                 //修改hint样式和标签
                    'tag' => 'label',
                    'class' => 'hint'
                ],
            ],
            'method' => 'post',
            'enableAjaxValidation' => true,
            'validationUrl' => \yii\helpers\Url::to(['member-order/validate-form', 'type' => 'edit']),
            'successCssClass' => 'has-success notice-success',
            'errorCssClass' => 'has-error notice-error',
        ]
    );
    ?>
    <?= $form->field($model,'id',[
        'template' => '{input}',
        'options' => ['class' => '']
    ])->hiddenInput()?>

    <?= $form->field($model,'member_id',[
        'template' => '{input}',
        'options' => ['class' => '']
    ])->hiddenInput()?>

    <?= $form->field($model,'reserved_order_number',[
        'template' => '{input}',
        'options' => ['class' => '']
    ])->hiddenInput(['value' => \backend\models\MemberOrder::getMemberOrderNum()])?>

    <div class="vip">
        <div class="vip_top clear-margin-bottom">
            <p class="bt"><b>会员信息</b></p>
            <div class="vip_box_input" style="padding-top:10px">
                <a class="box">筛选条件</a>
                <input type="text" id="memberName" style="width: 178px;" placeholder="<?= $member['tel']?>">
                <a class="btn" href="javascript:void(0);" onclick="getMemberInfo()">查询</a>
                <div id="memberSimpleInfo">
                </div>

            </div>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
        <!--        <div class="vip_top clear-margin-bottom" style="margin-bottom: 0">-->
        <?php if(\common\models\Functions::getABCommonByKey('order_date_on_off') == 1) :?>
            <div class="col-xs-6 col-sm-3 cl">
                <div class="form-group">
                    <lable class="form-label text-center" style="font-weight: bold">订单日期：<?= date('Y-m-d',strtotime($model->create_time))?></lable>
                </div>
            </div>
        <?php endif;?>
        <?php if(\common\models\Functions::getABCommonByKey('order_number_on_off') == 1) :?>
            <div class="col-xs-6 col-sm-4 cl text-center">
                <?= $form->field($model,'order_number',[
                    'labelOptions' => [
                        'class' => 'form-label col-xs-4 text-right'
                    ],
                    'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
                ])->textInput(['placeholder'=>"订单编号",'readonly' => ''])?>
            </div>
        <?php endif;?>
        <div class="clear"></div>
        <!--        </div>-->
        <div class="vip_box" style="margin-bottom:10px;padding:0 40px;">
            <table class="top" width="100%" border="0" cellspacing="0" cellpadding="0" id="memberTable">
                <thead>
                <tr>
                    <th scope="col">序号</th>
                    <th scope="col">宝宝姓名</th>
                    <th scope="col">性别</th>
                    <th scope="col">电话</th>
                    <th scope="col">年龄</th>
                    <th scope="col">家长姓名</th>
                    <th scope="col">与宝宝关系</th>
                    <th scope="col">微信</th>
                    <th scope="col">余额</th>
                    <th scope="col">积分</th>
                    <th scope="col">累计消费</th>
                    <th scope="col">备用电话</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?= $member['id']?></td>
                    <td><?= $member['name']?></td>
                    <td><?= $member['sex']?></td>
                    <td><?= $member['tel']?></td>
                    <td><?= $member['age']?></td>
                    <td><?= $member['parents_name']?></td>
                    <td><?= $member['parents_baby_link']?></td>
                    <td><?= $member['wechat']?></td>
                    <td><?= $member['valid_money']?></td>
                    <td><?= $member['integral']?></td>
                    <td><?= $member['total_consume']?></td>
                    <td><?= $member['spare_tel']?></td>
                </tr>
                </tbody>
            </table>
            <div class="clear"></div>
        </div>
    </div>

    <div class="vip">
        <div class="vip_top clear-margin-bottom">
            <p class="bt"><b>套系信息</b></p>
            <div class="clear"></div>
        </div>
        <div class="vip_box clear-margin-bottom" style="margin-bottom:10px;padding:0 40px;">
            <div class="vip_box_input" style="height:65px;">
                <a class="box">下拉选择</a>
                <div class="col-xs-6 col-sm-6 cl text-center">
                    <?= $form->field($model,'combo_id',[
                        'template' => '{label} <div class="formControls col-xs-7">{input}</div>',
                    ])->dropDownList(\backend\models\Combo::getFormArray(['is_delete' => \common\models\Status::COMBO_NOT_DELETE,'business_id' => \backend\models\Common::getBusinessId()],'id','combo_name'),[
                        'class' => 'selectpicker',
                        'id' => 'selectCombo',
                        'onChange' => 'changeCombo()',
                    ])?>
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
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" id="comboGoodsTable">
                                    <thead>
                                    <tr>
                                        <?php if(\common\models\Functions::getABCommonByKey('combo_goods_name_on_off') == 1) :?>
                                            <th style="border-left:none;" scope="col">商品名称</th>
                                        <?php endif;?>
                                        <?php if(\common\models\Functions::getABCommonByKey('combo_goods_num_on_off') == 1) :?>
                                            <th scope="col">商品数量</th>
                                        <?php endif;?>
                                        <?php if(\common\models\Functions::getABCommonByKey('combo_goods_rule_on_off') == 1) :?>
                                            <th scope="col">商品规格</th>
                                        <?php endif;?>
                                        <?php if(\common\models\Functions::getABCommonByKey('combo_goods_pic_number_on_off') == 1) :?>
                                            <th scope="col">商品编号</th>
                                        <?php endif;?>
                                        <?php if(\common\models\Functions::getABCommonByKey('combo_goods_default_p_on_off') == 1) :?>
                                            <th scope="col">默认P数</th>
                                        <?php endif;?>
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
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="clear"></div>
        </div>
    </div>

    <div class="vip">
        <div class="vip_top clear-margin-bottom">
            <p class="bt"><b>订单信息</b></p>

            <div class="clear"></div>
        </div>
        <div class="clear"></div>

        <?php if(\common\models\Functions::getABCommonByKey('order_num_on_off') == 1) :?>
            <div class="col-xs-6 col-sm-6 cl text-center" style="display: none">
                <?= $form->field($model,'number',[
                    'template' => '{label} <div class="col-xs-7 number-change">
            <a class="left controlLeft" href="javascript: void(0)"></a>
            <input id="number" class="number-input" name="MemberOrder[number]" value="'.$model->number.'" onChange="changeNumber()" placeholder="数量" type="text">
            <a class="right controlRight" href="javascript: void(0)"></a>
        {error}</div>',
                ])?>
            </div>
        <?php endif;?>

        <?php if(\common\models\Functions::getABCommonByKey('combo_price_on_off') == 1) :?>
            <div class="col-xs-6 col-sm-6 cl text-center">
                <?= $form->field($model,'combo_price',[
                    'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
                ])->textInput(['placeholder'=>"套系价"])?>
            </div>
        <?php endif;?>

        <?php if(\common\models\Functions::getABCommonByKey('order_discount_on_off') == 1) :?>
            <div class="col-xs-6 col-sm-6 cl text-center">
                <?= $form->field($model,'discount',[
                    'hintOptions' => [                                 //修改hint样式和标签
                        'tag' => 'label',
                        'class' => 'hint'
                    ],
                    'template' => '{label} <div class="formControls col-xs-7">{input}{error}{hint}</div>',
                ])->dropDownList(\backend\models\Combo::getDiscountByCombo($model->combo_id),[
                    'class' => 'selectpicker',
                    'id' => 'selectComboDiscount',
                    'onChange' => 'changeComboDiscount()',
                ])?>
            </div>
        <?php endif;?>

        <?php if(\common\models\Functions::getABCommonByKey('order_price_on_off') == 1) :?>
            <div class="col-xs-6 col-sm-6 cl text-center">
                <?= $form->field($model,'price',[
                    'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
                ])->textInput(['placeholder'=>"成单价",'onChange' => 'changediscount()'])?>
            </div>
        <?php endif;?>

        <?php if(\common\models\Functions::getABCommonByKey('order_integral_on_off') == 1) :?>
            <div class="col-xs-6 col-sm-6 cl text-center">
                <?= $form->field($model,'integral',[
                    'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
                ])->textInput(['placeholder'=>"积分"])?>
            </div>
        <?php endif;?>

        <?php if(\common\models\Functions::getABCommonByKey('order_earnest_on_off') == 1) :?>
            <div class="col-xs-6 col-sm-6 cl text-center">
                <?= $form->field($model,'earnest',[
                    'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
                ])->textInput(['placeholder'=>"定金",'onchange' => 'changeEarnest()'])?>
            </div>
        <?php endif;?>

        <?php if(\common\models\Functions::getABCommonByKey('order_tail_price_on_off') == 1) :?>
            <div class="col-xs-6 col-sm-6 cl text-center">
                <?= $form->field($model,'final_payment',[
                    'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
                ])->textInput(['placeholder'=>"尾款"])?>
            </div>
        <?php endif;?>

        <div class="col-xs-6 col-sm-6 cl text-center">
            <?= $form->field($model,'gathering_fund',[
                'template' => '{label} <div class="formControls col-xs-7">{input}</div>',
            ])->dropDownList(\common\models\Status::memberOrderGatheringFundMap(),[
                'class' => 'selectpicker',
                'id' => 'selectFund',
                'onchange' => 'changeGatheringFund()',
            ])?>
        </div>

        <div class="col-xs-6 col-sm-6 cl text-center">
            <?= $form->field($model,'gathering_money',[
                'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
            ])->textInput(['placeholder'=>"收款金额"])?>
        </div>

        <div class="col-xs-6 col-sm-6 cl text-center">
            <?= $form->field($model,'pay_type',[
                'template' => '{label} <div class="formControls col-xs-7">{input}</div>',
            ])->dropDownList(\common\models\Status::memberOrderPayTypeMap(),[
                'class' => 'selectpicker',
                'id' => 'selectPayType',
            ])?>
        </div>


        <div class="col-xs-6 col-sm-6">
            <?= $form->field($model,'mark',[
                'template' => '{label}<div class="formControls col-xs-9">{input}{error}</div>',
            ])->textarea(['placeholder'=>"备注",'rows' => 4])?>
        </div>
    </div>

    <?php \yii\bootstrap\ActiveForm::end(); ?>
    <div class="col-xs-12 col-sm-12 cl text-center margin-top-20 margin-bottom-10">
        <?php if($orderstate == '2') :?>
            <button type="button" onclick="add()" class='btn btn-hot btn-md margin-right-30'>付款</button>
        <?php endif;?>

<!--        <button type="button" onclick="layer_close()" class="btn btn-default btn-md layui-layer-close">取消</button>-->
        <button type="button" onclick="removeIframe()" class="btn btn-default btn-md layui-layer-close">取消</button>
    </div>
</div>
<style>
    a.btn{
        width: 65px !important;
        line-height: 28px !important;
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

        changeNumber();

        changeCombo();
    });

    //改变折扣
    function changediscount(){
        $("#selectComboDiscount").selectpicker('deselectAll');
    }

    //改变套系
    function changeCombo(){
        var id = $('#selectCombo').val();
        var number = $('#number').val();
        changeNumberCombo(number,id);
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
            if(!id){
                layer.msg('请先选择套系',{icon:0,time:2000});
                $('#number').val(1);
                return false;
            } else {
                changeNumberCombo(number,id);
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
    function changeNumberCombo(number, comboId){
        $('#number').val(number);
        $.ajax({
            url : '<?= \yii\helpers\Url::to(['combo/get-info-by-where'])?>',
            type : 'POST',
            async: false,
            data : {id : comboId},
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
                        <?php if(\common\models\Functions::getABCommonByKey('combo_goods_name_on_off') == 1) :?>
                        goodsTableHtml += '<td>'+v.goods_name+'</td>';
                        <?php endif;?>
                        <?php if(\common\models\Functions::getABCommonByKey('combo_goods_num_on_off') == 1) :?>
                        goodsTableHtml += '<td>'+v.goods_num+'</td>';
                        <?php endif;?>
                        <?php if(\common\models\Functions::getABCommonByKey('combo_goods_rule_on_off') == 1) :?>
                        goodsTableHtml += '<td>'+v.goods_style+'</td>';
                        <?php endif;?>
                        <?php if(\common\models\Functions::getABCommonByKey('combo_goods_pic_number_on_off') == 1) :?>
                        goodsTableHtml += '<td>'+v.goods_code+'</td>';
                        <?php endif;?>
                        <?php if(\common\models\Functions::getABCommonByKey('combo_goods_default_p_on_off') == 1) :?>
                        goodsTableHtml += '<td>'+v.goods_p+'</td>';
                        <?php endif;?>
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

    //未创建会员，创建会员信息
    function addMember()
    {
        layer_show({}, '添加会员', '<?= \yii\helpers\Url::to(['member/order-add']) ?>',780,500);
    }

    function add() {
        $('#addMemberOrder').prepend('<input type="hidden" value="1" name="orderstate"/>');
        ajaxSubmit('<?= \yii\helpers\Url::to(['member-order/add'])?>',$('#addMemberOrder').serialize(), function () {
            // removeIframe();
            // DataTable.id = '#memberOrderGuaList';
            // DataTable.drawTable();
        });
    }
</script>
