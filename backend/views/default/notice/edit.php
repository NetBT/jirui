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
                'class' => 'form-label col-xs-3 text-right'
            ],
            'errorOptions' => [                                 //修改error样式和标签
                'tag' => 'label',
                'class' => 'error'
            ],
        ],
        'method' => 'post',
    ]);
    ?>
    <?= $form->field($model,'id',[
        'template' => '{input}',
        'options' => ['class' => '']
    ])->hiddenInput()?>
    <div class="col-xs-12 cl text-center">
        <?= $form->field($model, 'title',[
            'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',
        ])->textInput()?>
    </div>
    <!--    <div class="col-xs-3 cl text-right">-->
    <!--        <label>内容</label>-->
    <!--    </div>-->
    <div class="col-xs-12 cl">
        <?= $form->field($model, 'content',[
            'template' => '{label} <div class="formControls col-xs-7">{input}{error}</div>',

        ])->widget('kucha\ueditor\UEditor',[
            'clientOptions' => [
                //编辑区域大小
                'initialFrameHeight'=>'600',
                'initialFrameWidth'=>'100%',
                //设置语言
                'lang' =>'zh-cn', //中文为 zh-cn
                'toolbars' => [
                    [
                        'source', 'undo', 'redo', '|',
                        'fontsize', 'snapscreen',
                        'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'removeformat',
                        'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|',
                        'simpleupload', 'simpleupload', 'emotion', 'insertvideo','|',
                        'lineheight', 'justifyleft', 'justifyright', 'justifycenter', 'justifyjustify', '|',
                        'indent', '|'
                    ],
                ]
            ],
        ]);?>
    </div>
    <?php ActiveForm::end(); ?>
    <div class="col-xs-12 col-sm-12 cl text-center margin-top-20 margin-bottom-10">
        <button type="button" onclick="save()" class='btn btn-hot btn-md margin-right-30'>保存</button>
        <button type="button" onclick="layer_close()" class="btn btn-default btn-md">取消</button>
    </div>
</article>

<script type="text/javascript">
    function save() {
        ajaxSubmit('<?= \yii\helpers\Url::to(['notice/do-edit'])?>', $("#edit").serialize(), function () {
            parent.DataTable.reloadTable();
            layer_close();
        });
    }
</script>
