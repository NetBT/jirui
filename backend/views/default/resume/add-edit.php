<div class="user_main col-xs-12">
    <input type="hidden" id="hiddenResumeId" value="<?= $model->id?>">
    <div class="user_left cox-xs-7">
        <div class="user_left_box">
            <p class="bt"><b>个人信息</b></p>
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                    'id' => 'resumeBasic',
                    'options' => ['class' => 'form form-horizontal'],
                    'fieldConfig'=>[
                        'inputOptions'=>['class'=>'form-control input-text'],//改变input输入框
//                'options' => [],//改变外层自动生成的div
//                'labelOptions' => [],//改变label
                        'labelOptions' => [
                            'class' => 'form-label col-xs-4 text-right'
                        ],
                        'errorOptions' => [                                 //修改error样式和标签
                            'tag' => 'label',
                            'class' => 'error'
                        ],
                    ],
                    'method' => 'post',
                    'enableAjaxValidation' => true,
                    'validationUrl' => \yii\helpers\Url::to(['resume/validate-form','type' => 'addEdit']),
                    'enableClientValidation' => true,
                    'validateOnSubmit'=> true,     //提交时的验证
                ]
            );
            ?>

            <?= $form->field($model,'id',[
                'template' => '{input}',
                'options' => ['class' => ''],
            ])->hiddenInput()?>

            <div class="col-xs-6 col-sm-6 cl text-center">
                <?= $form->field($model,'resume_title',[
                    'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
                ])->textInput(['placeholder'=>"简历标题"])?>
            </div>

            <div class="col-xs-6 col-sm-6 cl text-center">
                <?= $form->field($model,'name',[
                    'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
                ])->textInput(['placeholder'=>"真实姓名"])?>
            </div>

            <div class="col-xs-6 col-sm-6 cl text-center">
                <?= $form->field($model,'tel',[
                    'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
                ])->textInput(['placeholder'=>"手机号"])?>
            </div>

            <div class="col-xs-6 col-sm-6 cl text-center">
                <?= $form->field($model,'wechat',[
                    'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
                ])->textInput(['placeholder'=>"微信"])?>
            </div>

            <div class="col-xs-6 col-sm-6 cl text-center">
                <?= $form->field($model,'sex', [
                ])->inline()->radioList(\common\models\Status::sexyMap(),[
                    'template' => '{label}<div class="formControls col-xs-5">{input}</div>',
                ])?>
            </div>

            <div class="col-xs-6 col-sm-6 cl text-center">
                <?= $form->field($model,'marriage', [
                ])->inline()->radioList(\common\models\Status::marriageMap(),[
                    'template' => '{label}<div class="formControls col-xs-6">{input}</div>',
                ])?>
            </div>

            <div class="col-xs-6 col-sm-6 cl text-center">
                <?= $form->field($model,'birthday',[
                    'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
                ])->textInput(['placeholder'=>"生日", 'id' => 'birthday'])?>
            </div>

            <div class="col-xs-6 col-sm-6 cl text-center">
                <?= $form->field($model,'age',[
                    'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
                ])->textInput(['placeholder'=>"年龄"])?>
            </div>

            <div class="col-xs-6 col-sm-6 cl text-center">
                <?= $form->field($model,'QQ',[
                    'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
                ])->textInput(['placeholder'=>"QQ"])?>
            </div>

            <div class="col-xs-6 col-sm-6 cl text-center">
                <?= $form->field($model,'nation',[
                    'template' => '{label} <div class="formControls col-xs-7">{input}</div>',
                ])->dropDownList(\backend\models\Nation::getList(),[
                    'class' => 'selectpicker',
                    'id' => 'selectNation',
                ])?>
            </div>

            <div class="col-xs-6 col-sm-6 cl text-center">
                <?= $form->field($model,'email',[
                    'template' => '{label}<div class="formControls col-xs-7">{input}{error}</div>',
                ])->textInput(['placeholder'=>"email"])?>
            </div>

            <div class="col-xs-6 col-sm-6 cl text-center">
                <?= $form->field($model,'degree',[
                    'template' => '{label} <div class="formControls col-xs-7">{input}</div>',
                ])->dropDownList(\common\models\Status::degreeMap(),[
                    'class' => 'selectpicker',
                    'id' => 'selectDegree',
                ])?>
            </div>

            <div class="col-xs-6 col-sm-6 cl text-center">
                <?= $form->field($model,'province',[
                    'template' => '{label}<div class="formControls col-xs-7">{input}{error}</div>',
                ])->textInput(['placeholder'=>"籍贯"])?>
            </div>

            <div class="col-xs-6 col-sm-6 cl text-center">
                <?= $form->field($model,'school',[
                    'template' => '{label}<div class="formControls col-xs-7">{input}{error}</div>',
                ])->textInput(['placeholder'=>"毕业院校"])?>
            </div>

            <div class="col-xs-6 col-sm-6 cl text-center">
                <?= $form->field($model,'address',[
                    'template' => '{label}<div class="formControls col-xs-7">{input}{error}</div>',
                ])->textInput(['placeholder'=>"地址"])?>
            </div>

            <div class="col-xs-6 col-sm-6 cl text-center">
                <?= $form->field($model,'working_duration',[
                    'template' => '{label} <div class="formControls col-xs-7">{input}</div>',
                ])->dropDownList(\common\models\Status::workingDurationLabelMap(),[
                    'class' => 'selectpicker',
                    'id' => 'workingDuration',
                ])?>
            </div>

            <div class="col-xs-6 col-sm-6 cl text-center">
                <?= $form->field($model,'working_status', [
                ])->inline()->radioList(\common\models\Status::workingStatusMap(),[
                    'template' => '{label}<div class="formControls col-xs-6">{input}</div>',
                ])?>
            </div>

            <div class="col-xs-6 col-sm-6 cl text-center">
                <?= $form->field($model,'expected_salary',[
                    'template' => '{label} <div class="formControls col-xs-7">{input}</div>',
                ])->dropDownList(\common\models\Status::expectedSalaryLabelMap(),[
                    'class' => 'selectpicker',
                    'id' => 'expectedSalary',
                ])?>
            </div>

            <?php \yii\bootstrap\ActiveForm::end(); ?>
            <div class="col-xs-12 col-sm-12 cl text-center margin-top-20 margin-bottom-10">
                <button type="button" onclick="save()" id="saveBtn" class='btn btn-hot btn-md margin-right-30'>确认</button>
            </div>
        </div>
    </div>

    <div class="user_right cox-xs-5">
        <div class="user_right_box">
            <p class="bt"><b>教育经历</b><a href="javascript:void(0)" onclick="education.addModal()" class="btn">添加</a></p>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" id="table-education">
                <thead>
                <tr>
                    <th scope="col">毕业时间</th>
                    <th scope="col">学校</th>
                    <th scope="col">专业</th>
                    <th scope="col">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php if(isset($education) && !empty($education)) : ?>
                    <?php foreach ($education as $key => $value) : ?>
                        <tr id="table-education-<?= $value['id']?>">
                            <td><?= $value['final']?></td>
                            <td><?= $value['school_name']?></td>
                            <td><?= $value['major']?></td>
                            <td><a onClick="education.editModal(<?= $value['id']?>)" href="javascript:;" title="编辑"><i class="fa fa-pencil"></i></a>
                                <a onClick="education.deleteModal(this,<?= $value['id']?>)" href="javascript:;" title="删除"><i class="fa fa-remove"></i></a>
                            </td>
                        </tr>
                    <?php endforeach;?>
                <?php endif;?>
                </tbody>
            </table>
        </div>
        <div class="user_right_box">
            <p class="bt"><b>工作经验</b><a href="javascript:void(0)" onclick="working.addModal()" class="btn">添加</a></p>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" id="table-working">
                <thead>
                <tr>
                    <th scope="col">日期</th>
                    <th scope="col">公司名称</th>
                    <th scope="col">职位名称</th>
                    <th scope="col">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php if(isset($working) && !empty($working)) : ?>
                    <?php foreach ($working as $key => $value) : ?>
                        <tr id="table-working-<?= $value['id']?>">
                            <td><?= $value['start_time'] .'-'. $value['end_time']?></td>
                            <td><?= $value['company_name']?></td>
                            <td><?= $value['post_name']?></td>
                            <td><a onClick="working.editModal(<?= $value['id']?>)" href="javascript:;" title="编辑"><i class="fa fa-pencil"></i></a>
                                <a onClick="working.deleteModal(this,<?= $value['id']?>)" href="javascript:;" title="删除"><i class="fa fa-remove"></i></a>
                            </td>
                        </tr>
                    <?php endforeach;?>
                <?php endif;?>
                </tbody>
            </table>
        </div>
        <div class="user_right_box">
            <p class="bt"><b>自我评价</b><a href="javascript:void(0)" onclick="saveSelfAssessment()" class="box">添加</a></p>
            <textarea id="self_assessment" placeholder="请简单描述您的个人优势"></textarea>
        </div>
    </div>
    <div class="clear"></div>
</div>

<script>
    $(function(){
        $("#birthday").on('click', function() {
            WdatePicker({readOnly: true, dateFmt: 'yyyy-MM-dd'});
        });
        $("#selectDegree").selectpicker({
            title: '请选择学历',
            style: 'btn-default',
            width: '100%',
            liveSearch: true
        });
        $("#selectNation").selectpicker({
            title: '请选择民族',
            style: 'btn-default',
            width: '100%',
            liveSearch: true,
            dropupAuto : false
        });

        $("#workingDuration").selectpicker({
            title: '工作年限',
            style: 'btn-default',
            width: '100%',
            liveSearch: true,
            dropupAuto : false
        });

        $("#expectedSalary").selectpicker({
            title: '期望薪资',
            style: 'btn-default',
            width: '100%',
            liveSearch: true,
            dropupAuto : false
        });
    });

    function save() {
        layer.confirm('确定要保存基本信息？',function(index){
            $.ajax({
                url : '<?= \yii\helpers\Url::to(['resume/add-edit'])?>',
                type : 'POST',
                async: false,
                data : $('#resumeBasic').serialize(),
                dataType : 'JSON',
                beforeSend: function ()
                {
                    if ($(".has-error", '#resumeBasic').length)
                    {
                        return false;
                    }
                },
                success: function(data)
                {
                    if(data.code == 1000)
                    {
                        $('#hiddenResumeId').val(data.data);
                        $('#resume-id').val(data.data);
                    }
                    layer.msg(data.message,{icon:6,time:2000});

                },
                error: function()
                {
                    layer.msg('网络错误',{icon:5,time:2000});
                }
            })
        });
    }



    var education = {
        editUrl : '<?= \yii\helpers\Url::to(['resume/add-edit-education'])?>',
        addUrl : '<?= \yii\helpers\Url::to(['resume/add-edit-education'])?>',
        deleteUrl : '<?= \yii\helpers\Url::to(['resume/delete-education'])?>',
        deleteModal : function(obj,id){
            var _this = this;
            layer.confirm('确认要删除吗？',function(index){
                var params = {
                    id: id,
                };
                ajaxSubmit(_this.deleteUrl, params, function () {
                    $(obj).parents('tr').remove();
                });
            });
        },

        addModal  : function()
        {
            if(checkPersonal()){
                var resumeId = $('#hiddenResumeId').val();
                var params = {resumeId : resumeId};
                layer_show(params, '教育经历', this.addUrl, 550, 290);
            }

        },

        editModal : function(id)
        {
            var resumeId = $('#hiddenResumeId').val();
            var params = {id : id, resumeId : resumeId};
            layer_show(params, '教育经历', this.editUrl, 550, 290);
        },
    };

    var working = {
        editUrl : '<?= \yii\helpers\Url::to(['resume/add-edit-working'])?>',
        addUrl : '<?= \yii\helpers\Url::to(['resume/add-edit-working'])?>',
        deleteUrl : '<?= \yii\helpers\Url::to(['resume/delete-working'])?>',
        deleteModal : function(obj,id){
            var _this = this;
            layer.confirm('确认要删除吗？',function(index){
                var params = {
                    id: id,
                };
                ajaxSubmit(_this.deleteUrl, params, function () {
                    $(obj).parents('tr').remove();
                });
            });
        },

        addModal  : function(id)
        {
            if(checkPersonal()){
                var resumeId = $('#hiddenResumeId').val();
                var params = {id : id, resumeId : resumeId};
                layer_show(params, '工作经验', this.addUrl, 550, 330);
            }

        },

        editModal : function(id)
        {
            var resumeId = $('#hiddenResumeId').val();
            var params = {id : id, resumeId : resumeId};
            layer_show(params, '工作经验', this.editUrl, 550, 330);
        },
    };

    function saveSelfAssessment() {
        var params = {id : $('#hiddenResumeId').val(), content : $('#self_assessment').val()};
        $.ajax({
            url : '<?= \yii\helpers\Url::to(['resume/save-self-assessment'])?>',
            type : 'POST',
            async: false,
            data : params,
            dataType : 'JSON',
            success: function(data)
            {
                if(data.code == 1000)
                {
                    layer.msg(data.message,{icon:6,time:2000});
                }
            },
            error: function()
            {
                layer.msg('网络错误',{icon:5,time:2000});
            }
        })
    }

    function checkPersonal () {
        var resumeId = $('#hiddenResumeId').val();
        if(!resumeId) {
            alert('请填写个人信息');
            return false;
        } else {
            return true;
        }
    }
</script>