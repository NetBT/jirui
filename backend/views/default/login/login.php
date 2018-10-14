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

<div class="login ">
        <div class="login_box">
            <?php $form = ActiveForm::begin([
                    'id' => 'loginEmployeeForm',
                    'options' => ['class' => ''],
                    'method' => 'post']
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

            <?= $form->field($model, 'rememberMe',['checkboxTemplate' => '<div class="checkbox">{input}{label}</div>',
            ])->checkbox(['class' => 'input_checkbox']) ?>
            <?= Html::submitButton(' 登    录 ',['class' => 'btn'])?>
            <?php ActiveForm::end(); ?>

            <?php if (\common\models\Functions::getCommonByKey('member_register_on_off') == 1) :?>
            <p class="left">还没有账号？请 <a href="<?= \yii\helpers\Url::to(['login/register'])?>">注册</a></p>
            <?php endif;?>
            <a class="right" href="<?= \yii\helpers\Url::to(['login/forget-password'])?>">忘记密码？</a>
        </div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>







