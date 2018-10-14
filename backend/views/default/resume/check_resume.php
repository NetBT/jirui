<article class="page-container">
        <div class="user_left cox-xs-7">
            <div class="user_left_box" style="height: auto">
                <p class="bt"><b>基本信息</b></p>
                <div class="col-xs-6 col-sm-6 cl text-center margin-top-10">
                    <label class="form-label col-xs-5 text-right">简历标题</label>
                    <div class="formControls col-xs-7 text-left" style="margin-top: 3px;"><?= $info['resume_title']?></div>
                </div>

                <div class="col-xs-6 col-sm-6 cl text-center margin-top-10">
                    <label class="form-label col-xs-5 text-right">真实姓名</label>
                    <div class="formControls col-xs-7 text-left" style="margin-top: 3px;"><?= $info['name']?></div>
                </div>

                <div class="col-xs-6 col-sm-6 cl text-center margin-top-10">
                    <label class="form-label col-xs-5 text-right">手机号</label>
                    <div class="formControls col-xs-7 text-left" style="margin-top: 3px;"><?= $info['tel']?></div>
                </div>

                <div class="col-xs-6 col-sm-6 cl text-center margin-top-10">
                    <label class="form-label col-xs-5 text-right">微信</label>
                    <div class="formControls col-xs-7 text-left" style="margin-top: 3px;"><?= $info['wechat']?></div>
                </div>

                <div class="col-xs-6 col-sm-6 cl text-center margin-top-10">
                    <label class="form-label col-xs-5 text-right">性别</label>
                    <div class="formControls col-xs-7 text-left" style="margin-top: 3px;"><?= $info['sex']?></div>
                </div>

                <div class="col-xs-6 col-sm-6 cl text-center margin-top-10">
                    <label class="form-label col-xs-5 text-right">婚姻</label>
                    <div class="formControls col-xs-7 text-left" style="margin-top: 3px;"><?= $info['marriage']?></div>
                </div>

                <div class="col-xs-6 col-sm-6 cl text-center margin-top-10">
                    <label class="form-label col-xs-5 text-right">年龄</label>
                    <div class="formControls col-xs-7 text-left" style="margin-top: 3px;"><?= $info['age']?></div>
                </div>

                <div class="col-xs-6 col-sm-6 cl text-center margin-top-10">
                    <label class="form-label col-xs-5 text-right">民族</label>
                    <div class="formControls col-xs-7 text-left" style="margin-top: 3px;"><?= $info['nation']?></div>
                </div>

                <div class="col-xs-6 col-sm-6 cl text-center margin-top-10">
                    <label class="form-label col-xs-5 text-right">email</label>
                    <div class="formControls col-xs-7 text-left" style="margin-top: 3px;"><?= $info['email']?></div>
                </div>

                <div class="col-xs-6 col-sm-6 cl text-center margin-top-10">
                    <label class="form-label col-xs-5 text-right">学历</label>
                    <div class="formControls col-xs-7 text-left" style="margin-top: 3px;"><?= $info['degree']?></div>
                </div>

                <div class="col-xs-6 col-sm-6 cl text-center margin-top-10">
                    <label class="form-label col-xs-5 text-right">籍贯</label>
                    <div class="formControls col-xs-7 text-left" style="margin-top: 3px;"><?= $info['province']?></div>
                </div>

                <div class="col-xs-6 col-sm-6 cl text-center margin-top-10">
                    <label class="form-label col-xs-5 text-right">毕业院校</label>
                    <div class="formControls col-xs-7 text-left" style="margin-top: 3px;"><?= $info['school']?></div>
                </div>

                <div class="col-xs-6 col-sm-6 cl text-center margin-top-10">
                    <label class="form-label col-xs-5 text-right">地址</label>
                    <div class="formControls col-xs-7 text-left" style="margin-top: 3px;"><?= $info['address']?></div>
                </div>

                <div class="col-xs-6 col-sm-6 cl text-center margin-top-10">
                    <label class="form-label col-xs-5 text-right">工作年限</label>
                    <div class="formControls col-xs-7 text-left" style="margin-top: 3px;"><?= $info['working_duration']?></div>
                </div>

                <div class="col-xs-6 col-sm-6 cl text-center margin-top-10">
                    <label class="form-label col-xs-5 text-right">是否工作</label>
                    <div class="formControls col-xs-7 text-left" style="margin-top: 3px;"><?= $info['working_status']?></div>
                </div>

                <div class="col-xs-6 col-sm-6 cl text-center margin-top-10">
                    <label class="form-label col-xs-5 text-right">期望薪资</label>
                    <div class="formControls col-xs-7 text-left" style="margin-top: 3px;"><?= $info['expected_salary']?></div>
                </div>

            </div>
        </div>

        <div class="user_right cox-xs-5">
            <div class="user_right_box">
                <p class="bt"><b>教育经历</b></p>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" id="table-education">
                    <thead>
                    <tr>
                        <th scope="col">毕业时间</th>
                        <th scope="col">学校</th>
                        <th scope="col">专业</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(isset($education) && !empty($education)) : ?>
                        <?php foreach ($education as $key => $value) : ?>
                            <tr id="table-education-<?= $value['id']?>">
                                <td><?= $value['final']?></td>
                                <td><?= $value['school_name']?></td>
                                <td><?= $value['major']?></td>
                            </tr>
                        <?php endforeach;?>
                    <?php endif;?>
                    </tbody>
                </table>
            </div>
            <div class="user_right_box">
                <p class="bt"><b>工作经验</b></p>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" id="table-working">
                    <thead>
                    <tr>
                        <th scope="col">日期</th>
                        <th scope="col">公司名称</th>
                        <th scope="col">职位名称</th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php if(isset($working) && !empty($working)) : ?>
                        <?php foreach ($working as $key => $value) : ?>
                            <tr id="table-working-<?= $value['id']?>">
                                <td><?= $value['start_time'] .'-'. $value['end_time']?></td>
                                <td><?= $value['company_name']?></td>
                                <td><?= $value['post_name']?></td>
                            </tr>
                        <?php endforeach;?>
                    <?php endif;?>
                    </tbody>
                </table>
            </div>
            <div class="user_right_box">
                <p class="bt"><b>自我评价</b></p>
                <textarea id="self_assessment" placeholder="请简单描述您的个人优势"><?= $info['self_assessment']?></textarea>
            </div>
        </div>
        <div class="clear"></div>

    <div class="col-xs-12 col-sm-12 cl text-center margin-top-10 margin-bottom-10">
        <?php if(in_array($info['check_status'],[\common\models\Status::RESUME_CHECK_STATUS_NO,\common\models\Status::RESUME_CHECK_STATUS_ING])) : ?>
            <button type="button" onclick="resumeCheck.checkResumeModel(this,<?= $info['id']?>,<?=\common\models\Status::RESUME_CHECK_STATUS_YES?>)" class='btn btn-success btn-md margin-right-30' title="通过">通过</button>

            <button type="button" onclick="resumeCheck.checkResumeModel(this,<?= $info['id']?>,<?=\common\models\Status::RESUME_CHECK_STATUS_WTG?>)" class='btn btn-hot btn-md margin-right-30' title="不通过">不通过</button>

        <?php endif;?>

        <?php if($info['check_status'] == \common\models\Status::RESUME_CHECK_STATUS_YES) :?>
            <button type="button" onclick="" class='btn btn-default btn-md margin-right-30 disabled'>已通过</button>
        <?php endif;?>

        <?php if($info['check_status'] == \common\models\Status::RESUME_CHECK_STATUS_WTG) :?>
            <button type="button" onclick="" class='btn btn-default btn-md margin-right-30 disabled'>未通过</button>
        <?php endif;?>
    </div>
</article>
