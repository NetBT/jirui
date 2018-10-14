
<article class="page-container col-xs-12">
    <?php $form = \yii\bootstrap\ActiveForm::begin([
            'id' => 'addJMS',
            'options' => ['class' => 'form form-horizontal'],
            'method' => 'post',
            'enableAjaxValidation' => true,
            'validationUrl' => \yii\helpers\Url::to(['a-b/validate-form', 'type' => 'add']),
        ]
    );
    ?>
    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($addJMSmodel,'AB_number',[
            'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
            'errorOptions' => [
                'tag' => 'label',
                'class' => 'error'
            ],
            'labelOptions' => [
                'class' => 'form-label col-xs-5 text-right'
            ]
        ])->textInput(['placeholder'=>"请输入合同编号", "value" => \backend\models\AB::makeABNumber(), 'class' => 'form-control input-text'])?>
    </div>
    <div class="col-xs-6 col-sm-6 cl text-center">
    <?= $form->field($addJMSmodel,'AB_alliance_fee',[
        'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
        'errorOptions' => [
            'tag' => 'label',
            'class' => 'error'
        ],
        'labelOptions' => [
            'class' => 'form-label col-xs-3 text-right'
        ]
        ])->textInput(['placeholder'=>"0.00", 'class' => 'input-text form-control '])?>
    </div>
    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($addJMSmodel,'AB_name',[
            'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
            'errorOptions' => [
                'tag' => 'label',
                'class' => 'error'
            ],
            'labelOptions' => [
                'class' => 'form-label col-xs-5 text-right'
            ]
        ])->textInput(['placeholder'=>"请输入店铺名称", 'class' => 'input-text form-control '])?>
    </div>
    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($addJMSmodel,'AB_collection',[
            'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
            'errorOptions' => [
                'tag' => 'label',
                'class' => 'error'
            ],
            'labelOptions' => [
                'class' => 'form-label col-xs-3 text-right'
            ]
        ])->textInput(['placeholder'=>"0.00", 'class' => 'input-text form-control '])?>
    </div>
    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($addJMSmodel,'AB_principal',[
            'template' => '{label} <div class="formControls col-xs-7">{input}</div>',
            'labelOptions' => [
                'class' => 'form-label col-xs-5 text-right'
            ]
        ])->dropDownList(\backend\models\Employee::getFormList(), [
            'class' => 'selectpicker form-control',
            'id' => 'selectPrincipal'
        ])?>
    </div>
    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($addJMSmodel,'AB_collection_user',[
            'template' => '{label} <div class="formControls col-xs-7">{input}</div>',
            'labelOptions' => [
                'class' => 'form-label col-xs-3 text-right'
            ]
        ])->dropDownList(\backend\models\Employee::getFormList(),[
            'class' => 'selectpicker form-control',
            'id' => 'selectCollectUser',
        ])?>
    </div>
    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($addJMSmodel,'AB_tel',[
            'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
            'errorOptions' => [
                'tag' => 'label',
                'class' => 'error'
            ],
            'labelOptions' => [
                'class' => 'form-label col-xs-5 text-right'
            ]
        ])->textInput(['placeholder'=>"请填写联系方式", 'class' => 'input-text form-control '])?>
    </div>
    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($addJMSmodel,'AB_balance',[
            'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
            'errorOptions' => [
                'tag' => 'label',
                'class' => 'error'
            ],
            'labelOptions' => [
                'class' => 'form-label col-xs-3 text-right'
            ]
        ])->textInput(['readonly'=> '','class' => 'input-text form-control '])?>
    </div>
    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($addJMSmodel,'AB_address',[
            'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
            'errorOptions' => [
                'tag' => 'label',
                'class' => 'error'
            ],
            'labelOptions' => [
                'class' => 'form-label col-xs-5 text-right'
            ]
        ])->textInput(['placeholder'=>"请输入联系地址", 'class' => 'input-text form-control '])?>
    </div>
    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($addJMSmodel,'AB_store_code',[
            'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
            'errorOptions' => [
                'tag' => 'label',
                'class' => 'error'
            ],
            'labelOptions' => [
                'class' => 'form-label col-xs-3 text-right'
            ]
        ])->textInput(['placeholder'=>"邮编", 'class' => 'input-text form-control '])?>
    </div>
    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($addJMSmodel,'AB_start_time',[
            'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
            'errorOptions' => [
                'tag' => 'label',
                'class' => 'error'
            ],
            'labelOptions' => [
                'class' => 'form-label col-xs-5 text-right'
            ]
        ])->textInput(['placeholder'=>"请选择开通时间", 'id' => 'startTime', 'class' => 'input-text form-control '])?>
    </div>
    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($addJMSmodel,'AB_end_time',[
            'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
            'errorOptions' => [
                'tag' => 'label',
                'class' => 'error'
            ],
            'labelOptions' => [
                'class' => 'form-label col-xs-3 text-right'
            ]
        ])->textInput(['placeholder'=>"请选择到期时间",'id' => 'endTime', 'class' => 'input-text form-control '])?>
    </div>
    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($addJMSmodel,'AB_code',[
            'template' => '{label}<div class="formControls col-xs-7">{input}{error}</div>',
            'errorOptions' => [
                'tag' => 'label',
                'class' => 'error'
            ],
            'labelOptions' => [
                'class' => 'form-label col-xs-5 text-right'
            ]
        ])->textInput(['placeholder'=>"编码必须字母,最大5位", 'class' => 'input-text form-control '])?>
    </div>
    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($addJMSmodel,'AB_store_mark',[
            'template' => '{label}<div class="formControls col-xs-7">{input}{error}</div>',
            'errorOptions' => [
                'tag' => 'label',
                'class' => 'error'
            ],
            'labelOptions' => [
                'class' => 'form-label col-xs-3 text-right'
            ]
        ])->textInput(['placeholder'=>"备注字数限制100", 'class' => 'input-text form-control '])?>
    </div>
    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($addJMSmodel,'AB_store_status', [
            'labelOptions' => [
                'class' => 'form-label col-xs-5 text-right'
            ]
        ])->inline()->radioList(\common\models\Status::ABInfoStatusMap(), [
            'template' => '{label}<div class="formControls col-xs-7 text-left">{input}</div>',
            'value' => \common\models\Status::AB_STORE_STATUS_UNLOCK])?>
    </div>
    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($addJMSmodel,'AB_store_type', [
            'labelOptions' => [
                'class' => 'form-label col-xs-3 text-right'
            ]
        ])->inline()->radioList(\common\models\Status::ABInfoTypeMap(),[
            'template' => '{label}<div class="formControls col-xs-7 text-left">{input}</div>',
        'value' => \common\models\Status::AB_STORE_TYPE_COMMON])?>
    </div>
    <div class="col-xs-12 col-sm-12 cl text-center">
        <label class="form-label col-xs-2 text-right">权限</label>
        <?php $moduleList = \backend\models\ABPost::getFormList();
        ?>
        <div class="form-group fromControls col-xs-10 text-l"  id="commonStoreType">
            <?php foreach ($moduleList['commonPostList'] as $k => $v) :?>
            <label class="checkbox-inline">
                <input type="checkbox" disabled class="disabled" checked > <?= $v?>
            </label>
            <?php endforeach;?>
        </div>
        <div class="form-group fromControls col-xs-10 text-l" style="display: none;" id="advancedStoreType">
            <?php foreach ($moduleList['advancedPostList'] as $k => $v) :?>
                <label class="checkbox-inline">
                    <input type="checkbox" disabled class="disabled" checked > <?= $v?>
                </label>
            <?php endforeach;?>
        </div>
    </div>
    <?php \yii\bootstrap\ActiveForm::end(); ?>
    <div class="col-xs-12 col-sm-12 cl text-center">
        <button type="button" onclick="add()" class='btn btn-hot btn-md margin-right-30'>保存并授权</button>
        <button type="button" class="btn btn-default btn-md layui-layer-close">取消</button>
    </div>
</article>
<script>
    $(function(){
        $("#startTime").on('click', function() {
            WdatePicker({readOnly: true, dateFmt: 'yyyy-MM-dd HH:mm:ss', minDate:'%y-%M-%d 00:00:00'});
        });
        $("#endTime").on('click', function() {
            WdatePicker({readOnly: true, dateFmt: 'yyyy-MM-dd HH:mm:ss', minDate: '#F{$dp.$D(\'startTime\')}'});
        });
        $("#selectCollectUser").selectpicker({
            title: '请选择收款人',
            style: 'btn-default',
            width: '100%',
            liveSearch: true
        });
        $("#selectPrincipal").selectpicker({
            title: '请选择负责人',
            style: 'btn-default',
            width: '100%',
            liveSearch: true
        });

        $("input[name='AB[AB_store_type]'").on('click', function () {
            if ($(this).val() == '<?= \common\models\Status::AB_STORE_TYPE_COMMON?>') {
                $("#advancedStoreType").hide();
                $("#commonStoreType").show();
            }
            if ($(this).val() == '<?= \common\models\Status::AB_STORE_TYPE_ADVANCED?>') {
                $("#commonStoreType").hide();
                $("#advancedStoreType").show();
            }
        });
    });
    function add() {
        ajaxSubmitForm('#addJMS', '<?= \yii\helpers\Url::to(['a-b/do-add'])?>');
    }
</script>
