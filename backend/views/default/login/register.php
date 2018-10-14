<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>
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
    <div class="register_box text-center">
        <h2 class="">用户注册</h2>
        <?php $form = ActiveForm::begin([
                'id' => 'registerEmployeeForm',
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
//                'enableAjaxValidation' => true,
//                'validationUrl' => \yii\helpers\Url::to(['login/validate-form', 'type' => 'registerEmployee']),
            ]
        );
        ?>
        <?= $form->field($model,'login_name',['template' => '<div class="form-input">{input}{error}</div>',
            'errorOptions' => [
                'tag' => 'label',
                'class' => 'error'
            ],
        ])->textInput(['placeholder'=>"请输入用户名", 'class' => 'input_login_name'])?>
        <?= $form->field($model,'password',['template' => '<div class="form-input">{input}{error}</div>',
            'errorOptions' => [
                'tag' => 'label',
                'class' => 'error'
            ],
        ])->passwordInput(['placeholder'=>"请输入密码", 'class' => 'input_password'])?>

        <?= $form->field($model,'re_password',['template' => '<div class="form-input">{input}{error}</div>',
            'errorOptions' => [
                'tag' => 'label',
                'class' => 'error'
            ],
        ])->passwordInput(['placeholder'=>"请再次输入密码", 'class' => 'input_password'])?>

        <?= $form->field($model,'code',[
            'template' => '<div class="form-input">{input}{error}</div>',
            'errorOptions' => [                                 //修改error样式和标签
                'tag' => 'label',
                'class' => 'error',
                'style' => 'left:-83px;',
            ],
        ])->error()->widget(\yii\captcha\Captcha::className(),
            [
                'captchaAction'=> 'login/captcha',//指定captcha所在的控制器路径，默认是‘site/captcha’，不换到指定位置的话，很容易，验证码就显示不出来
                'imageOptions' => ['class' => 'size-L','style' => 'height:41px;float:right;margin-top:20px;margin-right:45px;'],
                'options' => ['class' => 'form-input size-L','placeholder'=> "验证码",'style' => "width:85px;border:1px solid #ccc"],
                'template' => '{input}{image}',
            ])->label(false)?>
        <?= Html::submitButton(' 注    册 ',['class' => 'btn'])?>
        <?php ActiveForm::end(); ?>

        <!--            <p class="left">还没有账号？请 <a href="#">注册</a></p>-->
        <!--            <a class="right">忘记密码？</a>-->
    </div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>







