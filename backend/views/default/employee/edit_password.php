<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>
<article class="page-container">

    <?php $form = ActiveForm::begin([
        'id' => 'editPwdEmployee',
        'options' => ['class' => 'form form-horizontal col-sm-12 clear-padding'],
//        'fieldConfig'=>[
//                'options'=>['class'=>'row cl'],//改变class类
//            ],
        'method' => 'post',
        'enableAjaxValidation' => true,
        'validationUrl' => \yii\helpers\Url::to(['employee/validate-form','type' => 'editPwd']),
        'enableClientValidation' => true,
        'validateOnSubmit'=> true,     //提交时的验证
//        'clientOptions' => [
//            'validateOnSubmit'=> true,     //提交时的验证
//        ],
    ]);
    ?>
    <!--  隐藏input框   隐藏最外层div  -->
    <?=$form->field($model,'id',[
            'template' => '{input}',
            'options' => ['class' => '']
    ])->hiddenInput(['value'=>$id])?>

    <?= $form->field($model,'password',['template' =>
        '<div class="">
            {label}
            <div class="formControls col-xs-4 col-sm-9 clear-padding">{input}{error}</div>
            </div>',
        'errorOptions' => [
            'tag' => 'label',
            'class' => 'error'
        ],
        'labelOptions' => [
            'class' => 'control-label col-xs-4 col-sm-2'
        ],

    ])->passwordInput(['placeholder'=>"密码"])?>

    <?= $form->field($model,'new_password',['template' =>
        '<div class="">
            {label}
            <div class="formControls col-xs-4 col-sm-9 clear-padding">{input}{error}</div>
            </div>',
        'errorOptions' => [
            'tag' => 'label',
            'class' => 'error'
        ],
        'labelOptions' => [
            'class' => 'control-label col-xs-4 col-sm-2'
        ],
    ])->passwordInput(['placeholder'=>"新密码"])?>

    <?= $form->field($model,'re_new_password',['template' =>
        '<div class="">
            {label}
            <div class="formControls col-xs-4 col-sm-9 clear-padding">{input}{error}</div>
            </div>',
        'errorOptions' => [
            'tag' => 'label',
            'class' => 'error'
        ],
        'labelOptions' => [
            'class' => 'control-label col-xs-4 col-sm-2'
        ],
    ])->passwordInput(['placeholder'=>"确认新密码"])?>

    <?php ActiveForm::end(); ?>

    <div class="row cl">
        <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2 clear-padding">
            <input class="btn btn-primary radius margin-right-30" type="button" onclick="editPwd()" value="&nbsp;&nbsp;提交&nbsp;&nbsp;">
            <button type="button" class="btn btn-default btn-md layui-layer-close">取消</button>
        </div>
    </div>

</article>
<script>
    function editPwd()
    {
        ajaxSubmitForm('#editPwdEmployee', '<?= \yii\helpers\Url::to(['employee/do-update-password'])?>');
    }
</script>

