<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>
<!--此处是处理 '您提交的表单无法验证'的问题-->
<?= Html::csrfMetaTags() ?>
<!--此处是处理 '您提交的表单无法验证'的问题-->
<?php $this->beginPage() ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>集睿</title>
    <?php \backend\assets\LoginAsset::register($this);?>

    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="login">
    <div class="forget_box text-center">
        <h2 class="">找回密码</h2>
        <?php $form = ActiveForm::begin([
                'id' => 'forgetPasswordForm',
                'options' => ['class' => ''],
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
                'validationUrl' => \yii\helpers\Url::to(['login/validate-form', 'type' => 'forgetPassword']),
            ]
        );
        ?>
        <?= $form->field($model,'login_name',['template' => '<div class="form-input">{input}{error}</div>',
            'errorOptions' => [
                'tag' => 'label',
                'class' => 'error'
            ],
        ])->textInput(['placeholder'=>"输入要找回的账号", 'class' => 'input_login_name'])?>

        <?= $form->field($model,'tel',['template' => '<div class="form-input">{input}{error}</div>',
            'errorOptions' => [
                'tag' => 'label',
                'class' => 'error'
            ],
        ])->textInput(['placeholder'=>"输入对应的手机号", 'class' => 'input_login_name'])?>
        <?= $form->field($model,'new_password',['template' => '<div class="form-input">{input}{error}</div>',
            'errorOptions' => [
                'tag' => 'label',
                'class' => 'error'
            ],
        ])->passwordInput(['placeholder'=>"请输入新密码", 'class' => 'input_password'])?>

        <?= $form->field($model,'re_new_password',['template' => '<div class="form-input">{input}{error}</div>',
            'errorOptions' => [
                'tag' => 'label',
                'class' => 'error'
            ],
        ])->passwordInput(['placeholder'=>"确认密码", 'class' => 'input_password'])?>

        <?= $form->field($model,'phone_code', ['template' => '<div class="form-input">{input}{error} 
        <a class="phone-code" href="javascript:void(0)" onclick="getRegisterCode($(this))">获取验证码</a>
        </div>',
                'errorOptions' => [
                    'tag' => 'label',
                    'class' => 'code-error'
                ],
            ])->textInput(['placeholder'=>"手机验证码",'style' => "width:175px;",'class' => 'input_phone_code'])?>
        <?= Html::submitButton(' 确    认 ',['class' => 'btn'])?>
        <?php ActiveForm::end(); ?>
        <!--            <p class="left">还没有账号？请 <a href="#">注册</a></p>-->
        <!--            <a class="right">忘记密码？</a>-->
    </div>
</div>
<?php $this->endBody() ?>
</body>
<script>
    var i = 60;
    var interval = null;
    function getRegisterCode(obj) {
        var obj = $(".phone-code");
        var phone = $("input[name='Employee[tel]']").val();
        $.ajax({
            url: '<?= \yii\helpers\Url::to(['login/send-message-for-forget'])?>',
            type: 'post',
            data: {phone: phone},
            beforeSend: function () {
                if (phone.length !== 11) {
                    alert('请输入正确手机号');
                    return false;
                }
                if (obj.hasClass('send')) {
                    return false;
                }
                if ($(".field-employee-tel").hasClass('has-error')) {
                    return false;
                }
            },
            success: function (result) {
                if (result.code == 1000) {
                    i = 60;
                    interval = setInterval(setCountDown, 1000);
                } else {
                    alert(result.message);
                }
            },
            error: function () {
                alert('发送失败，请重试');
            }
        });
    }
    function setCountDown() {
        var obj = $(".phone-code");
        i--;
        obj.addClass('disabled text-gray send');
        obj.html('已发送(' + i +'s)');
        if (i == 0) {
            obj.removeClass('disabled text-gray send').html('获取验证码');
            clearInterval(interval);
        }
    }
</script>
</html>
<?php $this->endPage() ?>








