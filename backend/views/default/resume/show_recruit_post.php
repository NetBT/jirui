<article class="page-container">
    <div class="col-xs-12 col-sm-12 cl text-center margin-top-10">
        <label class="form-label col-xs-2 text-right">店铺名称</label>
        <div class="formControls col-xs-10 text-left" style="margin-top: 3px;"><?= $info['business_name']?></div>
    </div>

    <div class="col-xs-12 col-sm-12 cl text-center margin-top-10">
        <label class="form-label col-xs-2 text-right">地址</label>
        <div class="formControls col-xs-10 text-left" style="margin-top: 3px;"><?= $info['address']?></div>
    </div>

    <div class="col-xs-12 col-sm-12 cl text-center margin-top-10">
        <label class="form-label col-xs-2 text-right">招聘标题</label>
        <div class="formControls col-xs-10 text-left" style="margin-top: 3px;"><?= $info['recruit_title']?></div>
    </div>

    <div class="col-xs-6 col-sm-6 cl text-center margin-top-10">
        <label class="form-label col-xs-4 text-right">职位名称</label>
        <div class="formControls col-xs-7 text-left" style="margin-top: 3px;"><?= $info['post_id']?></div>
    </div>

    <div class="col-xs-6 col-sm-6 cl text-center margin-top-10">
        <label class="form-label col-xs-4 text-right">工作年限</label>
        <div class="formControls col-xs-7 text-left" style="margin-top: 3px;"><?= $info['working_duration']?></div>
    </div>

    <div class="col-xs-6 col-sm-6 cl text-center margin-top-10">
        <label class="form-label col-xs-4 text-right">工作地址</label>
        <div class="formControls col-xs-7 text-left" style="margin-top: 3px;"><?= $info['degree']?></div>
    </div>
    <div class="col-xs-6 col-sm-6 cl text-center margin-top-10">
        <label class="form-label col-xs-4 text-right">要求薪资</label>
        <div class="formControls col-xs-7 text-left" style="margin-top: 3px;"><?= $info['expected_salary']?></div>
    </div>

    <div class="col-xs-12 col-sm-12 cl text-center margin-top-10">
        <label class="form-label col-xs-2 text-right">店铺介绍</label>
        <div class="formControls col-xs-10 text-left" style="margin-top: 3px;"><?= $info['shop_introduced']?></div>
    </div>

    <div class="col-xs-12 col-sm-12 cl text-center margin-top-10">
        <label class="form-label col-xs-2 text-right">任职要求</label>
        <div class="formControls col-xs-10 text-left" style="margin-top: 3px;"><?= $info['job_specification']?></div>
    </div>


    <?php if(!\backend\models\Common::getBusinessId()) : ?>
    <div class="col-xs-12 col-sm-12 cl text-center margin-top-30 margin-bottom-10">
        <?php if($info['is_send']) : ?>
            <button type="button" onclick="already()" class='btn btn-default btn-md margin-right-30 disabled'>已投</button>
        <?php else:?>
        <button type="button" onclick="save(this,<?= $info['id']?>)" id="saveBtn" class='btn btn-hot btn-md margin-right-30'>投递简历</button>
        <?php endif;?>
    </div>
    <?php endif;?>
</article>


<script>


    function save(obj,id)
    {
        var $obj = $(obj);
        layer.confirm('确定使用您的默认简历投递该职位？',function(index){
            ajaxSubmit('<?= \yii\helpers\Url::to(['resume/apply'])?>', {id: id}, function () {
                $obj.attr('onclick','already()');
                $obj.removeClass('btn-hot');
                $obj.addClass('btn-default disabled');
                $obj.html('已投');
            });
        });
    }
</script>