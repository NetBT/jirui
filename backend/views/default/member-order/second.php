<article class="page-container">
    <form id="second" class="form form-horizontal" action="" method="post">
        <input type="hidden" value="1" name="submit" id="submit">
        <input type="hidden" value="<?= $info['order']['member_id']?>" name="memberId">
        <input type="hidden" value="<?= $info['order']['id']?>" name="orderId">
        <input type="hidden" value="<?= $info['order']['final_payment']?>" name="finalPayment">
        <input type="hidden" value="<?= $weikuan?>" name="weikuan">
        <div class="col-xs-12 col-sm-12 cl">
            <div class="formControls col-xs-4">
                <label class="form-label col-xs-5 text-right">姓名</label>
                <div class="form-group formControls col-xs-8">
                    <input type="text" class="form-control input-text" name="" value="<?= $info['member']['name']?>" readonly disabled >
                </div>
            </div>
            <div class="formControls col-xs-4">
                <label class="form-label col-xs-5 text-right">余额</label>
                <div class="form-group formControls col-xs-8">
                    <input type="text" class="form-control input-text" name="old_money" value="<?= $info['member']['valid_money']?>" readonly disabled >
                </div>
            </div>
            <div class="formControls col-xs-4">
                <label class="form-label col-xs-5 text-right">积分</label>
                <div class="form-group formControls col-xs-8">
                    <input type="text" class="form-control input-text" name="old_integral" value="<?= $info['member']['integral']?>" readonly disabled >
                </div>
            </div>
        </div>

        <div class="vip">
            <div class="vip_box">
                <table class="top" width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                    <tr>
                        <th scope="col">订单号</th>
                        <th scope="col">会员名称</th>
                        <!--                        <th scope="col">数量</th>-->
                        <th scope="col">定金</th>
                        <th scope="col">实收</th>
                        <th scope="col">尾款</th>
                        <th scope="col">订单金额</th>
                    </tr>
                    <tr>
                        <td><?= $info['order']['order_number']?></td>
                        <td><?= $info['member']['name']?></td>
                        <!--                        <td>--><?//= $info['order']['number']?><!--</td>-->
                        <td><?= $info['order']['earnest']?></td>
                        <td><?= $info['order']['total_money']?></td>
                        <td><?= $info['order']['final_payment']?></td>
                        <td><?= $info['order']['price']?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 cl">
            <div class="form-group">
                <label class="form-label col-xs-offset-2 col-xs-2 text-left">收款款项</label>
                <div class="formControls col-xs-5 text-center">
                    <select class="selectpicker" name="gathering_fund" id="gathering_fund" onchange="changeSecondGatherFund()" <?php if($weikuan == 'weikuan') :?> disabled="disabled" <?php endif;?>>
                        <?php if($weikuan == 'weikuan') :?>
                            <option value="4" selected>尾款</option>
                        <?php else:?>
                            <?php foreach (\common\models\Status::memberOrderSecondGatheringFundMap() as $key => $value) : ?>
                                <option value="<?= $key?>"><?= $value?></option>
                            <?php endforeach;?>
                        <?php endif;?>
                    </select>
                    <label class="error"></label>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 cl">
            <div class="form-group">
                <label class="form-label col-xs-offset-2 col-xs-2 text-left">支付方式</label>
                <div class="formControls col-xs-5 text-center">
                    <select class="selectpicker" name="pay_type" id="pay_type">
                        <?php foreach (\common\models\Status::memberOrderPayTypeMap() as $key => $value) : ?>
                            <option value="<?= $key?>"><?= $value?></option>
                        <?php endforeach;?>
                    </select>
                    <label class="error"></label>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 cl">
            <div class="form-group">
                <label class="form-label col-xs-offset-2 col-xs-2 text-left">金额</label>
                <div class="formControls col-xs-5 text-center">
                    <input type="text" class="form-control input-text" name="second_money" id="second_money" value="" placeholder="输入金额" >
                    <label class="error"></label>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 cl">
            <div class="form-group">
                <label class="form-label col-xs-offset-2 col-xs-2 text-left">备注</label>
                <div class="formControls col-xs-5 text-center">
                    <textarea type="text" class="form-control input-text" name="mark" id="mark" style="resize:none" placeholder="备注"></textarea>
                    <label class="error"></label>
                </div>
            </div>
        </div>

    </form>

    <div class="col-xs-12 col-sm-12 cl text-center margin-top-20 margin-bottom-10">
        <button type="button" onclick="save()" class='btn btn-hot btn-md margin-right-30'>确认</button>
        <button type="button" class="btn btn-default btn-md layui-layer-close">取消</button>
    </div>
</article>
<script>
    $(function(){
        validateForm();

        var  type = $("input[name=weikuan]").val();
        if (type) {
            $('#second_money').val($("input[name=finalPayment]").val());
        }

        $("#gathering_fund").selectpicker({
            style: 'btn-default',
            width: '100%',
            liveSearch: true
        });

        $("#pay_type").selectpicker({
            style: 'btn-default',
            width: '100%',
            liveSearch: true
        });
    });
    function save() {
        var $secondMoney = $("#second_money");
        var secondMoney = $secondMoney.val();
        if(secondMoney == '') {
            $secondMoney.parent().parent().addClass('has-error notice-error');
            $secondMoney.next().html('金额不能为空');
            return false;
        }
        ajaxSubmitForm('#second', '<?= \yii\helpers\Url::to(['member-order/second'])?>');
    }

    function changeSecondGatherFund()
    {
        var gatheringFund = $('#gathering_fund').selectpicker('val');
        if(gatheringFund == '<?= \common\models\Status::MEMBER_ORDER_SECOND_GATHERING_FUND_FINAL_PAYMENT?>') {
            $('#second_money').val($("input[name=finalPayment]").val());
        } else {
            $('#second_money').val('');
        }
    }

    //验证积分和充值金额必须为数字
    function validateForm(){
        $("input").change(function(){
//                var $secondMoney = $("#second_money");
//                var secondMoney = $secondMoney.val();
//                if(Common.isNumber(secondMoney)){
//                    $secondMoney.parent().removeClass('has-error');
//                    $secondMoney.next().html('');
//                } else {
//                    $secondMoney.parent().addClass('has-error');
//                    $secondMoney.next().html('金额必须为正数');
//                }

            var $secondMoney = $("#second_money");
            var secondMoney = $secondMoney.val();
            if(Common.isNumber(secondMoney)){
                $secondMoney.parent().parent().removeClass('has-error notice-error');
                $secondMoney.parent().parent().addClass('has-success notice-success');
                $secondMoney.next().html('');
            } else {
                $secondMoney.parent().parent().addClass('has-error notice-error');
                $secondMoney.next().html('金额必须为正数');
            }

            }
        );
    }

</script>
