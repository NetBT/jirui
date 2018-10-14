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
        <label class="form-label col-xs-4 text-right" style="width: 106px;">职位名称</label>
        <div class="formControls col-xs-7 text-left" style="margin-top: 3px;"><?= $info['post_id']?></div>
    </div>

    <div class="col-xs-6 col-sm-6 cl text-center margin-top-10">
        <label class="form-label col-xs-4 text-right">工作年限</label>
        <div class="formControls col-xs-7 text-left" style="margin-top: 3px;"><?= $info['working_duration']?></div>
    </div>

    <div class="col-xs-6 col-sm-6 cl text-center margin-top-10">
        <label class="form-label col-xs-4 text-right" style="width: 106px;">工作地址</label>
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


    <?php if(\backend\models\Common::getBusinessId() == 1) : ?>
    <div class="col-xs-12 col-sm-12 cl text-center margin-top-30 margin-bottom-10">
        <div class="col-xs-12 col-sm-12 cl text-center margin-top-10 margin-bottom-10">
            <?php if(in_array($info['check_status'],[\common\models\Status::RECRUIT_CHECK_STATUS_NO,\common\models\Status::RECRUIT_CHECK_STATUS_ING])) : ?>
                <button type="button" onclick="recruitCheck.checkRecruitModel(this,<?= $info['id']?>,<?=\common\models\Status::RECRUIT_CHECK_STATUS_YES?>)" class='btn btn-success btn-md margin-right-30' title="通过">通过</button>

                <button type="button" onclick="recruitCheck.checkRecruitModel(this,<?= $info['id']?>,<?=\common\models\Status::RECRUIT_CHECK_STATUS_WTG?>)" class='btn btn-hot btn-md margin-right-30' title="不通过">不通过</button>

            <?php endif;?>

            <?php if($info['check_status'] == \common\models\Status::RECRUIT_CHECK_STATUS_YES) :?>
                <button type="button" onclick="" class='btn btn-default btn-md margin-right-30 disabled'>已通过</button>
            <?php endif;?>

            <?php if($info['check_status'] == \common\models\Status::RECRUIT_CHECK_STATUS_WTG) :?>
                <button type="button" onclick="" class='btn btn-default btn-md margin-right-30 disabled'>未通过</button>
            <?php endif;?>
        </div>
    </div>
    <?php endif;?>
</article>
