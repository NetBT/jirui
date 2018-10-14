<article class="page-container">
    <?php $form = \yii\bootstrap\ActiveForm::begin([
            'id' => 'addMember',
            'options' => ['class' => 'form form-horizontal'],
            'fieldConfig'=>[
                'template' => '{label} <div class="formControls col-xs-7 ">{input}{error}</div>',
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
            'validationUrl' => \yii\helpers\Url::to(['member/validate-form', 'type' => 'add']),
            'successCssClass' => 'has-success notice-success',
            'errorCssClass' => 'has-error notice-error',
        ]
    );
    ?>
    <?= $form->field($model,'id',[
        'template' => '{input}',
        'options' => ['class' => '']
    ])->hiddenInput()?>

    <div class="col-xs-6 col-sm-6 cl text-center" style="display: none">
        <?= $form->field($model,'number',[

        ])->textInput(['placeholder'=>"编号",'value' => \backend\models\Member::getMemberNum(), 'readonly' => ''])?>
    </div>

    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($model,'name',[
        ])->textInput(['placeholder'=>"宝宝姓名"])?>
    </div>

    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($model,'age',[
        ])->textInput(['placeholder'=>"年龄"])?>
    </div>

    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($model,'sex', [
        ])->inline()->radioList(\common\models\Status::sexyMap(),[
            'template' => '{label}<div class="formControls col-xs-3 notice-success">{input}</div>',
        ])?>
    </div>

    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($model,'birthday',[
        ])->textInput(['placeholder'=>"2018-01-01"])?>
    </div>

    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($model,'tel',[
        ])->textInput(['placeholder'=>"联系手机号"])?>
    </div>

    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($model,'parents_name',[
        ])->textInput(['placeholder'=>"家长姓名"])?>
    </div>

    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($model,'parents_baby_link',[
        ])->textInput(['placeholder'=>"与宝宝关系"])?>
    </div>

    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($model,'wechat',[
        ])->textInput(['placeholder'=>"微信"])?>
    </div>

    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($model,'QQ',[
        ])->textInput(['placeholder'=>"QQ"])?>
    </div>

    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($model,'address',[
        ])->textInput(['placeholder'=>"地址限制40字"])?>
    </div>

    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($model,'email',[
        ])->textInput(['placeholder'=>"邮箱"])?>
    </div>

    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($model,'source',[
        ])->dropDownList(\common\models\Status::memberSourceMap(),[
            'class' => 'selectpicker',
            'id' => 'selectSource',
            'onchange' => 'changeSource()',
        ])?>
    </div>

    <div class="col-xs-6 col-sm-6 cl text-center" id="referrerSource" style="display: none">
        <?= $form->field($model,'referrer_id',[
        ])->dropDownList(\backend\models\Member::getFormArray(['is_delete' => \common\models\Status::MEMBER_NOT_DELETE,'business_id' => \backend\models\Common::getBusinessId()],'id','name'),[
            'class' => 'selectpicker',
            'id' => 'selectReferrer',
        ])?>
    </div>

    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($model,'royalty_id',[
        ])->dropDownList(\backend\models\Employee::getFormArray(['status' => \common\models\Status::EMPLOYEE_STATUS_ACTIVE,'alliance_business_id' => \backend\models\Common::getBusinessId()],'id','employee_name'),[
            'class' => 'selectpicker',
            'id' => 'selectRoyalty',
        ])?>
    </div>

    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($model,'spare_tel',[
        ])->textInput(['placeholder'=>"备用电话"])?>
    </div>

    <div class="col-xs-6 col-sm-6">
        <?= $form->field($model,'mark',[
        ])->textarea(['placeholder'=>"备注",'rows' => 4])?>
    </div>

    <?php \yii\bootstrap\ActiveForm::end(); ?>
    <div class="col-xs-12 col-sm-12 cl text-center margin-top-20 margin-bottom-10">
        <button type="button" onclick="add()" class='btn btn-hot btn-md margin-right-30'>确认</button>
        <button type="button" class="btn btn-default btn-md layui-layer-close">取消</button>
    </div>
</article>

<script>
    $(function(){

//        $('#addMember').on('afterValidateAttribute', function (event, attribute, messages) {
//            alert(attribute);
//            return false;
//        });

        $("#selectSource").selectpicker({
            title: '选择来源',
            style: 'btn-default',
            width: '100%',
            liveSearch: true
        });

        $("#selectReferrer").selectpicker({
            title: '选择推荐人(如需要)',
            style: 'btn-default',
            width: '100%',
            liveSearch: true
        });

        $("#selectRoyalty").selectpicker({
            title: '选择提成员工',
            style: 'btn-default',
            width: '100%',
            liveSearch: true
        });
    });

    function changeSource()
    {
        var val =  $("#selectSource").selectpicker('val');
        if(val == '<?= \common\models\Status::MEMBER_SOURCE_LGKTJ?>') {
            $('#referrerSource').css('display','block');
        } else {
            $('#referrerSource').css('display','none');
        }
    }

    function add() {
        ajaxSubmitForm('#addMember', '<?= \yii\helpers\Url::to(['member/add'])?>');
    }
</script>
