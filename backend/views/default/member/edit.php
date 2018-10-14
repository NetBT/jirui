<article class="page-container">
    <?php $form = \yii\bootstrap\ActiveForm::begin([
            'id' => 'editMember',
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
            'validationUrl' => \yii\helpers\Url::to(['member/validate-form', 'type' => 'edit']),
            'successCssClass' => 'has-success notice-success',
            'errorCssClass' => 'has-error notice-error',
        ]
    );
    ?>
    <?= $form->field($model,'id',[
        'template' => '{input}',
        'options' => ['class' => '']
    ])->hiddenInput()?>

    <div class="col-xs-6 col-sm-6 cl text-center" style="display: none">
        <?= $form->field($model,'number',[
            'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
        ])->textInput(['placeholder'=>"编号",'readonly' => '','disabled' => ''])?>
    </div>

    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($model,'name',[
            'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
        ])->textInput(['placeholder'=>"宝宝姓名"])?>
    </div>

    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($model,'age',[
            'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
        ])->textInput(['placeholder'=>"年龄"])?>
    </div>

    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($model,'sex', [
        ])->inline()->radioList(\common\models\Status::sexyMap(),[
            'template' => '{label}<div class="formControls col-xs-3">{input}</div>',
        ])?>
    </div>

    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($model,'birthday',[
            'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
        ])->textInput(['placeholder'=>"2018-01-01"])?>
    </div>

    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($model,'tel',[
            'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
        ])->textInput(['placeholder'=>"联系手机号"])?>
    </div>

    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($model,'parents_name',[
            'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
        ])->textInput(['placeholder'=>"家长姓名"])?>
    </div>

    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($model,'parents_baby_link',[
            'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
        ])->textInput(['placeholder'=>"与宝宝关系"])?>
    </div>

    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($model,'wechat',[
            'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
        ])->textInput(['placeholder'=>"微信"])?>
    </div>

    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($model,'QQ',[
            'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
        ])->textInput(['placeholder'=>"QQ"])?>
    </div>

    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($model,'address',[
            'template' => '{label}<div class="formControls col-xs-7">{input}{error}</div>',
        ])->textInput(['placeholder'=>"地址限制40字"])?>
    </div>

    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($model,'email',[
            'template' => '{label}<div class="formControls col-xs-7">{input}{error}</div>',
        ])->textInput(['placeholder'=>"邮箱"])?>
    </div>


    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($model,'royalty_id',[
            'template' => '{label} <div class="formControls col-xs-7">{input}</div>',
        ])->dropDownList(\backend\models\Employee::getFormArray(['status' => \common\models\Status::EMPLOYEE_STATUS_ACTIVE,'alliance_business_id' => \backend\models\Common::getBusinessId()],'id','employee_name'),[
            'class' => 'selectpicker',
            'id' => 'selectRoyalty',
        ])?>
    </div>

    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($model,'spare_tel',[
            'template' => '{label}<div class="formControls col-xs-7">{input}{error}</div>',
        ])->textInput(['placeholder'=>"备用电话"])?>
    </div>

    <div class="col-xs-6 col-sm-6">
        <?= $form->field($model,'mark',[
            'template' => '{label}<div class="formControls col-xs-7">{input}{error}</div>',
        ])->textarea(['placeholder'=>"备注",'rows' => 4])?>
    </div>

    <?php \yii\bootstrap\ActiveForm::end(); ?>
    <div class="col-xs-12 col-sm-12 cl text-center margin-top-20 margin-bottom-10">
        <button type="button" onclick="add()" class='btn btn-hot btn-md margin-right-30'>确认</button>
        <button type="button" class="btn btn-default btn-md layui-layer-close">取消</button>
    </div>
</article>
<script>
    $(function(){
        $("#selectReferrer").selectpicker({
            title: '选择推荐人',
            style: 'btn-default',
            width: '100%',
            liveSearch: true
        });

        $("#selectRoyalty").selectpicker({
            title: '选择提成员工',
            style: 'btn-default',
            width: '100%',
            liveSearch: true
        });
    });
    function add() {
        ajaxSubmitForm('#editMember', '<?= \yii\helpers\Url::to(['member/edit'])?>');
    }
</script>
