<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>
<article class="page-container">

    <?php $form = ActiveForm::begin([
        'id' => 'addEditEducation',
        'options' => ['class' => 'form form-horizontal col-sm-12 clear-padding'],
        'fieldConfig'=>[
//                'options'=>['class'=>'row cl'],//改变class类
            'errorOptions' => [
                'tag' => 'label',
                'class' => 'error'
            ],
            'labelOptions' => [
                'class' => 'control-label col-xs-4 col-sm-2'
            ],
        ],
        'method' => 'post',
//        'enableAjaxValidation' => true,
//        'validationUrl' => \yii\helpers\Url::to(['resume/validate-form','type' => 'editPwd']),

    ]);
    ?>
    <!--  隐藏input框   隐藏最外层div  -->
    <?=$form->field($model,'id',[
        'template' => '{input}',
        'options' => ['class' => '']
    ])->hiddenInput()?>

    <?=$form->field($model,'resume_id',[
        'template' => '{input}',
        'options' => ['class' => '']
    ])->hiddenInput()?>

    <?= $form->field($model,'school_name',['template' =>
        '<div class="">
            {label}
            <div class="formControls col-xs-4 col-sm-9 clear-padding">{input}{error}</div>
            </div>',

    ])->textInput(['placeholder'=>"学校名称"])?>

    <?= $form->field($model,'major',['template' =>
        '<div class="">
            {label}
            <div class="formControls col-xs-4 col-sm-9 clear-padding">{input}{error}</div>
            </div>',
    ])->textInput(['placeholder'=>"专业"])?>


    <?= $form->field($model,'final',[
        'template' => '{label} <div class="formControls col-xs-7 clear-padding">{input}{error}</div>',
    ])->textInput(['placeholder'=>"毕业时间", 'id' => 'final'])?>


    <?php ActiveForm::end(); ?>

    <div class="row cl">
        <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2 clear-padding">
            <input class="btn btn-primary radius" type="button" onclick="saveEducation()" value="&nbsp;&nbsp;提交&nbsp;&nbsp;">
        </div>
    </div>

</article>
<script>
    $(function(){
        $("#final").on('click', function() {
            WdatePicker({readOnly: true, dateFmt: 'yyyy-MM-dd'});
        });
    });
    //关闭最新弹出的layer层
    //    layer.close(layer.index);
    function saveEducation()
    {
        $.ajax({
            url : '<?= \yii\helpers\Url::to(['resume/add-edit-education'])?>',
            type : 'POST',
            async: false,
            data : $('#addEditEducation').serialize(),
            dataType : 'JSON',
            beforeSend: function ()
            {
                if ($(".has-error", '#addEditEducation').length)
                {
                    return false;
                }
            },
            success: function(data)
            {
                if(data.code == 1000)
                {
                    var falg = true;
                    var html = '<tr id="table-education-'+data.data.id+'">';
                    var htmlTd = '';
                    if(data.data){
                        htmlTd += '<td>'+data.data.final+'</td>';
                        htmlTd += '<td>'+data.data.school_name+'</td>';
                        htmlTd += '<td>'+data.data.major+'</td>';
                        htmlTd += '<td><a onClick="education.editModal('+data.data.id+')" href="javascript:;" title="编辑"><i class="fa fa-pencil"></i></a> ' +
                            '<a onClick="education.deleteModal(this,'+data.data.id+')" href="javascript:;" title="删除"><i class="fa fa-remove"></i></a>'+
                            '</td>';
                    }
                    html = html + htmlTd + '</tr>';
                    $('tbody','#table-education').find('tr').each(function(index){
                        if ($(this).attr('id') == 'table-education-'+data.data.id)
                        {
                            falg = false;
                            $(this).html(htmlTd);
                        }
                    });
                    if(falg){
                        $('tbody','#table-education').append(html);
                    }
                    layer.close(layer.index);
                    layer.msg(data.message,{icon:6,time:2000});
                } else {
                    layer.msg(data.message,{icon:5,time:2000});
                }
            },
            error: function()
            {
                layer.msg('网络错误',{icon:5,time:2000});
            }
        })
    }
</script>

