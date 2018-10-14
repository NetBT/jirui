<!--<article class="page-container">-->
<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-4"><b>个人信息</b>Content of personal</div>
        <div class="header-search col-xs-7">
            <div class="col-xs-9 pull-right text-c">
                <button class="btn btn-warning size-M" onclick="updatePassword(<?= $model->id?>)">修改密码</button>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="main-body col-xs-12">
        <?php $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'editBySelf',
                'options' => ['class' => 'form form-horizontal'],
                'fieldConfig'=>[
                    'inputOptions'=>['class'=>'form-control input-text'],//改变input输入框
//                'options' => [],//改变外层自动生成的div
//                'labelOptions' => [],//改变label
                    'labelOptions' => [
                        'class' => 'form-label col-xs-3 text-right'
                    ],
                    'errorOptions' => [                                 //修改error样式和标签
                        'tag' => 'label',
                        'class' => 'error'
                    ],
                ],
                'method' => 'post',
                'enableAjaxValidation' => true,
                'validationUrl' => \yii\helpers\Url::to(['employee/validate-form','type' => 'editBySelf']),
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
            <?= $form->field($model,'employee_name',[
                'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
            ])->textInput(['placeholder'=>"真实姓名"])?>
        </div>

        <div class="col-xs-6 col-sm-6 cl text-center">
            <?= $form->field($model,'tel',[
                'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
            ])->textInput(['placeholder'=>"手机号"])?>
        </div>

        <div class="col-xs-6 col-sm-6 cl text-center">
            <?= $form->field($model,'sex', [
            ])->inline()->radioList(\common\models\Status::sexyMap(),[
                'template' => '{label}<div class="formControls col-xs-4">{input}</div>',
            ])?>
        </div>

        <div class="col-xs-6 col-sm-6 cl text-center">
            <?= $form->field($model,'marriage', [
            ])->inline()->radioList(\common\models\Status::marriageMap(),[
                'template' => '{label}<div class="formControls col-xs-4">{input}</div>',
            ])?>
        </div>

        <div class="col-xs-6 col-sm-6 cl text-center">
            <?= $form->field($model,'birthday',[
                'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
            ])->textInput(['placeholder'=>"生日", 'id' => 'birthday'])?>
        </div>

        <div class="col-xs-6 col-sm-6 cl text-center">
            <?= $form->field($model,'wechat',[
                'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
            ])->textInput(['placeholder'=>"微信"])?>
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
            <?= $form->field($model,'working_status', [
            ])->inline()->radioList(\common\models\Status::workingStatusMap(),[
                'template' => '{label}<div class="formControls col-xs-4">{input}</div>',
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
            <?= $form->field($model,'expected_salary',[
                'template' => '{label} <div class="formControls col-xs-7">{input}</div>',
            ])->dropDownList(\common\models\Status::expectedSalaryLabelMap(),[
                'class' => 'selectpicker',
                'id' => 'expectedSalary',
            ])?>
        </div>

        <?php \yii\bootstrap\ActiveForm::end(); ?>
        <div class="col-xs-12 col-sm-12 cl text-center margin-top-20 margin-bottom-10">
            <button type="button" onclick="save()" class='btn btn-hot btn-md margin-right-30'>确认</button>
            <button type="button" class="btn btn-default btn-md">取消</button>
        </div>
    </div>
</div>
<!--</article>-->
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
        ajaxSubmitForm('#editBySelf', '<?= \yii\helpers\Url::to(['resume/index-personal-data'])?>');
    }

    function updatePassword(id)
    {
        var param = {
            id : id,
        };
        layer_show(param, '修改密码', '<?= \yii\helpers\Url::to(['employee/update-password'])?>', 550, 290);
    }

</script>
