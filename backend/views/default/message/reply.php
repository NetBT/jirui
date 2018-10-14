<article class="page-container">
    <?php $form = \yii\bootstrap\ActiveForm::begin([
            'id' => 'reply',
            'options' => ['class' => 'form form-horizontal'],
            'fieldConfig'=>[
                'inputOptions'=>['class'=>'form-control input-text'],//改变input输入框
//                'option' => [],//改变外层自动生成的div
//                'labelOptions' => [],//改变label
                'labelOptions' => [
                    'class' => 'form-label col-xs-3 text-right'
                ],
                'errorOptions' => [
                    'tag' => 'label',
                    'class' => 'error'
                ],
            ],
            'method' => 'post',
            'enableAjaxValidation' => true,
            'validationUrl' => \yii\helpers\Url::to(['employee/validate-form', 'type' => 'addEmployee']),
        ]
    );
    ?>

    <?= $form->field($model,'id',[
        'template' => '{input}',
        'options' => ['class' => ''],
    ])->hiddenInput()?>

    <div class="col-xs-12 col-sm-12 cl text-center">
        <?= $form->field($model,'business_name',[
            'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
        ])->textInput(['placeholder'=>"商户", 'readonly' => '', 'disabled' => ''])?>
    </div>

    <div class="col-xs-12 col-sm-12 cl text-center">
        <?= $form->field($model,'content',[
            'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
        ])->textarea(['placeholder'=>"内容",'rows' => 8])?>
    </div>

    <div class="col-xs-12 col-sm-12 cl text-center">
        <?= $form->field($model,'reply_content',[
            'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
        ])->textarea(['placeholder'=>"回复内容",'rows' => 8])?>
    </div>

    <?php \yii\bootstrap\ActiveForm::end(); ?>
    <div class="col-xs-12 col-sm-12 cl text-center margin-top-20 margin-bottom-10">
        <button type="button" onclick="reply()" class='btn btn-hot btn-md margin-right-30'>确认</button>
        <button type="button" class="btn btn-default btn-md layui-layer-close">取消</button>
    </div>
</article>
<script>
    function reply() {
        ajaxSubmitForm('#reply', '<?= \yii\helpers\Url::to(['message/reply'])?>');
    }
</script>
