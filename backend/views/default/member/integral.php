<article class="page-container">
    <form id="integral" class="form form-horizontal" action="" method="post">
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
            <label class="form-label col-xs-3 text-left">操作类型</label>
            <div class="form-group formControls col-xs-5 text-center">
                <div>
                    <?php foreach (\common\models\Status::memberIntegralMap() as $key => $value) :?>
                        <label class="radio-inline"><input name="type" value="<?= $key?>" <?php if($key == \common\models\Status::MEMBER_INTEGRAL_ADD):?> checked <?php endif;?>
                                                           type="radio"><?= $value?></label>
                    <?php endforeach;?>
                </div>
                <label class="error"></label>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 cl">
            <label class="form-label col-xs-3 text-left">现有积分</label>
            <div class="form-group formControls col-xs-5 text-center">
                <input type="text" class="form-control input-text" name="old_integral" id="old_integral" value="<?= $model->integral?>" placeholder="" disabled readonly>
                <label class="error"></label>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 cl">
            <label class="form-label col-xs-3 text-left">输入积分</label>
            <div class="form-group formControls col-xs-5 text-center">
                <input type="text" class="form-control input-text" name="change_integral" id="change_integral" value="" placeholder="不输入则不变化" >
                <label class="error"></label>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 cl">
            <label class="form-label col-xs-3 text-left">积分变为</label>
            <div class="form-group formControls col-xs-5 text-center">
                <input type="text" class="form-control input-text" name="new_integral" id="new_integral" value="" placeholder="" readonly>
                <label class="error"></label>
            </div>
        </div>
    </form>

    <div class="col-xs-12 col-sm-12 cl text-center margin-top-20 margin-bottom-10">
        <button type="button" onclick="save()" class='btn btn-hot btn-md margin-right-30'>保存</button>
        <button type="button" class="btn btn-default btn-md layui-layer-close">取消</button>
    </div>
</article>
<script>
    $(function(){
        validateForm();
    });
    function save() {
        getNewIntegral();
        ajaxSubmitForm('#integral', '<?= \yii\helpers\Url::to(['member/integral'])?>');
    }

    //验证积分和充值金额必须为数字
    function validateForm(){
        $("input").change(function(){
            var $changeIntegral = $("#change_integral");
            var changeIntegral = $changeIntegral.val();
            if($(this).attr('name') == 'change_integral'){
                if(Common.isNumber(changeIntegral)){
                    $changeIntegral.parent().removeClass('has-error');
                    $changeIntegral.next().html('');
                    getNewIntegral();
                } else {
                    $changeIntegral.parent().addClass('has-error');
                    $changeIntegral.next().html('积分必须为正数');
                }
            } else {
                getNewIntegral();
            }
        });
    }

    function getNewIntegral(){
        var type = $("input[name='type']:checked").val();
        var oldIntegral = $("#old_integral").val();
        var changeIntegral = $("#change_integral").val() ? $("#change_integral").val() : 0;
        var newIntegral = '';
        switch (type){
            case '<?= \common\models\Status::MEMBER_INTEGRAL_ADD?>':
                newIntegral = parseInt(oldIntegral) + parseInt(changeIntegral);
                $("#new_integral").val(newIntegral);
                break;
            case '<?= \common\models\Status::MEMBER_INTEGRAL_SUBTRACT?>':
                newIntegral = parseInt(oldIntegral) - parseInt(changeIntegral);
                $("#new_integral").val(newIntegral);
                break;
        }
    }
</script>
