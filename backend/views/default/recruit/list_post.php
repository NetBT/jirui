<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-3"><b>职位列表</b>List Of Post</div>
        <input type="hidden" value="" id="hiddenRecruitPostId">
        <div class="header-search col-xs-3 text-r">
            <button class="btn btn-yellow" onclick="recruitPostList.addModal()">新 建</button>
            <button class="btn btn-success" id="doRefresh">刷 新</button>
        </div>
        <div class="clear"></div>
    </div>
    <div class="main-body col-xs-12">
        <table class="tab" width="100%" border="0" cellspacing="0" cellpadding="0" id="recruitPostList"></table>
    </div>
</div>
<script>
    $(function(){
        DataTable.init('initRecruitPostList','#recruitPostList','<?= \yii\helpers\Url::to(['recruit/list-post'])?>',{});
        //刷新事件
        $("#doRefresh").bind('click',function(){
            DataTable.id = '#recruitPostList';
            DataTable.drawTable();
        });
    });

    var recruitPostList = {
        endUrl : '<?= \yii\helpers\Url::to(['recruit/end-recruit-post'])?>',
        editUrl : '<?= \yii\helpers\Url::to(['recruit/add-edit-post'])?>',
        addUrl : '<?= \yii\helpers\Url::to(['recruit/add-edit-post'])?>',
        scanResumeUrl : '<?= \yii\helpers\Url::to(['recruit/resume-for-recruit-post'])?>',
        endModal : function(id){
            var _this = this;
            layer.confirm('确认要结束招聘吗？',function(index){
                ajaxSubmit(_this.endUrl, {id: id}, function () {
                    DataTable.drawTable();
                });
            });
        },

        addModal  : function()
        {
            layer_show({},'添加招聘职位', this.addUrl,700);
        },

        editModal : function(id)
        {
            layer_show({id : id},'编辑招聘职位', this.editUrl,700);
        },

        scanResumeModal : function(id)
        {
            if(!id) {
                layer.msg('请选择职位',{icon:5,time:2000});
            }
            $('#hiddenRecruitPostId').val(id);
            layer_show({id : id},'简历列表', this.scanResumeUrl,1000);
        },
    };
</script>