
<article class="page-container col-xs-12">
    <?php $form = \yii\bootstrap\ActiveForm::begin([
            'id' => 'add',
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
            'enableAjaxValidation' => true,
            'validationUrl' => \yii\helpers\Url::to(['recruit/validate-form', 'type' => 'add']),
        ]
    );
    ?>
    <div class="col-xs-12 cl text-center">
        <?= $form->field($model,'combo_name',[
            'template' => '{label} <div class="formControls col-xs-4">{input}{error}</div><div class="col-xs-3 text-left text-gray"></div>',
        ])->textInput(['placeholder'=>"请输入套餐名称", 'class' => 'form-control input-text'])?>
    </div>
    <div class="col-xs-12 cl text-center">
    <?= $form->field($model,'vaild_days',[
        'template' => '{label} <div class="formControls col-xs-4">{input}{error}</div><div class="col-xs-3 text-left text-gray"><span class="text-black">天</span>&emsp;(<span class="text-hot">0</span>表示不限制)</div>',
        ])->textInput(['placeholder'=>"0", 'class' => 'input-text form-control '])?>
    </div>
    <div class="col-xs-12 cl text-center">
        <?= $form->field($model,'origin_price',[
            'template' => '{label} <div class="formControls col-xs-4">{input}{error}</div>
<div class="col-xs-3 text-left text-gray"><span class="text-black">元</span>&emsp;(<span class="text-hot">0</span>表示免费)</div>',
        ])->textInput(['placeholder'=>"0.00", 'class' => 'input-text form-control '])?>
    </div>
    <div class="col-xs-12 cl text-center">
        <?= $form->field($model,'discount_price',[
            'template' => '{label} <div class="formControls col-xs-4">{input}{error}</div>
<div class="col-xs-3 text-left text-gray"><span class="text-black">元</span>&emsp;(<span class="text-hot">0</span>表示免费)</div>',
        ])->textInput(['placeholder'=>"0.00", 'class' => 'input-text form-control '])?>
    </div>
    <div class="col-xs-12 cl text-center">
        <?= $form->field($model,'max_concurr',[
            'template' => '{label} <div class="formControls col-xs-4">{input}{error}</div>
<div class="col-xs-3 text-left text-gray"><span class="text-black">条</span>&emsp;(<span class="text-hot">0</span>表示不允许)</div>',
        ])->textInput(['placeholder'=>"0", 'class' => 'input-text form-control '])?>
    </div>
    <div class="col-xs-12 cl text-center">
        <?= $form->field($model,'refresh_time_span',[
            'template' => '{label} <div class="formControls col-xs-4">{input}{error}</div>
<div class="col-xs-3 text-left text-gray"><span class="text-black">分钟</span>&emsp;(<span class="text-hot">0</span>表示不限制)</div>',
        ])->textInput(['placeholder'=>"0", 'class' => 'input-text form-control '])?>
    </div>
    <div class="col-xs-12 cl text-center">
        <?= $form->field($model,'max_refresh_pre_day',[
            'template' => '{label} <div class="formControls col-xs-4">{input}{error}</div>
<div class="col-xs-3 text-left text-gray"><span class="text-black">次</span>&emsp;(<span class="text-hot">0</span>表示不限制)</div>',
        ])->textInput(['placeholder'=>"0", 'class' => 'input-text form-control '])?>
    </div>
    <div class="col-xs-12 cl text-center">
        <?= $form->field($model,'download_resume_num',[
            'template' => '{label} <div class="formControls col-xs-4">{input}{error}</div>
<div class="col-xs-3 text-left text-gray"><span class="text-black">份</span>&emsp;(<span class="text-hot">0</span>表示不允许)</div>',
        ])->textInput(['placeholder'=>"0", 'class' => 'input-text form-control '])?>
    </div>
    <div class="col-xs-12 cl text-center">
        <?= $form->field($model,'invite_candidate_num',[
            'template' => '{label} <div class="formControls col-xs-4">{input}{error}</div>
<div class="col-xs-3 text-left text-gray"><span class="text-black">次</span>&emsp;(<span class="text-hot">0</span>表示不允许)</div>',
        ])->textInput(['placeholder'=>"0", 'class' => 'input-text form-control '])?>
    </div>
    <div class="col-xs-12 cl text-center">
        <?= $form->field($model,'headquarter_recommend_num',[
            'template' => '{label} <div class="formControls col-xs-4">{input}{error}</div>
<div class="col-xs-3 text-left text-gray"><span class="text-black">条</span>&emsp;(<span class="text-hot">0</span>表示不允许)</div>',
        ])->textInput(['placeholder'=>"0", 'class' => 'input-text form-control '])?>
    </div>

    <div class="col-xs-12 cl text-center">
        <?= $form->field($model,'recommend_days',[
            'template' => '{label} <div class="formControls col-xs-4">{input}{error}</div>
<div class="col-xs-3 text-left text-gray"><span class="text-black">天</span></div>',
        ])->textInput(['placeholder'=>"0", 'class' => 'input-text form-control '])?>
    </div>
    <div class="col-xs-12 cl text-center">
        <?= $form->field($model,'top_num',[
            'template' => '{label} <div class="formControls col-xs-4">{input}{error}</div>
<div class="col-xs-3 text-left text-gray"><span class="text-black">条</span>&emsp;(<span class="text-hot">0</span>表示不允许)</div>',
        ])->textInput(['placeholder'=>"0", 'class' => 'input-text form-control '])?>
    </div>
    <div class="col-xs-12 cl text-center">
        <?= $form->field($model,'top_days',[
            'template' => '{label} <div class="formControls col-xs-4">{input}{error}</div>
<div class="col-xs-3 text-left text-gray"><span class="text-black">天</span></div>',
        ])->textInput(['placeholder'=>"0", 'class' => 'input-text form-control '])?>
    </div>
    <div class="col-xs-12 cl text-center">
        <?= $form->field($model,'urgent_post_num',[
            'template' => '{label} <div class="formControls col-xs-4">{input}{error}</div>
<div class="col-xs-3 text-left text-gray"><span class="text-black">条</span>&emsp;(<span class="text-hot">0</span>表示不允许)</div>',
        ])->textInput(['placeholder'=>"0", 'class' => 'input-text form-control '])?>
    </div>
    <div class="col-xs-12 cl text-center">
        <?= $form->field($model,'urgent_days',[
            'template' => '{label} <div class="formControls col-xs-4">{input}{error}</div>
<div class="col-xs-3 text-left text-gray"><span class="text-black">天</span></div>',
        ])->textInput(['placeholder'=>"0", 'class' => 'input-text form-control '])?>
    </div>
    <div class="col-xs-12 cl text-center">
        <?= $form->field($model,'recruit_order',[
            'template' => '{label} <div class="formControls col-xs-4">{input}{error}</div>
<div class="col-xs-3 text-left text-gray">数字越大越靠前</div>',
        ])->textInput(['placeholder'=>"0", 'class' => 'input-text form-control '])?>
    </div>
    <div class="col-xs-12 cl text-center">
        <?= $form->field($model,'show_status', [
        ])->inline()->radioList(\common\models\Status::getRecruitComboShowStatusMap(), [
            'template' => '{label}<div class="formControls col-xs-7 text-left">{input}</div>',
            'value' => \common\models\Status::RECRUIT_COMBO_SHOW_STATUS_NORMAL])?>
    </div>
    <div class="col-xs-12 cl text-center">
        <?= $form->field($model,'allow_member_apply', [
        ])->inline()->radioList(\common\models\Status::getRecruitComboMemberApplyMap(),[
            'template' => '{label}<div class="formControls col-xs-7 text-left">{input}</div>',
        'value' => \common\models\Status::RECRUIT_COMBO_DENY_MEMBER_APPLY])?>
    </div>
    <div class="col-xs-12 cl text-center">
        <?= $form->field($model,'mark',[
            'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
        ])->textInput(['placeholder'=>"其他说明", 'class' => 'input-text form-control '])?>
    </div>
    <?php \yii\bootstrap\ActiveForm::end(); ?>
    <div class="col-xs-12 col-sm-12 cl text-center">
        <button type="button" onclick="add()" class='btn btn-hot btn-md margin-right-30'>保存</button>
        <button type="button" class="btn btn-default btn-md layui-layer-close">取消</button>
    </div>
</article>
<script>

    function add() {
        ajaxSubmitForm('#add', '<?= \yii\helpers\Url::to(['recruit/do-add'])?>');
    }
</script>
