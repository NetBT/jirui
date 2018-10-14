<article class="page-container">
    <form id="refund" class="form form-horizontal" action="" method="post">
        <input type="hidden" value="1" name="submit" id="submit">
        <input type="hidden" value="<?= $info['order']['member_id']?>" name="memberId">
        <input type="hidden" value="<?= $info['order']['id']?>" name="orderId">
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
                        <th scope="col">实收金额</th>
                        <th scope="col">订单金额</th>
                    </tr>
                    <tr>
                        <td><?= $info['order']['order_number']?></td>
                        <td><?= $info['member']['name']?></td>
                        <!--                        <td>--><?//= $info['order']['number']?><!--</td>-->
                        <td><?= $info['order']['total_money']?></td>
                        <td><?= $info['order']['price']?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>


        <div class="col-xs-12 col-sm-12 cl">
            <div class="form-group">
                <label class="form-label col-xs-offset-2 col-xs-2 text-left">退款方式</label>
                <div class="formControls col-xs-4 text-center">
                    <div>
                        <?php foreach (\common\models\Status::memberOrderRefundTypeMap() as $key => $value) :?>
                            <label class="radio-inline"><input name="type" value="<?= $key?>" <?php if($key == \common\models\Status::MEMBER_ORDER_REFUND_TYPE_CASH):?> checked <?php endif;?>
                                                               type="radio"><?= $value?></label>
                        <?php endforeach;?>
                    </div>
                    <label class="error"></label>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 cl">
            <div class="form-group">
                <label class="form-label col-xs-offset-2 col-xs-2 text-left">扣除积分</label>
                <div class="formControls col-xs-5 text-center">
                    <input type="text" class="form-control input-text" name="refund_integral" id="refund_integral" value="" placeholder="输入积分">
                    <label class="error"></label>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 cl">
            <div class="form-group">
                <label class="form-label col-xs-offset-2 col-xs-2 text-left">扣除金额</label>
                <div class="formControls col-xs-5 text-center">
                    <input type="text" class="form-control input-text" name="refund_money" id="refund_money" value="" placeholder="输入金额" >
                    <label class="error"></label>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 cl">
            <div class="form-group">
                <label class="form-label col-xs-offset-2 col-xs-2 text-left">退款原因</label>
                <div class="formControls col-xs-5 text-center">
                    <textarea type="text" class="form-control input-text" name="refund_reason" id="refund_reason" placeholder="输入原因" ></textarea>
                    <label class="error"></label>
                </div>
            </div>
        </div>

    </form>

    <div class="col-xs-12 col-sm-12 cl text-center margin-top-20 margin-bottom-10">
        <button type="button" onclick="save()" class='btn btn-hot btn-md margin-right-30'>退款</button>
        <button type="button" class="btn btn-default btn-md layui-layer-close">取消</button>
    </div>
</article>
<script>
    $(function(){
        validateForm();
    });
    function save() {

        ajaxSubmitForm('#refund', '<?= \yii\helpers\Url::to(['member-order/refund'])?>');
    }

    //验证积分和充值金额必须为数字
    function validateForm(){
        $("input").change(function(){
//                var $refundIntegral = $("#refund_integral");
//                var refundIntegral = $refundIntegral.val();
//                if(Common.isNumber(refundIntegral)){
//                    $refundIntegral.parent().removeClass('has-error');
//                    $refundIntegral.parent().addClass('has-success notice-success');
//                    $refundIntegral.next().html('');
//                } else {
//                    $refundIntegral.parent().addClass('has-error');
//                    $refundIntegral.next().html('积分必须为正数');
//                }

                //                var $refundMoney = $("#refund_money");
//                var refundMoney = $refundMoney.val();
//                if(Common.isNumber(refundMoney) && refundMoney <= <?//= $info['order']['price']?>//){
//                    $refundMoney.parent().removeClass('has-error');
//                    $refundMoney.next().html('');
//                } else {
//                    $refundMoney.parent().addClass('has-error');
//                    if(refundMoney >= <?//= $info['order']['price']?>//){
//                        $refundMoney.next().html('退款金额不能大于订单金额');
//                    } else {
//                        $refundMoney.next().html('金额必须为正数');
//                    }
//                }

                var $refundIntegral = $("#refund_integral");
                var refundIntegral = $refundIntegral.val();
                if(Common.isNumber(refundIntegral)){
                    $refundIntegral.parent().parent().removeClass('has-error notice-error');
                    $refundIntegral.parent().parent().addClass('has-success notice-success');
                    $refundIntegral.next().html('');
                } else {
                    $refundIntegral.parent().parent().addClass('has-error notice-error');
                    $refundIntegral.next().html('积分必须为正数');
                }



                var $refundMoney = $("#refund_money");
                var refundMoney = $refundMoney.val();
                if(Common.isNumber(refundMoney) && refundMoney <= <?= $info['order']['price']?>){
                    $refundMoney.parent().parent().removeClass('has-error notice-error');
                    $refundMoney.parent().parent().addClass('has-success notice-success');
                    $refundMoney.next().html('');
                } else {
                    $refundMoney.parent().parent().addClass('has-error notice-error');
                    if(refundMoney >= <?= $info['order']['price']?>){
                        $refundMoney.next().html('退款金额不能大于订单金额');
                    } else {
                        $refundMoney.next().html('金额必须为正数');
                    }
                }

            }
        );
    }

</script>
