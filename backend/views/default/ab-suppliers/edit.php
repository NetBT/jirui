<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>
<article class="page-container">
    <?php $form = ActiveForm::begin([
        'id' => 'edit',
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
    ]);
    ?>
    <?= $form->field($model, 'id',[
        'template' => '<div class="formControls col-xs-7">{input}{error}</div>',
    ])->hiddenInput()?>
    <div class="col-xs-12 cl text-center">
        <?= $form->field($model, 'name',[
            'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
        ])->textInput()?>
    </div>
    <div class="col-xs-12 cl text-center">
        <?= $form->field($model, 'address',[
            'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
        ])->textInput()?>
    </div>
    <div class="col-xs-12 cl text-center">
        <?= $form->field($model, 'tel',[
            'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
        ])->textInput()?>
    </div>
    <div class="col-xs-12 col-sm-12 cl text-center">
        <?= $form->field($model,'link_person',[
            'template' => '{label} <div class="formControls col-xs-7">{input}</div>',
        ])->dropDownList(\backend\models\Employee::getFormListForAB(), [
            'class' => 'selectpicker form-control',
            'id' => 'selectLinkPerson'
        ])?>
    </div>
    <?php ActiveForm::end(); ?>
    <div class="col-xs-12 col-sm-12 cl text-center margin-top-20 margin-bottom-10">
        <button type="button" onclick="doEdit()" class='btn btn-hot btn-md margin-right-30'>确认</button>
        <button type="button" class="layui-layer-close btn btn-default btn-md">取消</button>
    </div>
</article>

<script type="text/javascript">
    $(function () {
        $(".selectpicker").selectpicker();
    });
    function doEdit() {
        ajaxSubmit('<?= \yii\helpers\Url::to(['ab-suppliers/do-edit'])?>', $("#edit").serialize(), function () {
            DataTable.reloadTable();
            layer_close_curr();
        });
    }
</script>
