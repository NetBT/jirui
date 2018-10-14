<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-4"><b>店铺资料</b>Content of shop</div>
        <div class="header-search col-xs-7">
            <div class="col-xs-9 pull-right text-c">
                <button class="btn btn-warning size-M" onclick="doPostpone()">延期申请</button>
                <button class="btn btn-danger size-M" onclick="doRecharge()">充值</button>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="main-body col-xs-12">
        <?php $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'editJMS',
                'options' => ['class' => 'form form-horizontal'],
                'fieldConfig'=>[
                    //改变input输入框
                    'inputOptions'=>['class'=>'form-control input-text'],
                    //改变外层自动生成的div
//                        'options' => [],
                    //改变label
                    'labelOptions' => [
                        'class' => 'form-label col-xs-2 text-right'
                    ],
                    //修改error样式和标签
                    'errorOptions' => [
                        'tag' => 'label',
                        'class' => 'error'
                    ],
                ],
                'method' => 'post',
                'enableAjaxValidation' => true,
                'validationUrl' => \yii\helpers\Url::to(['a-b/validate-form', 'type' => 'editBySelf']),
            ]
        );
        ?>

        <?= $form->field($model,'id',[
            'template' => '{input}',
            'options' => [
                'class' => 'hidden'
            ],
        ])->hiddenInput()?>

        <div class="col-xs-6 col-sm-6 cl text-right">
            <?= $form->field($model,'AB_number',[
                'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
            ])->textInput(['placeholder'=>"请输入合同编号", 'readonly' => '','disabled' => ''])?>
        </div>
        <div class="col-xs-6 col-sm-6 cl text-center">
            <?= $form->field($model,'AB_alliance_fee',[
                'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
            ])->textInput(['placeholder'=>"0.00",'readonly' => '','disabled' => ''])?>
        </div>

        <div class="col-xs-6 col-sm-6 cl text-center">
            <?= $form->field($model,'AB_name',[
                'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
            ])->textInput(['placeholder'=>"请输入店铺名称"])?>
        </div>
        <div class="col-xs-6 col-sm-6 cl text-center">
            <?= $form->field($model,'AB_balance',[
                'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
            ])->textInput(['readonly' => '','disabled' => ''])?>
        </div>

        <div class="col-xs-6 col-sm-6 cl text-center">
            <?= $form->field($model,'AB_principal',[
                'template' => '{label} <div class="formControls col-xs-7">{input}</div>',
            ])->dropDownList(\backend\models\Employee::getFormArray(['alliance_business_id' => \backend\models\Common::getBusinessId(),'status' => \common\models\Status::EMPLOYEE_STATUS_ACTIVE],'id','employee_name'), [
                'class' => 'selectpicker',
                'id' => 'selectPrincipal',
            ])?>
        </div>
        <div class="col-xs-6 col-sm-6 cl text-center">
            <?= $form->field($model,'AB_tel',[
                'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
            ])->textInput(['placeholder'=>"请填写联系方式"])?>
        </div>

        <div class="col-xs-6 col-sm-6 cl text-center">
            <?= $form->field($model,'AB_address',[
                'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
            ])->textInput(['placeholder'=>"请输入联系地址"])?>
        </div>
        <div class="col-xs-6 col-sm-6 cl text-center">
            <?= $form->field($model,'AB_store_code',[
                'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
            ])->textInput(['placeholder'=>"邮编", 'class' => 'input-text form-control '])?>
        </div>

        <div class="col-xs-6 col-sm-6 cl text-center">
            <?= $form->field($model,'AB_start_time',[
                'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
            ])->textInput(['placeholder'=>"请选择开通时间", 'readonly' => '','disabled' => '','id' => 'startTime'])?>
        </div>
        <div class="col-xs-6 col-sm-6 cl text-center">
            <?= $form->field($model,'AB_end_time',[
                'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
            ])->textInput(['placeholder'=>"请选择到期时间",'readonly' => '','disabled' => '','id' => 'endTime'])?>
        </div>

        <div class="col-xs-6 col-sm-6 cl text-center">
            <label class="form-label col-xs-2 text-right">操作人</label>
            <div class="formControls col-xs-7 text-left" style="margin-top: 3px;"><?= Yii::$app->user->identity->employee_name?></div>
        </div>
        <div class="col-xs-6 col-sm-6 cl text-center">
            <?= $form->field($model,'AB_store_type', [
                'template' => '{label} <div class="formControls col-xs-3">{input}</div>',
            ])->textInput(['value' => \common\models\Status::ABInfoTypeMap()[$model->AB_store_type],'readonly' => '','disabled' => '']
            )?>
        </div>

        <div class="col-xs-6 col-sm-6 cl text-center">
            <?= $form->field($model,'AB_store_mark',[
                'template' => '{label}<div class="formControls col-xs-7">{input}{error}</div>',
            ])->textarea(['placeholder'=>"备注字数限制100"])?>
        </div>

        <?php \yii\bootstrap\ActiveForm::end(); ?>
        <div class="col-xs-12 col-sm-12 cl text-center margin-top-30">
            <button type="button" class="btn btn-secondary size-L" onclick="doSave()"><i class="Hui-iconfont">&#xe632;</i>&emsp;保  存&emsp;</button>
        </div>
    </div>
</div>

<script>
    $(function(){
        $("#selectPrincipal").selectpicker({
            title: '请选择负责人',
            style: 'btn-default',
            width: '100%',
            liveSearch: true
        });
    });
    function doSave() {
        ajaxSubmitForm('#editJMS', '<?= \yii\helpers\Url::to(['a-b/edit-by-self'])?>');
    }

    //充值
    function doRecharge() {
        layer_show({},'充值','<?= \yii\helpers\Url::to(['a-b/recharge-by-self'])?>',500,450);
    }

    //延期
    function doPostpone() {
        layer_show({},'延期','<?= \yii\helpers\Url::to(['a-b/postpone-by-self'])?>',500,450);
    }
</script>
