<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-3"><b>我的简历</b>My Resume</div>
        <div class="header-search col-xs-4 text-r">
            <button class="btn btn-success " id="doRefresh">刷新</button>

            <?php if($num['total']) : ?>
                <?php if($num['remain'] > 0 ) : ?>
                    <button class="btn btn-yellow" onclick="resumeList.beforeAddModal()">新 建</button>
                <?php else:?>
                    <button class="btn btn-default disabled" onclick="already()">新 建</button>
                <?php endif;?>
                <span class="text-gray font-xs">(已发布<span class="text-hot"><?=  $num['already']?></span>份简历 还可创建<span class="text-hot"><?=  $num['remain']?></span>份)</span>
            <?php else:?>
                <span class="text-gray font-xs">(<span class="text-hot">无限制</span>)</span>
            <?php endif;?>
        </div>
        <div class="clear"></div>
    </div>
    <div class="main-body col-xs-12">
        <table class="tab" width="100%" border="0" cellspacing="0" cellpadding="0" id="resumeList"></table>
    </div>
</div>
<script>
    $(function(){
        DataTable.init('initResumeList','#resumeList','<?= \yii\helpers\Url::to(['resume/list'])?>',{});
        //刷新
        $("#doRefresh").bind('click',function(){
            window.location.reload();
        });
    });

    var resumeList = {
        defaultUrl : '<?= \yii\helpers\Url::to(['resume/update-default'])?>',
        deleteUrl : '<?= \yii\helpers\Url::to(['resume/delete'])?>',
        editUrl : '<?= \yii\helpers\Url::to(['resume/add-edit'])?>',
        addUrl : '<?= \yii\helpers\Url::to(['resume/add-edit'])?>',
        defaultModal : function(obj,id){
            var defaultVal = $(obj).attr('title');
            var _this = this;
            layer.confirm('确认要'+defaultVal+'吗？',function(index){
                ajaxSubmit(_this.defaultUrl, {id: id, defaultVal : defaultVal}, function () {
//                    DataTable.id = '#resumeList';
                    DataTable.drawTable();
                });
            });
        },

        beforeAddModal : function()
        {
            var _this = this;
            $.ajax({
                url : '<?= \yii\helpers\Url::to(['resume/get-resume-num'])?>',
                type : 'POST',
                dataType : 'json',
                success : function(data)
                {
                    if(data.remain <= 0)
                    {
                        layer.msg('简历数量已超过设置数量，不能创建简历',{icon:5,time:2000});
                    } else {
                        _this.addModal();
                    }
                },
                error : function () {
                    layer.msg('网络错误',{icon:6,time:2000});
                }
            })
        },

        addModal  : function()
        {
            layer_show_full('添加简历', this.addUrl);
        },

        editModal : function(id)
        {
            layer_show_full('修改简历', this.editUrl+'?id='+id);
        },

        deleteModal : function(id){
            var _this = this;
            layer.confirm('确认要删除吗？',function(index){
                ajaxSubmit(_this.deleteUrl, {id: id}, function () {
                    DataTable.drawTable();
                });
            });
        },
    };

    function already()
    {
        layer.msg('简历数量已超过设置数量，不能创建简历');
    }
</script>