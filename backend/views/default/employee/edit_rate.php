<article class="page-container">
    <?php $form = \yii\bootstrap\ActiveForm::begin([
            'id' => 'editEmployeeRate',
            'options' => ['class' => 'form form-horizontal'],
            'fieldConfig'=>[
                'inputOptions'=>['class'=>'form-control input-text'],//改变input输入框
//                'option' => [],//改变外层自动生成的div
//                'labelOptions' => [],//改变label
                'errorOptions' => [
                    'tag' => 'label',
                    'class' => 'error'
                ],
                'labelOptions' => [
                    'class' => 'form-label col-xs-3 text-right'
                ],

            ],
            'method' => 'post',
        ]
    );
    ?>
    <?= $form->field($model,'id',[
        'template' => '{input}',
        'options' => ['class' => '']
    ])->hiddenInput()?>

    <div class="col-xs-12 col-sm-12 cl text-center">
        <?= $form->field($model,'employee_id',[
            'template' => '{label} <div class="formControls col-xs-7">{input}</div>',
        ])->dropDownList(\backend\models\Employee::getFormArray(['status' => \common\models\Status::EMPLOYEE_STATUS_ACTIVE,'alliance_business_id' => \backend\models\Common::getBusinessId()],'id','employee_name'),[
            'class' => 'selectpicker',
            'id' => 'selectEmployee',
            'disabled' => '',
        ])?>
    </div>

    <div class="col-xs-12 col-sm-12 cl text-center">
        <?= $form->field($model,'rate_type',[
            'template' => '{label} <div class="formControls col-xs-7">{input}</div>',
        ])->dropDownList(\common\models\Status::employeeRateTypeMap(),[
            'class' => 'selectpicker',
            'id' => 'selectRateType',
        ])?>
    </div>

    <div class="col-xs-12 col-sm-12 cl text-center">
        <?= $form->field($model,'rate_money',[
            'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
        ])->textInput(['placeholder'=>"提成金额(元)"])?>
    </div>

    <div class="col-xs-12 col-sm-12 cl text-center">
        <?= $form->field($model,'mark',[
            'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
        ])->textarea(['placeholder'=>"备注",'rows' => 3])?>
    </div>

    <?php \yii\bootstrap\ActiveForm::end(); ?>
    <div class="col-xs-12 col-sm-12 cl text-center margin-top-20 margin-bottom-10">
        <button type="button" onclick="save()" class='btn btn-hot btn-md margin-right-30'>确认</button>
        <button type="button" class="btn btn-default btn-md layui-layer-close">取消</button>
    </div>
</article>
<script>
    $(function(){
        $("#selectEmployee").selectpicker({
            title: '请选择员工',
            style: 'btn-default',
            width: '100%',
            liveSearch: true
        });
        $("#selectRateType").selectpicker({
            title: '请选择类型',
            style: 'btn-default',
            width: '100%',
            liveSearch: true,
            dropupAuto : false
        });

    });
    function save() {
        ajaxSubmitForm('#editEmployeeRate', '<?= \yii\helpers\Url::to(['employee/edit-rate'])?>');
    }
</script>
