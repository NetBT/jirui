
<article class="page-container col-xs-12">
    <?php $form = \yii\bootstrap\ActiveForm::begin([
            'id' => 'editAdvert',
            'options' => [
                    'class' => 'form form-horizontal',
            ],
            'method' => 'post',
            'enableAjaxValidation' => true,
            'validationUrl' => \yii\helpers\Url::to(['advert/validate-form', 'type' => 'edit']),
        ]
    );
    ?>
    <?= $form->field($model,'id',[
        'template' => '{input}',
        'options' => [
            'class' => 'hidden'
        ],
        'labelOptions' => [
            'class' => 'hidden'
        ],
    ])->hiddenInput()?>
    <div class="col-xs-12 col-sm-12 cl text-center">
        <?= $form->field($model,'advert_name',[
            'template' => '{label} <div class="formControls col-xs-5">{input}{error}</div>',
            'errorOptions' => [
                'tag' => 'label',
                'class' => 'error'
            ],
            'labelOptions' => [
                'class' => 'form-label col-xs-4 text-right'
            ]
        ])->textInput(['placeholder' => '必填', 'class' => 'form-control input-text'])?>
    </div>
    <div class="col-xs-12 col-sm-12 cl text-center">
        <label class="form-label col-xs-4 text-right">广告素材</label>
        <div class="formControls col-xs-5">
            <span class="btn-upload form-group">
                <input class="input-text upload-url" name="uploadfile1" id="uploadfile1" readonly="" style="width:183px" type="text">
                <a href="javascript:void();" class="btn btn-primary upload-btn"><i class="Hui-iconfont"></i> 浏览文件</a>
                <input name="advert_matter" class="input-file" type="file">
            </span>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 cl text-center">
        <label class="form-label col-xs-4 text-right">&emsp;</label>
        <div class="formControls col-xs-5">
            <img src="/uploads/<?= $model->advert_matter?>" style="width: 150px;height: 150px;">
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 cl text-center">
        <?= $form->field($model,'advert_position', [
            'labelOptions' => [
                'class' => 'form-label col-xs-4 text-right'
            ],
        ])->inline()->radioList(\common\models\Status::getPositionMap(), [
            'template' => '{label}<div class="formControls col-xs-5 text-left" id="colorList">{input}</div>'])?>
    </div>
    <div class="col-xs-12 col-sm-12 cl text-center">
        <?= $form->field($model,'advert_tel',[
            'template' => '{label} <div class="formControls col-xs-5">{input}{error}</div>',
            'errorOptions' => [
                'tag' => 'label',
                'class' => 'error'
            ],
            'labelOptions' => [
                'class' => 'form-label col-xs-4 text-right'
            ]
        ])->textInput(['placeholder'=>"联系电话", 'class' => 'input-text form-control '])?>
    </div>
    <div class="col-xs-12 col-sm-12 cl text-center">
        <?= $form->field($model,'advert_commission',[
            'template' => '{label} <div class="formControls col-xs-5">{input}{error}</div>',
            'errorOptions' => [
                'tag' => 'label',
                'class' => 'error'
            ],
            'labelOptions' => [
                'class' => 'form-label col-xs-4 text-right'
            ]
        ])->textInput(['placeholder'=>"佣金", 'class' => 'input-text form-control '])?>
    </div>
    <div class="col-xs-12 col-sm-12 cl text-center">
        <?= $form->field($model,'advert_balance',[
            'template' => '{label} <div class="formControls col-xs-5">{input}{error}</div>',
            'errorOptions' => [
                'tag' => 'label',
                'class' => 'error'
            ],
            'labelOptions' => [
                'class' => 'form-label col-xs-4 text-right'
            ]
        ])->textInput(['placeholder'=>"", 'value' => 0.0, 'readonly' => '','class' => 'input-text form-control '])?>
    </div>
    <div class="col-xs-12 col-sm-12 cl text-center">
        <?= $form->field($model,'advert_pay_money',[
            'template' => '{label} <div class="formControls col-xs-5">{input}{error}</div>',
            'errorOptions' => [
                'tag' => 'label',
                'class' => 'error'
            ],
            'labelOptions' => [
                'class' => 'form-label col-xs-4 text-right'
            ]
        ])->textInput(['placeholder'=> '请填写收款额','class' => 'input-text form-control '])?>
    </div>
    <div class="col-xs-12 col-sm-12 cl text-center">
        <?= $form->field($model,'advert_principal',[
            'template' => '{label} <div class="formControls col-xs-5">{input}</div>',
            'labelOptions' => [
                'class' => 'form-label col-xs-4 text-right'
            ]
        ])->dropDownList(\backend\models\Employee::getFormList(), [
            'class' => 'selectpicker form-control',
            'id' => 'selectPrincipal'
        ])?>
    </div>
    <div class="col-xs-12 col-sm-12 cl text-center">
        <?= $form->field($model,'mark',[
            'template' => '{label} <div class="formControls col-xs-5">{input}{error}</div>',
            'errorOptions' => [
                'tag' => 'label',
                'class' => 'error'
            ],
            'labelOptions' => [
                'class' => 'form-label col-xs-4 text-right'
            ]
        ])->textInput(['placeholder'=>"备注", 'class' => 'input-text form-control '])?>
    </div>
    <div class="col-xs-12 col-sm-12 cl text-center">
        <?= $form->field($model,'start_time',[
            'template' => '{label} <div class="formControls col-xs-3">{input}</div>',
            'errorOptions' => [
                'tag' => 'label',
                'class' => 'error'
            ],
            'labelOptions' => [
                'class' => 'form-label col-xs-4 text-right'
            ],
            'options' => [
                'class' => ''
            ]
        ])->textInput(['placeholder'=>"开始时间", 'id' => 'startTime', 'class' => 'input-text form-control '])?>
        <?= $form->field($model,'end_time',[
            'template' => '<div class="formControls col-xs-3">{input}</div>',
            'errorOptions' => [
                'tag' => 'label',
                'class' => 'error'
            ],
            'options' => [
                    'class' => 'form-group'
            ]
        ])->textInput(['placeholder'=>"结束时间",'id' => 'endTime', 'class' => 'input-text form-control '])?>
    </div>
    <?php \yii\bootstrap\ActiveForm::end(); ?>
    <div class="col-xs-12 col-sm-12 cl text-center">
        <button type="button" onclick="addAvert()" class='btn btn-hot btn-md margin-right-30'>保存</button>
        <button type="button" onclick="layer.closeAll()" class="btn btn-default btn-md">取消</button>
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
        $("#selectPrincipal").selectpicker({
            title: '请选择负责人',
            style: 'btn-default',
            width: '100%',
            liveSearch: true
        });
    });

    function addAvert() {
        ajaxSubmitFileForm('editAdvert', '<?=\yii\helpers\Url::to(['advert/do-edit'])?>', function () {
            layer.closeAll();
            DataTable.drawTable();
        });
    }
</script>
