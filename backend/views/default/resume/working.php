<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>
<article class="page-container">

    <?php $form = ActiveForm::begin([
        'id' => 'addEditWorking',
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

    <?= $form->field($model,'company_name',['template' =>
        '<div class="">
            {label}
            <div class="formControls col-xs-4 col-sm-9 clear-padding">{input}{error}</div>
            </div>',

    ])->textInput(['placeholder'=>"公司名称"])?>

    <?= $form->field($model,'post_name',['template' =>
        '<div class="">
            {label}
            <div class="formControls col-xs-4 col-sm-9 clear-padding">{input}{error}</div>
            </div>',
    ])->textInput(['placeholder'=>"职位名称"])?>


    <?= $form->field($model,'start_time',[
        'template' => '{label} <div class="formControls col-xs-7 clear-padding">{input}{error}</div>',
    ])->textInput(['placeholder'=>"入职时间", 'class' => 'form-control working_time'])?>

    <?= $form->field($model,'end_time',[
        'template' => '{label} <div class="formControls col-xs-7 clear-padding">{input}{error}</div>',
    ])->textInput(['placeholder'=>"离职时间", 'class' => 'form-control working_time'])?>


    <?php ActiveForm::end(); ?>

    <div class="row cl">
        <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2 clear-padding">
            <input class="btn btn-primary radius" type="button" onclick="saveWorking()" value="&nbsp;&nbsp;提交&nbsp;&nbsp;">
        </div>
    </div>

</article>
<script>
    $(function(){
        $(".working_time").on('click', function() {
            WdatePicker({readOnly: true, dateFmt: 'yyyy-MM-dd'});
        });
    });
    //关闭最新弹出的layer层
    //    layer.close(layer.index);
    function saveWorking()
    {
        $.ajax({
            url : '<?= \yii\helpers\Url::to(['resume/add-edit-working'])?>',
            type : 'POST',
            async: false,
            data : $('#addEditWorking').serialize(),
            dataType : 'JSON',
            beforeSend: function ()
            {
                if ($(".has-error", '#addEditWorking').length)
                {
                    return false;
                }
            },
            success: function(data)
            {
                if(data.code == 1000)
                {
                    var falg = true;
                    var html = '<tr id="table-working-'+data.data.id+'">';
                    var htmlTd = '';
                    if(data.data){
                        htmlTd += '<td>'+data.data.time+'</td>';
                        htmlTd += '<td>'+data.data.company_name+'</td>';
                        htmlTd += '<td>'+data.data.post_name+'</td>';
                        htmlTd += '<td><a onClick="working.editModal('+data.data.id+')" href="javascript:;" title="编辑"><i class="fa fa-pencil"></i></a> ' +
                            '<a onClick="working.deleteModal(this,'+data.data.id+')" href="javascript:;" title="删除"><i class="fa fa-remove"></i></a>'+
                            '</td>';
                    }
                    html = html + htmlTd + '</tr>';
                    $('tbody','#table-working').find('tr').each(function(index){
                        if ($(this).attr('id') == 'table-working-'+data.data.id)
                        {
                            falg = false;
                            $(this).html(htmlTd);
                        }
                    });
                    if(falg){
                        $('tbody','#table-working').append(html);
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

