<article class="page-container">
    <?php $form = \yii\bootstrap\ActiveForm::begin([
            'id' => 'addEditRecruitForm',
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
            'validationUrl' => \yii\helpers\Url::to(['recruit/validate-post-form','type' => 'addEdit']),
//            'enableClientValidation' => true,
//            'validateOnSubmit'=> true,     //提交时的验证
        ]
    );
    ?>

    <?= $form->field($model,'id',[
        'template' => '{input}',
        'options' => ['class' => ''],
    ])->hiddenInput()?>

    <div class="col-xs-12 col-sm-12 cl text-center">
        <?= $form->field($model,'business_name',[
            'template' => '{label} <div class="formControls col-xs-10">{input}{error}</div>',
            'labelOptions' => [
                'class' => 'form-label col-xs-2 text-right'
            ],
        ])->textInput(['placeholder'=>"店铺名称",])?>
    </div>

    <div class="col-xs-12 col-sm-12 cl text-center">
        <?= $form->field($model,'address',[
            'template' => '{label} <div class="formControls col-xs-10">{input}{error}</div>',
            'labelOptions' => [
                'class' => 'form-label col-xs-2 text-right'
            ],
        ])->textInput(['placeholder'=>"地址"])?>
    </div>

    <div class="col-xs-12 col-sm-12 cl text-center">
        <?= $form->field($model,'recruit_title',[
            'template' => '{label} <div class="formControls col-xs-10">{input}{error}</div>',
            'labelOptions' => [
                'class' => 'form-label col-xs-2 text-right'
            ],
        ])->textInput(['placeholder'=>"招聘标题"])?>
    </div>

    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($model,'post_id',[
            'template' => '{label} <div class="formControls col-xs-7">{input}</div>',
        ])->dropDownList(\backend\models\EmployeePost::getFormArray(['business_id' => \backend\models\Common::getBusinessId(),'status' => \common\models\Status::EMPLOYEE_POST_SUCCESS],'id','post_name'),[
            'class' => 'selectpicker',
            'id' => 'postId',
        ])?>
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
        <?= $form->field($model,'degree',[
            'template' => '{label} <div class="formControls col-xs-7">{input}</div>',
        ])->dropDownList(\common\models\Status::degreeMap(),[
            'class' => 'selectpicker',
            'id' => 'selectDegree',
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

    <div class="col-xs-12 col-sm-12 cl text-center">
        <?= $form->field($model,'shop_introduced',[
            'template' => '{label} <div class="formControls col-xs-10">{input}{error}</div>',
            'labelOptions' => [
                'class' => 'form-label col-xs-2 text-right'
            ],
        ])->textarea(['placeholder'=>"店铺介绍",'rows' => 4])?>
    </div>

    <div class="col-xs-12 col-sm-12 cl text-center">
        <?= $form->field($model,'job_specification',[
            'template' => '{label} <div class="formControls col-xs-10">{input}{error}</div>',
            'labelOptions' => [
                'class' => 'form-label col-xs-2 text-right'
            ],
        ])->textarea(['placeholder'=>"任职要求",'rows' => 8])?>
    </div>

    <?php \yii\bootstrap\ActiveForm::end(); ?>
    <div class="col-xs-12 col-sm-12 cl text-center margin-top-20 margin-bottom-10">
        <button type="button" onclick="save()" id="saveBtn" class='btn btn-hot btn-md margin-right-30'>确认</button>
        <button type="button" class="btn btn-default btn-md layui-layer-close">取消</button>
    </div>
</article>


<script>
    $(function(){
        $("#postId").selectpicker({
            title: '招聘职位',
            style: 'btn-default',
            width: '100%',
            liveSearch: true
        });

        $("#selectDegree").selectpicker({
            title: '请选择学历',
            style: 'btn-default',
            width: '100%',
            liveSearch: true
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

    function save()
    {
        ajaxSubmitForm('#addEditRecruitForm', '<?= \yii\helpers\Url::to(['recruit/add-edit-post'])?>');
    }
</script>