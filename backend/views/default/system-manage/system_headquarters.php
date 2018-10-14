

<div class="main-page col-xs-12 ">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-3"><b>配置管理</b>Configuration Management</div>
        <div class="header-search col-xs-8">
        </div>
        <div class="clear"></div>
    </div>
    <div class="main-body col-xs-12">

        <div id="tab-system" class="HuiTab">
            <div class="tabBar cl">
                <span>网站设置</span>
                <span>招聘个人设置</span>
                <span>招聘店铺设置</span>
                <span>店铺招聘套餐设置</span>
                <span>其他设置</span>
            </div>
            <div class="tabCon margin-top-10">
                <form name="system_site" id="system_site" enctype="multipart/form-data">
                    <table class="tab system" width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tbody>
                        <tr>
                            <td class="text-left font-sm">网站名称</td>
                            <td class="text-left">
                                <input type="type" value="<?= $list['web_name']?>" class="input-text" style="width:20%" name="web_name">
                            </td>
                        </tr>
<!--                        <tr>-->
<!--                            <td class="text-left font-sm">图片域名</td>-->
<!--                            <td class="text-left">-->
<!--                                <input type="type" value="--><?//= $list['img_url']?><!--" class="input-text" style="width:20%" name="img_url">-->
<!--                            </td>-->
<!--                        </tr>-->
                        <tr>
                            <td class="text-left font-sm">会员注册开关</td>
                            <td class="text-left">
                                <label class="radio-inline"><input type="radio" <?php if($list['member_register_on_off'] == 1) : ?>checked<?php endif;?> name="member_register_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['member_register_on_off'] == 2) : ?>checked<?php endif;?> name="member_register_on_off" value="2">关闭</label>
                            </td>
                        </tr>

<!--                        <tr>-->
<!--                            <td class="text-left font-sm">邮箱激活开关</td>-->
<!--                            <td class="text-left">-->
<!--                                <label class="radio-inline"><input type="radio" --><?php //if($list['email_on_off'] == 1) : ?><!--checked--><?php //endif;?><!-- name="email_on_off" value="1">开启</label>-->
<!--                                <label class="radio-inline"><input type="radio" --><?php //if($list['email_on_off'] == 2) : ?><!--checked--><?php //endif;?><!-- name="email_on_off" value="2">关闭</label>-->
<!--                            </td>-->
<!--                        </tr>-->
<!---->
<!--                        <tr>-->
<!--                            <td class="text-left font-sm">更新时间开关</td>-->
<!--                            <td class="text-left">-->
<!--                                <label class="radio-inline"><input type="radio" --><?php //if($list['update_time_on_off'] == 1) : ?><!--checked--><?php //endif;?><!-- name="update_time_on_off" value="1">开启</label>-->
<!--                                <label class="radio-inline"><input type="radio" --><?php //if($list['update_time_on_off'] == 2) : ?><!--checked--><?php //endif;?><!-- name="update_time_on_off" value="2">关闭</label>-->
<!--                            </td>-->
<!--                        </tr>-->

                        <tr>
                            <td class="text-left font-sm">未登录提醒</td>
                            <td class="text-left">
                                <input type="type" value="<?= $list['notice_not_login']?>" class="input-text" style="width:5%" name="notice_not_login">天未登录提醒【为空则不提醒】
                            </td>
                        </tr>

                        <tr>
                            <td class="text-left font-sm">加盟商到期提醒</td>
                            <td class="text-left">
                                <input type="type" value="<?= $list['notice_ab_expire']?>" class="input-text" style="width:5%" name="notice_ab_expire">天到期前提醒【为空则不提醒】
                            </td>
                        </tr>

<!--                        <tr>-->
<!--                            <td class="text-left font-sm">联系电话（顶部）</td>-->
<!--                            <td class="text-left">-->
<!--                                <input type="type" value="--><?//= $list['tel_top']?><!--" class="input-text" style="width:20%" name="tel_top">-->
<!--                            </td>-->
<!--                        </tr>-->

                        <tr>
                            <td class="text-left font-sm">联系电话（底部）</td>
                            <td class="text-left">
                                <input type="type" value="<?= $list['tel_bottom']?>" class="input-text" style="width:20%" name="tel_bottom">
                            </td>
                        </tr>

                        <tr>
                            <td class="text-left font-sm">联系地址（底部）</td>
                            <td class="text-left">
                                <input type="type" value="<?= $list['link_address']?>" class="input-text" style="width:20%" name="link_address">
                            </td>
                        </tr>

                        <tr>
                            <td class="text-left font-sm">网站其他说明</td>
                            <td class="text-left">
                                <input type="type" value="<?= $list['web_other']?>" class="input-text" style="width:20%" name="web_other">
                            </td>
                        </tr>

                        <tr>
                            <td class="text-left font-sm">网站备案号ICP</td>
                            <td class="text-left">
                                <input type="type" value="<?= $list['web_ICP']?>" class="input-text" style="width:20%" name="web_ICP">
                            </td>
                        </tr>

                        <tr>
                            <td class="text-left font-sm">网站LOGO</td>
                            <td class="text-left">
                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="fileupload-new thumbnail" style="width: 100px; height: 85px;">
                                        <img src="<?= '/uploads/'.$list['web_logo']?>" alt=""/>
                                    </div>
                                    <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                    <div>
                                <span class="btn btn-file btn-primary size-MINI">
                                    <span class="fileupload-new">选择图片</span>
                                    <span class="fileupload-exists">重新选择</span>
                                    <input type="file" name="web_logo">
                                </span>
                                        <span class="btn btn-danger fileupload-exists size-MINI" data-dismiss="fileupload">移除图片</span>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-left font-sm">网站关闭开关</td>
                            <td class="text-left">
                                <label class="radio-inline"><input type="radio" <?php if($list['web_close_on_off'] == 1) : ?>checked<?php endif;?> name="web_close_on_off" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['web_close_on_off'] == 2) : ?>checked<?php endif;?> name="web_close_on_off" value="2">关闭</label>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-left font-sm">关闭原因</td>
                            <td class="text-left font-sm">
                                <textarea class="form-control col-md-2" rows="3" style="width:30%;resize: none" name="web_close_reason"><?= $list['web_close_reason']?></textarea>
                            </td>
                        </tr>



                        <tr>
                            <td class=""></td>
                            <td class="text-left">
                                <button type="button" class="btn btn-secondary btn-sm" onclick="doSave('#system_site')"><i class="Hui-iconfont">&#xe632;</i>保存修改配置</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </form>
            </div>

            <div class="tabCon margin-top-10">
                <form name="recruiter_personage" id="recruiter_personage" enctype="multipart/form-data">
                    <table class="tab system" width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tbody>
                        <tr>
<!--                            <td class="text-left font-sm" rowspan="5"><label>基础设置</label></td>-->
                            <td class="text-left font-sm" rowspan="2"><label>基础设置</label></td>
                            <td class="text-left">
                                <span>会员最大发布简历</span>
                                <input type="type" value="<?= $list['member_max_issue_resume']?>" class="input-text" style="width:20%" name="member_max_issue_resume">份【为0则不限制】
                            </td>
                        </tr>

                        <tr>

                            <td class="text-left">
                                <span>每天最大申请职位</span>
                                <input type="type" value="<?= $list['everyday_max_apply_post']?>" class="input-text" style="width:20%" name="everyday_max_apply_post">个【为0则不限制】
                            </td>
                        </tr>

                        <tr style="display: none">
                            <td class="text-left">
                                <span>简历照片限制</span>
                                <input type="type" value="<?= $list['resume_max_size']?>" class="input-text" style="width:20%" name="resume_max_size">KB【为0则不限制】
                            </td>
                        </tr>

                        <tr style="display: none">
                            <td class="text-left">
                                <span>简历最大列表</span>
                                <input type="type" value="<?= $list['resume_max_list']?>" class="input-text" style="width:20%" name="resume_max_list">条【为0则不限制】
                            </td>
                        </tr>

                        <tr style="display: none">
                            <td class="text-left">
                                <span>刷新简历限制</span>
                                <input type="type" value="<?= $list['resume_refresh_min_time']?>" class="input-text" style="width:20%" name="resume_refresh_min_time">分【为0则不限制】
                            </td>
                        </tr>

                        <tr>
                            <td class="text-left font-sm" rowspan="2"><label>审核与认证设置</label></td>
                            <td class="text-left">
                                <span>新简历默认状态</span>
                                <label class="radio-inline"><input type="radio" <?php if($list['new_resume_status'] == 1) : ?>checked<?php endif;?> name="new_resume_status" value="1">未通过</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['new_resume_status'] == 2) : ?>checked<?php endif;?> name="new_resume_status" value="2">审核中</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['new_resume_status'] == 3) : ?>checked<?php endif;?> name="new_resume_status" value="3">通过</label>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-left">
                                <span>修改后默认状态</span>
                                <label class="radio-inline"><input type="radio" <?php if($list['update_resume_status'] == 1) : ?>checked<?php endif;?> name="update_resume_status" value="1">未通过</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['update_resume_status'] == 2) : ?>checked<?php endif;?> name="update_resume_status" value="2">审核中</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['update_resume_status'] == 3) : ?>checked<?php endif;?> name="update_resume_status" value="3">通过</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['update_resume_status'] == 4) : ?>checked<?php endif;?> name="update_resume_status" value="4">保持不变</label>
                            </td>
                        </tr>

                        <tr>
<!--                            <td class="text-left font-sm" rowspan="2"><label>查看简历</label></td> -->
                            <td class="text-left font-sm" rowspan="1"><label>查看简历</label></td>
                            <td class="text-left">
                                <span>店铺查看收到的简历无需下载</span>
                                <label class="radio-inline"><input type="radio" <?php if($list['ab_visible_resume_is_download'] == 1) : ?>checked<?php endif;?> name="ab_visible_resume_is_download" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['ab_visible_resume_is_download'] == 2) : ?>checked<?php endif;?> name="ab_visible_resume_is_download" value="2">关闭</label>
                            </td>

                        </tr>

                        <tr style="display: none">
                            <td class="text-left">
                                <span>Web端允许查看简历联系方式</span>
                                <label class="radio-inline"><input type="radio" <?php if($list['web_visible_resume_link'] == 1) : ?>checked<?php endif;?> name="web_visible_resume_link" value="1">已登录会员可见</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['web_visible_resume_link'] == 2) : ?>checked<?php endif;?> name="web_visible_resume_link" value="2">下载后可见</label>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-left font-sm"><label>简历下载设置</label></td>
                            <td class="text-left">
                                <span>简历下载要求</span>
                                <label class="radio-inline"><input type="radio" <?php if($list['resume_download_condition'] == 1) : ?>checked<?php endif;?> name="resume_download_condition" value="1">有发布职位的店铺可下载</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['resume_download_condition'] == 2) : ?>checked<?php endif;?> name="resume_download_condition" value="2">所有店铺</label>
                            </td>
                        </tr>

                        <tr style="display: none">
                            <td class="text-left font-sm"><label>高级人才设置</label></td>
                            <td class="text-left">
                                <span>申请高级人才简历完整指数要求</span>
                                <label class="radio-inline"><input type="radio" <?php if($list['high_person_resume_condition'] == 1) : ?>checked<?php endif;?> name="high_person_resume_condition" value="1">>=60%</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['high_person_resume_condition'] == 2) : ?>checked<?php endif;?> name="high_person_resume_condition" value="2">>=75%</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['high_person_resume_condition'] == 3) : ?>checked<?php endif;?> name="high_person_resume_condition" value="3">>=85%</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['high_person_resume_condition'] == 4) : ?>checked<?php endif;?> name="high_person_resume_condition" value="4">>=100%</label>
                            </td>
                        </tr>

                        <tr>
                            <td class=""></td>
                            <td class="text-left">
                                <button type="button" class="btn btn-secondary btn-sm" onclick="doSave('#recruiter_personage')"><i class="Hui-iconfont">&#xe632;</i>保存</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </form>
            </div>

            <div class="tabCon margin-top-10">
                <form name="recruiter_ab" id="recruiter_ab" enctype="multipart/form-data">
                    <table class="tab system" width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tbody>
                        <tr>
<!--                            <td class="text-left font-sm" rowspan="3"><label>基础设置</label></td>-->
                            <td class="text-left font-sm" rowspan="1"><label>基础设置</label></td>
                            <td class="text-left">
                                <span>发布招聘默认有效期</span>
                                <input type="type" value="<?= $list['issue_recruit_default_day']?>" class="input-text" style="width:20%" name="issue_recruit_default_day">天【为0则不限制】
                            </td>
                        </tr>

                        <tr style="display: none">
                            <td class="text-left">
                                <span>上传营业执照文件限制</span>
                                <input type="type" value="<?= $list['business_license_max_size']?>" class="input-text" style="width:20%" name="business_license_max_size">KB【为0则不限制】
                            </td>
                        </tr>

                        <tr style="display: none">
                            <td class="text-left">
                                <span>职位列表最大条</span>
                                <input type="type" value="<?= $list['max_post_list']?>" class="input-text" style="width:20%" name="max_post_list">条
                            </td>
                        </tr>

                        <tr>
                            <td class="text-left font-sm"><label>查看联系方式设置</label></td>
                            <td class="text-left">
                                <span>Web端允许查看联系方式</span>
                                <label class="radio-inline"><input type="radio" <?php if($list['ab_web_scan_link'] == 1) : ?>checked<?php endif;?> name="ab_web_scan_link" value="1">所有会员</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['ab_web_scan_link'] == 2) : ?>checked<?php endif;?> name="ab_web_scan_link" value="2">已发布简历的会员</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['ab_web_scan_link'] == 3) : ?>checked<?php endif;?> name="ab_web_scan_link" value="3">隐藏</label>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-left font-sm" rowspan="2"><label>认证与审核状态</label></td>
                            <td class="text-left">
                                <span>店铺发布职位后审核状态</span>
                                <label class="radio-inline"><input type="radio" <?php if($list['ab_issue_post_status'] == 1) : ?>checked<?php endif;?> name="ab_issue_post_status" value="1">未通过</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['ab_issue_post_status'] == 2) : ?>checked<?php endif;?> name="ab_issue_post_status" value="2">审核中</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['ab_issue_post_status'] == 3) : ?>checked<?php endif;?> name="ab_issue_post_status" value="3">通过</label>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-left">
                                <span>店铺修改职位后审核状态</span>
                                <label class="radio-inline"><input type="radio" <?php if($list['ab_update_post_status'] == 1) : ?>checked<?php endif;?> name="ab_update_post_status" value="1">未通过</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['ab_update_post_status'] == 2) : ?>checked<?php endif;?> name="ab_update_post_status" value="2">审核中</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['ab_update_post_status'] == 3) : ?>checked<?php endif;?> name="ab_update_post_status" value="3">通过</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['ab_update_post_status'] == 4) : ?>checked<?php endif;?> name="ab_update_post_status" value="4">保持不变</label>
                            </td>
                        </tr>

                        <tr style="display: none">
                            <td class="text-left font-sm"><label>过期信息显示</label></td>
                            <td class="text-left">
                                <span>显示过期信息</span>
                                <label class="radio-inline"><input type="radio" <?php if($list['ab_past_due_show'] == 1) : ?>checked<?php endif;?> name="ab_past_due_show" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['ab_past_due_show'] == 2) : ?>checked<?php endif;?> name="ab_past_due_show" value="2">关闭</label>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-left font-sm"><label>其他设置</label></td>
                            <td class="text-left">
                                <span>店铺名称重复</span>
                                <label class="radio-inline"><input type="radio" <?php if($list['ab_name_repetition'] == 1) : ?>checked<?php endif;?> name="ab_name_repetition" value="1">开启</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['ab_name_repetition'] == 2) : ?>checked<?php endif;?> name="ab_name_repetition" value="2">关闭</label>
                            </td>
                        </tr>

                        <tr>
                            <td class=""></td>
                            <td class="text-left">
                                <button type="button" class="btn btn-secondary btn-sm" onclick="doSave('#recruiter_ab')"><i class="Hui-iconfont">&#xe632;</i>保存</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </form>
            </div>

            <div class="tabCon margin-top-10">
                <form name="combo_ab" id="combo_ab" enctype="multipart/form-data">
                    <table class="tab system" width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tbody>
                        <tr>
                            <td class="text-left font-sm"><label>会员赠送服务</label></td>
                            <td class="text-left">
                                <label class="radio-inline"><input type="radio" <?php if($list['member_present_serve'] == 1) : ?>checked<?php endif;?> name="member_present_serve" value="1">普通会员</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['member_present_serve'] == 2) : ?>checked<?php endif;?> name="member_present_serve" value="2">金牌会员</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['member_present_serve'] == 3) : ?>checked<?php endif;?> name="member_present_serve" value="2">钻石会员</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['member_present_serve'] == 4) : ?>checked<?php endif;?> name="member_present_serve" value="2">VIP会员</label>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-left font-sm"><label>套餐过期后自动变更为</label></td>
                            <td class="text-left">
                                <label class="radio-inline"><input type="radio" <?php if($list['member_over_serve'] == 1) : ?>checked<?php endif;?> name="member_over_serve" value="1">普通会员</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['member_over_serve'] == 2) : ?>checked<?php endif;?> name="member_over_serve" value="2">金牌会员</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['member_over_serve'] == 3) : ?>checked<?php endif;?> name="member_over_serve" value="2">钻石会员</label>
                                <label class="radio-inline"><input type="radio" <?php if($list['member_over_serve'] == 4) : ?>checked<?php endif;?> name="member_over_serve" value="2">VIP会员</label>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-left font-sm"><label>服务到期时间提醒</label></td>
                            <td class="text-left">
                                <input type="type" value="<?= $list['member_over_notify_day']?>" class="input-text" style="width:20%" name="member_over_notify_day">天前
                            </td>
                        </tr>

                        <tr>
                            <td class=""></td>
                            <td class="text-left">
                                <button type="button" class="btn btn-secondary btn-sm" onclick="doSave('#combo_ab')"><i class="Hui-iconfont">&#xe632;</i>保存</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </form>
            </div>

            <div class="tabCon margin-top-10">
                <form name="system_other" id="system_other" enctype="multipart/form-data">
                    <table class="tab system" width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tbody>
                        <tr>
                            <td class="text-left font-sm" width="200">微信二维码</td>
                            <td class="text-left">
                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="fileupload-new thumbnail" style="width: 100px; height: 85px;">
                                        <img src="<?= '/uploads/'.$list['wechat_qr_code']?>" alt=""/>
                                    </div>
                                    <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                    <div>
                                <span class="btn btn-file btn-primary size-MINI">
                                    <span class="fileupload-new">选择图片</span>
                                    <span class="fileupload-exists">重新选择</span>
                                    <input type="file" name="wechat_qr_code">
                                </span>
                                        <span class="btn btn-danger fileupload-exists size-MINI" data-dismiss="fileupload">移除图片</span>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-left font-sm" width="200">支付宝二维码</td>
                            <td class="text-left">
                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="fileupload-new thumbnail" style="width: 100px; height: 85px;">
                                        <img src="<?= '/uploads/'.$list['alipay_qr_code']?>" alt=""/>
                                    </div>
                                    <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                    <div>
                                <span class="btn btn-file btn-primary size-MINI">
                                    <span class="fileupload-new">选择图片</span>
                                    <span class="fileupload-exists">重新选择</span>
                                    <input type="file" name="alipay_qr_code">
                                </span>
                                        <span class="btn btn-danger fileupload-exists size-MINI" data-dismiss="fileupload">移除图片</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class=""></td>
                            <td class="text-left">
                                <button type="button" class="btn btn-secondary btn-sm" onclick="doSave('#system_other')"><i class="Hui-iconfont">&#xe632;</i>保存修改配置</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </form>
            </div>

        </div>
    </div>
    <script type="text/javascript">
        $(function(){
            $('.skin-minimal input').iCheck({
                checkboxClass: 'icheckbox-blue',
                radioClass: 'iradio-blue',
                increaseArea: '20%'
            });
            $("#tab-system").Huitab({
                index:0
            });
        });
        /**
         * 保存修改操作
         */
        function doSave(formID)
        {
            var option = ({
                url : '<?= \yii\helpers\Url::to(['system-manage/do-save'])?>',
                type : 'POST',
                async: false,
                dataType : 'JSON',
                success: function(data)
                {
                    var callBackFunction = '';
                    if(data.code == 1000)
                    {
                        layer.closeAll('page');
                        callBackFunction  = DataTable.drawTable();
                    }
                    layer.msg(data.message,{icon:6,time:2000},callBackFunction);
                },
                error: function()
                {
                    layer.msg('网络错误',{icon:5,time:2000});
                }
            });
            $(formID).ajaxSubmit(option);
        }

    </script>