<article class="page-container">
    <form id="postpone" class="form form-horizontal" action="" method="post">
        <input type="hidden" value="1" name="submit" id="submit">
        <div class="col-xs-12 col-sm-12 cl">
            <label class="form-label col-xs-4 text-right">延期</label>
            <div class="form-group formControls col-xs-5 text-left">
                <input type="text" class="form-control input-text" name="postpone_time" value="" placeholder="" >
                <label class="error"></label>
            </div>
            <div class="form-group formControls col-xs-3 text-left">
                <select name="postpone_type" class="selectpicker">
                    <?php foreach (\common\models\Status::AbPostponeTimeUnitMap() as $k => $v) :?>
                        <option value="<?= $k ?>"><?= $v ?></option>
                    <?php endforeach;?>
                </select>
                <label class="error"></label>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 cl">
            <label class="form-label col-xs-4 text-right">金额(元)</label>
            <div class="form-group formControls col-xs-7 text-left">
                <input type="text" class="form-control input-text" name="money" value="" placeholder="" >
                <label class="error"></label>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 cl">
            <label class="form-label col-xs-4 text-right">支付方式</label>
            <div class="form-group formControls col-xs-5 text-left">
                <label class="radio-inline"><input type="radio" name="type" checked value="<?= \common\models\Status::AB_POSTPONE_PAY_WAY_WECHAT?>">微信</label>
                <label class="radio-inline"><input type="radio" name="type" value="<?= \common\models\Status::AB_POSTPONE_PAY_ALIPAY?>">支付宝</label>
                <label class="error"></label>
            </div>
        </div>


    <div class="col-xs-12 col-sm-12 cl pay_type" id="pay_type_<?= \common\models\Status::AB_POSTPONE_PAY_WAY_WECHAT?>">
        <label class="form-label col-xs-4 text-right">微信二维码</label>
        <div class="form-group formControls col-xs-5 text-left">
            <img width="120" height="120" src="/uploads/<?= \common\models\Functions::getCommonByKey('wechat_qr_code') ?>">
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 cl pay_type" id="pay_type_<?= \common\models\Status::AB_POSTPONE_PAY_ALIPAY?>" style="display: none">
        <label class="form-label col-xs-4 text-right">支付宝二维码</label>
        <div class="form-group formControls col-xs-5 text-left">
            <img width="120" height="120" src="/uploads/<?= \common\models\Functions::getCommonByKey('alipay_qr_code') ?>">
        </div>
    </div>

        <div class="col-xs-12 col-sm-12 cl">
            <label class="form-label col-xs-4 text-right">备注</label>
            <div class="form-group formControls col-xs-7 text-left">
                <textarea class="form-control input-text" name="mark" placeholder="请在备注处填写支付宝账号或微信昵称"></textarea>
                <label class="error"></label>
            </div>
        </div>
    </form>
    <div class="col-xs-12 col-sm-12 cl text-center margin-top-20 margin-bottom-10">
        <button type="button" onclick="recharge()" class='btn btn-hot btn-md margin-right-30'>确认</button>
        <button type="button" class="btn btn-default btn-md layui-layer-close">取消</button>
    </div>
</article>
<script>
    $(function(){
        $('.selectpicker').selectpicker({
            'width' : '70px',
        });

        validateForm();

    });
    function recharge() {
        if($("input[name='money']").val() === ''){
            $("input[name='money']").parent().addClass('has-error');
            $("input[name='money']").next().html('金额必须为正数');
        }
        if($("input[name='type']").val() === ''){
            $("input[name='type']").parent().addClass('has-error');
            $("input[name='type']").next().html('请选择支付方式');
        }

        if($("input[name='postpone_time']").val() === ''){
            $("input[name='postpone_time']").parent().addClass('has-error');
            $("input[name='postpone_time']").next().html('请填写时间');
        }

        if($("input[name='postpone_type']").val() === ''){
            $("input[name='postpone_type']").parent().addClass('has-error');
            $("input[name='postpone_type']").next().html('请选择延期方式');
        }

        if ($(".has-error", '#postpone').length)
        {
            return false;
        }
        layer.confirm('确认是否扫描二维码？', {icon: 0, title: '提示'}, function (index) {
            ajaxSubmitForm('#postpone', '<?= \yii\helpers\Url::to(['a-b/postpone-by-self'])?>');

        });

    }

    //验证积分和充值金额必须为数字
    function validateForm(){
        $("input[name='money']").change(function(){
            var money = $(this).val();
            if(Common.isNumber(money)){
                $(this).parent().removeClass('has-error');
                $(this).next().html('');
            } else {
                $(this).parent().addClass('has-error');
                $(this).next().html('金额必须为正数');
            }
        });

        $("input[name='type']").change(function(){
            var num = $(this).val();
            $(".pay_type").css('display','none');
           $("#pay_type_"+num).css('display','block')
        });

        $("input[name='postpone_time']").change(function(){
            var money = $(this).val();
            if(Common.isNumber(money)){
                $(this).parent().removeClass('has-error');
                $(this).next().html('');
            } else {
                $(this).parent().addClass('has-error');
                $(this).next().html('时间必须为正数');
            }
        });
    }
</script>
