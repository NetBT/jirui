<article class="page-container">
    <?php $form = \yii\bootstrap\ActiveForm::begin([
            'id' => 'generalAddEdit',
            'options' => ['class' => 'form form-horizontal'],
            'fieldConfig'=>[
                'inputOptions'=>['class'=>'form-control input-text'],//改变input输入框
//                'options' => [],//改变外层自动生成的div
//                'labelOptions' => [],//改变label
                'labelOptions' => [
                    'class' => 'form-label col-xs-2 text-right'
                ],
                'errorOptions' => [                                 //修改error样式和标签
                    'tag' => 'label',
                    'class' => 'error'
                ],
            ],
            'method' => 'post',
            'enableAjaxValidation' => true,
            'validationUrl' => \yii\helpers\Url::to(['combo/validate-form', 'type' => 'generalCombo']),
        ]
    );
    ?>
    <?= $form->field($model,'id',[
        'template' => '{input}',
        'options' => ['class' => '']
    ])->hiddenInput()?>

    <div class="col-xs-12 col-sm-12 cl text-center">
        <?= $form->field($model,'combo_name',[
            'template' => '{label} <div class="formControls col-xs-9">{input}{error}</div>',
        ])->textInput(['placeholder'=>"套系名称"])?>
    </div>

    <div class="col-xs-12 col-sm-12 cl text-center">
        <?= $form->field($model,'combo_price',[
            'template' => '{label} <div class="formControls col-xs-9">{input}{error}</div>',
        ])->textInput(['placeholder'=>"套系价格"])?>
    </div>

    <div class="col-xs-12 col-sm-12 cl text-center margin-bottom-30">
        <?= $form->field($model,'combo_discount',[
            'template' => '{label} <div class="formControls col-xs-9">{input}{error}
        <label class="notice text-left">注意:95折请填写0.95；如果填写多个折扣，请用英文逗号隔开，如：0.95,0.85,0.8。若不想添加折扣，请留空。</label>
        </div>',
        ])->textInput(['placeholder'=>"套系折扣",'id' => 'generalComboDiscount','onChange' => 'validDiscount()'])?>
    </div>

    <div class="col-xs-12 col-sm-12 cl text-center">
        <?= $form->field($model,'combo_integral',[
            'template' => '{label} <div class="formControls col-xs-9">{input}{error}</div>',
        ])->textInput(['placeholder'=>"套系积分"])?>
    </div>

    <div class="col-xs-12 col-sm-12 cl text-center">
        <?= $form->field($model,'register_count',[
            'template' => '{label} <div class="formControls col-xs-9">{input}{error}</div>',
        ])->textInput(['placeholder'=>"入底/入册"])?>
    </div>

    <div class="col-xs-12 col-sm-12 cl text-center">
        <?= $form->field($model,'combo_clothing',[
            'template' => '{label} <div class="formControls col-xs-9">{input}{error}</div>',
        ])->textInput(['placeholder'=>"服装造型"])?>
    </div>

    <div class="col-xs-12 col-sm-12 cl text-center">
        <div class="form-group ">
            <label class="form-label col-xs-2 text-right">套系商品</label>
            <div class="fromControls col-xs-9 text-l"  id="">
                <button type="button" onclick="goodsDetail()" class='btn btn-secondary btn-sm'>选择商品</button>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 cl text-center">
        <?php $str = '';if($goodsList):?>
            <?php foreach ($goodsList as $key => $value): ?>
                <?php $str .= '<label class="checkbox-inline"><input type="checkbox" value="'.$key.'" class="disabled" name="Combo[goods_content][]" checked >'.$value.'</label>'?>
            <?php endforeach;?>
        <?php endif;?>
        <?= $form->field($model,'goods_content',[
            'template' => '{label} <div class=" formControls col-xs-9 text-left" id="goodsContent">'.$str.'</div>',
        ])?>
    </div>

    <div class="col-xs-12 col-sm-12 cl text-center">
        <?= $form->field($model,'mark',[
            'template' => '{label} <div class="formControls col-xs-9">{input}{error}</div>',
        ])->textarea(['placeholder'=>"备注"])?>
    </div>

    <?php \yii\bootstrap\ActiveForm::end(); ?>
    <div class="col-xs-12 col-sm-12 cl text-center margin-top-20 margin-bottom-10">
        <button type="button" onclick="add()" class='btn btn-hot btn-md margin-right-30'>确认</button>
        <button type="button" class="btn btn-default btn-md layui-layer-close">取消</button>
    </div>
</article>
<script>
    function goodsDetail(){
        layer_show({}, '选择商品', '<?= \yii\helpers\Url::to(['combo/goods-detail'])?>',1000,'');
    }

    function add() {
        DataTable.id = '#generalComboList';
        ajaxSubmitForm('#generalAddEdit', '<?= \yii\helpers\Url::to(['combo/general-add-edit'])?>');
    }

    function validDiscount()
    {
        var currentValue = $('#generalComboDiscount').val();
        var currArr = currentValue.split(',');
        $.each(currArr, function(k,i) {
            if (isNaN(i) || i < 0 || i >= 1) {
                layer.msg(i + '折扣错误',{icon:0,time:2000});
            }
        });
    }
</script>
