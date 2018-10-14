<article class="page-container">
    <?php $form = \yii\bootstrap\ActiveForm::begin([
            'id' => 'send',
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
            'validationUrl' => \yii\helpers\Url::to(['message/validate-form', 'type' => 'sendMessage']),
        ]
    );
    ?>
    <div class="col-xs-12 col-sm-12 cl text-center">
    <?= $form->field($model,'type',[
        'template' => '{label} <div class="formControls col-xs-7">{input}</div>',
        'labelOptions' => [
            'class' => 'form-label col-xs-3 text-right'
        ]
    ])->dropDownList(\common\models\Status::messageTypeMap(),[
        'class' => 'selectpicker form-control',
        'id' => 'selectType',
        'data-title' => '请选择类型'
    ])?>
    </div>
    <div class="col-xs-12 col-sm-12 cl text-center">
        <?= $form->field($model,'content',[
            'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
        ])->textarea(['placeholder'=>"内容",'rows' => 8])?>
    </div>

    <?php \yii\bootstrap\ActiveForm::end(); ?>
    <div class="col-xs-12 col-sm-12 cl text-center margin-top-20 margin-bottom-10">
        <button type="button" onclick="send()" class='btn btn-hot btn-md margin-right-30'>发送</button>
        <button type="button" class="btn btn-default btn-md layui-layer-close">取消</button>
    </div>
</article>
<script>
    $(function () {
       $(".selectpicker").selectpicker({
           style: 'btn-default',
           width: '100%'
       });
    });
    function send() {
        ajaxSubmitForm('#send', '<?= \yii\helpers\Url::to(['message/do-send'])?>');
    }
</script>
