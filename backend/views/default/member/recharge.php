<article class="page-container">
    <form id="recharge" class="form form-horizontal" action="" method="post">
        <input type="hidden" value="1" name="submit" id="submit">
        <input type="hidden" value="<?= $model->id?>" name="id" id="id">
        <div class="col-xs-12 col-sm-12 cl">
            <div class="formControls col-xs-4">
                <label class="form-label col-xs-5 text-right">姓名</label>
                <div class="form-group formControls col-xs-8">
                    <input type="text" class="form-control input-text" name="" value="<?= $model->name?>" readonly disabled >
                </div>
            </div>
            <div class="formControls col-xs-4">
                <label class="form-label col-xs-5 text-right">余额</label>
                <div class="form-group formControls col-xs-8">
                    <input type="text" class="form-control input-text" name="old_money" value="<?= $model->valid_money?>" readonly disabled >
                </div>
            </div>
            <div class="formControls col-xs-4">
                <label class="form-label col-xs-5 text-right">积分</label>
                <div class="form-group formControls col-xs-8">
                    <input type="text" class="form-control input-text" name="old_integral" value="<?= $model->integral?>" readonly disabled >
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 cl margin-top-20">
            <label class="form-label col-xs-3 text-left">充值金额(元)</label>
            <div class="form-group formControls col-xs-5 text-center">
                <input type="text" class="form-control input-text" name="money" value="" placeholder="" >
                <label class="error"></label>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 cl">
            <label class="form-label col-xs-3 text-left">赠送积分</label>
            <div class="form-group formControls col-xs-5 text-center">
                <input type="text" class="form-control input-text" name="integral" value="" placeholder="" >
                <label class="error"></label>
            </div>
        </div>
    </form>

    <div class="col-xs-12 col-sm-12 cl text-center margin-top-20 margin-bottom-10">
        <button type="button" onclick="recharge()" class='btn btn-hot btn-md margin-right-30'>充值</button>
        <button type="button" class="btn btn-default btn-md layui-layer-close">取消</button>
    </div>
</article>
<script>
    $(function(){
        validateForm();
    });
    function recharge() {
        if($("input[name='money']").val() === ''){
            $("input[name='money']").parent().addClass('has-error');
            $("input[name='money']").next().html('金额必须为正数');
        }
        if($("input[name='integral']").val() === ''){
            $("input[name='integral']").parent().addClass('has-error');
            $("input[name='integral']").next().html('积分必须为正数');
        }

        ajaxSubmitForm('#recharge', '<?= \yii\helpers\Url::to(['member/recharge'])?>');
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

        $("input[name='integral']").change(function(){
            var money = $(this).val();
            if(Common.isNumber(money)){
                $(this).parent().removeClass('has-error');
                $(this).next().html('');
            } else {
                $(this).parent().addClass('has-error');
                $(this).next().html('积分必须为正数');
            }
        });
    }
</script>
