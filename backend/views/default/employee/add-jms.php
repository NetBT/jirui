
<article class="page-container">
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
        ])->textInput(['placeholder'=>"请输入合同编号", 'class' => 'input-text'])?>
    </div>
    <?php \yii\bootstrap\ActiveForm::end(); ?>
    <div class="col-xs-12 col-sm-12 cl text-center">
        <button type="button" onclick="add()" class='btn btn-hot btn-md margin-right-30'>保存并授权</button>
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
