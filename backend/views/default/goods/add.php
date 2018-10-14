
<article class="page-container col-xs-12">
    <?php $form = \yii\bootstrap\ActiveForm::begin([
            'id' => 'addGoods',
            'options' => ['class' => 'form form-horizontal'],
            'method' => 'post',
            'enableAjaxValidation' => true,
            'validationUrl' => \yii\helpers\Url::to(['goods/validate-form', 'type' => 'add']),
        ]
    );
    ?>
    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($model,'goods_code',[
            'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
            'errorOptions' => [
                'tag' => 'label',
                'class' => 'error'
            ],
            'labelOptions' => [
                'class' => 'form-label col-xs-5 text-right'
            ]
        ])->textInput(["value" => \backend\models\Goods::makeGoodsCode(), 'class' => 'form-control input-text'])?>
    </div>
    <div class="col-xs-6 col-sm-6 cl text-center">
    <?= $form->field($model,'goods_name',[
        'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
        'errorOptions' => [
            'tag' => 'label',
            'class' => 'error'
        ],
        'labelOptions' => [
            'class' => 'form-label col-xs-3 text-right'
        ]
        ])->textInput(['placeholder'=>"请输入商品名称", 'class' => 'input-text form-control '])?>
    </div>
    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($model,'goods_color', [
            'labelOptions' => [
                'class' => 'form-label col-xs-5 text-right'
            ],
            'options' => ['class' => '']
        ])->inline()->radioList(\common\models\Status::getGoodsDefaultColor(), [
            'template' => '{label}<div class="formControls col-xs-5 text-left" id="colorList">{input}</div><div class="col-xs-2 pull-right text-right">
                <button type="button" class="btn btn-primary size-MINI" onclick="addGoodsColor()">添加颜色</button>
            </div>\'',
            'value' => \common\models\Status::AB_STORE_STATUS_UNLOCK])?>
    </div>
    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($model,'goods_size', [
            'labelOptions' => [
                'class' => 'form-label col-xs-3 text-right'
            ]
        ])->inline()->radioList(\common\models\Status::getGoodsDefaultSize(), [
            'template' => '{label}<div class="formControls col-xs-5 text-left" id="sizeList">{input}</div> 
            <div class="col-xs-2 text-right">
                <button type="button" class="btn btn-primary size-MINI" onclick="addGoodsSize()">添加尺寸</button>
            </div>',
            'value' => \common\models\Status::AB_STORE_STATUS_UNLOCK])?>
    </div>
    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($model,'goods_texture',[
            'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
            'errorOptions' => [
                'tag' => 'label',
                'class' => 'error'
            ],
            'labelOptions' => [
                'class' => 'form-label col-xs-5 text-right'
            ]
        ])->textInput(['placeholder'=>"材质", 'class' => 'input-text form-control '])?>
    </div>
    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($model,'goods_style',[
            'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
            'errorOptions' => [
                'tag' => 'label',
                'class' => 'error'
            ],
            'labelOptions' => [
                'class' => 'form-label col-xs-3 text-right'
            ]
        ])->textInput(['placeholder'=>"请填写风格", 'class' => 'input-text form-control '])?>
    </div>
    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($model,'goods_num',[
            'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
            'errorOptions' => [
                'tag' => 'label',
                'class' => 'error'
            ],
            'labelOptions' => [
                'class' => 'form-label col-xs-5 text-right'
            ]
        ])->textInput(['placeholder'=>"请填写商品数量", 'class' => 'input-text form-control '])?>
    </div>
    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($model,'goods_price',[
            'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
            'errorOptions' => [
                'tag' => 'label',
                'class' => 'error'
            ],
            'labelOptions' => [
                'class' => 'form-label col-xs-3 text-right'
            ]
        ])->textInput(['placeholder'=> '请填写价格','class' => 'input-text form-control '])?>
    </div>
    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($model,'goods_cost',[
            'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
            'errorOptions' => [
                'tag' => 'label',
                'class' => 'error'
            ],
            'labelOptions' => [
                'class' => 'form-label col-xs-5 text-right'
            ]
        ])->textInput(['placeholder'=>"请填写成本价", 'class' => 'input-text form-control '])?>
    </div>
    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($model,'goods_discount',[
            'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
            'errorOptions' => [
                'tag' => 'label',
                'class' => 'error'
            ],
            'labelOptions' => [
                'class' => 'form-label col-xs-3 text-right'
            ]
        ])->textInput(['placeholder'=>"折扣单价", 'class' => 'input-text form-control '])?>
    </div>
    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($model,'discount_start_time',[
            'template' => '{label} <div class="formControls col-xs-3">{input}{error}</div>',
            'errorOptions' => [
                'tag' => 'label',
                'class' => 'error'
            ],
            'labelOptions' => [
                'class' => 'form-label col-xs-5 text-right'
            ],
            'options' => [
                'class' => ''
            ]
        ])->textInput(['placeholder'=>"开始时间", 'id' => 'startTime', 'class' => 'input-text form-control '])?>
        <?= $form->field($model,'discount_end_time',[
            'template' => '<div class="formControls col-xs-3">{input}{error}</div>',
            'errorOptions' => [
                'tag' => 'label',
                'class' => 'error'
            ],
            'options' => [
                    'class' => ''
            ]
        ])->textInput(['placeholder'=>"结束时间",'id' => 'endTime', 'class' => 'input-text form-control '])?>
    </div>
    <div class="col-xs-6 col-sm-6 cl text-center">
        <?= $form->field($model,'goods_category',[
            'template' => '{label} <div class="formControls col-xs-7">{input}</div>',
            'labelOptions' => [
                'class' => 'form-label col-xs-3 text-right'
            ]
        ])->dropDownList(\backend\models\GoodsCategory::getCategoryMap(),[
            'class' => 'selectpicker form-control',
            'id' => 'selectCategory',
        ])?>
    </div>
    <?php \yii\bootstrap\ActiveForm::end(); ?>
    <div class="col-xs-12">
        <label class="col-xs-5 text-r" style="width: 20%">图片</label>
        <div class="col-xs-7" style="width: 80%">
            <div class="album-item" style="width: 200px;">
                <div class="album-img" id="album_cover" onclick="goods_image_add();">
                    <img src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/PjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMTQwIiBoZWlnaHQ9IjE0MCIgdmlld0JveD0iMCAwIDE0MCAxNDAiIHByZXNlcnZlQXNwZWN0UmF0aW89Im5vbmUiPjwhLS0KU291cmNlIFVSTDogaG9sZGVyLmpzLzE0MHgxNDAKQ3JlYXRlZCB3aXRoIEhvbGRlci5qcyAyLjYuMC4KTGVhcm4gbW9yZSBhdCBodHRwOi8vaG9sZGVyanMuY29tCihjKSAyMDEyLTIwMTUgSXZhbiBNYWxvcGluc2t5IC0gaHR0cDovL2ltc2t5LmNvCi0tPjxkZWZzPjxzdHlsZSB0eXBlPSJ0ZXh0L2NzcyI+PCFbQ0RBVEFbI2hvbGRlcl8xNTEwYmJhZjQzYSB0ZXh0IHsgZmlsbDojQUFBQUFBO2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1mYW1pbHk6QXJpYWwsIEhlbHZldGljYSwgT3BlbiBTYW5zLCBzYW5zLXNlcmlmLCBtb25vc3BhY2U7Zm9udC1zaXplOjEwcHQgfSBdXT48L3N0eWxlPjwvZGVmcz48ZyBpZD0iaG9sZGVyXzE1MTBiYmFmNDNhIj48cmVjdCB3aWR0aD0iMTQwIiBoZWlnaHQ9IjE0MCIgZmlsbD0iI0VFRUVFRSIvPjxnPjx0ZXh0IHg9IjQ0LjA1NDY4NzUiIHk9Ijc0LjUiPjE0MHgxNDA8L3RleHQ+PC9nPjwvZz48L3N2Zz4=" alt="..." class="radius">
                </div>
                <div class="album-title text-center">
                    图册
                </div>
                <div class="album-bg">
                    <div class="album-bg-Fir"></div>
                    <div class="album-bg-Sec"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 cl text-center">
        <button type="button" onclick="add()" class='btn btn-hot btn-md margin-right-30'>保存</button>
        <button type="button" onclick="layer_close()" class="btn btn-default btn-md">取消</button>
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
        $("#selectCategory").selectpicker({
            title: '请选择分类',
            style: 'btn-default',
            width: '100%',
            liveSearch: true
        });
    });

    function addGoodsColor() {
        layer_show({}, '添加商品颜色', '<?= \yii\helpers\Url::to(['goods/add-color'])?>', 300, 200);
    }
    function addGoodsSize() {
        layer_show({}, '添加商品尺寸', '<?= \yii\helpers\Url::to(['goods/add-size'])?>', 300, 200);
    }

    function goods_image_add(){
        layer_show({}, '', '<?= \yii\helpers\Url::to(['goods-images/edit'])?>');
    }

    function add() {
        ajaxSubmitForm('#addGoods', '<?= \yii\helpers\Url::to(['goods/do-add'])?>');
    }

</script>
