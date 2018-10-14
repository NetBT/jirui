<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>
<article class="page-container">

    <form class="form form-horizontal" id="addEditPost">
        <input type="hidden" name="submit" value="1">
        <input type="hidden" name="id" value="<?php if(isset($role['id']) && !empty($role['id'])):?> <?= $role['id'] ?> <?php endif;?>">
        <div class="form-group">
            <label class="control-label col-xs-4 col-sm-2">权限名称</label>
            <div class="formControls col-xs-8 col-sm-9 clear-padding">
                <input type="text" class="form-control" name="post_name" value="<?php if(isset($role['post_name']) && !empty($role['post_name'])):?><?= $role['post_name'] ?><?php endif;?>">
            </div>
        </div>

        <div class="form-group ">
            <label class="col-xs-12 col-sm-12">权限管理(请勾选权限)</label>
            <div class="formControls col-xs-8 col-sm-9 clear-padding col-sm-offset-2" style="height:300px;overflow:scroll;">
                <table class="table table-striped table-bordered table-hover" id="moduleList">
                    <?php foreach ($module as $key => $value) :?>
                        <tr id="play_type_info_<?= $value['id'] ?>">
                            <td style="width: 8%">
                                <input type="checkbox" name="module_content[]"
                                       <?php if(isset($role['module_content']) && !empty($role['module_content'])) : ?>
                                           <?php foreach ($role['module_content'] as $vv) :?>
                                               <?php if($vv == $value['id']) :?>
                                               checked
                                               <?php endif;?>
                                           <?php endforeach;?>
                                        <?php endif;?>
                                       value="<?= $value['id'] ?>" onclick="relChoose(<?= $value['id'] ?>);" id="check_box_<?= $value['id'] ?>">
                            </td>
                            <td><?= $value['path_count'].$value['module_title']?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
            <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2 clear-padding">
                <button type="button" class="btn btn-secondary size-MINI" onclick="choosePlay(true)">全选</button>&nbsp;&nbsp;&nbsp;
                <button type="button" class="btn btn-success size-MINI" onclick="choosePlay(false)">全不选</button>
            </div>
        </div>

    </form>

    <div class="row cl">
        <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2 clear-padding">
            <input class="btn btn-primary radius margin-right-30" type="button" onclick="addEdit()" value="&nbsp;&nbsp;提交&nbsp;&nbsp;">
            <button type="button" class="btn btn-default btn-md layui-layer-close">取消</button>
        </div>
    </div>
</article>

<script>
    function addEdit()
    {
        ajaxSubmitForm("#addEditPost",'<?= \yii\helpers\Url::to(['a-b/post-add-edit'])?>');

    }

    //关联选择
    function relChoose(id)
    {
        $.ajax({
            url : '<?= \yii\helpers\Url::to(['common/rel-choose']);?>',
            type : 'POST',
            async : false,
            data : {id : id},
            dataType : 'JSON',
            success : function(data)
            {
                var currentCheck = $("#check_box_"+id+"").prop("checked");
                if(data.parentId) {
                    //父级元素都要选择
                    $.each(data.parentId,function(k,v){
                        $("#check_box_"+v+"").prop("checked","true");
                    });
                }
                if(data.childId) {
                    //子集元素视情况而定
                    $.each(data.childId,function(k,v){
                        $("#check_box_"+v+"").prop("checked",currentCheck);
                    });
                }
            },
            error : function()
            {
                layer.msg('网络错误',{icon:5,time:2000});
            }
        });
    }

    /**
     * 全选 全不选
     * @param type
     */
    function choosePlay(type)
    {
        $("input[type='checkbox']","#moduleList").each(function()
        {
            $(this).prop("checked",type);
        });
    }
</script>