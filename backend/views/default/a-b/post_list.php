<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-3"><b>加盟商权限列表</b>Franchisees Permissions List</div>
        <div class="header-search col-xs-8">
            <div class="col-xs-3 pull-right text-r">
                <button class="btn btn-yellow" onclick="abPostList.postAddModel();">新加权限</button>
                <button class="btn btn-success" id="doRefresh">刷 新</button>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="main-body col-xs-12">
        <table class="tab" width="100%" border="0" cellspacing="0" cellpadding="0" id="abPostList"></table>
    </div>
</div>
<script>
    $(function(){
        DataTable.init('initAbPostList','#abPostList','<?= \yii\helpers\Url::to(['a-b/list-post'])?>',{});

        //刷新事件
        $("#doRefresh").bind('click',function(){
            DataTable.id = '#abPostList';
            DataTable.drawTable();
        });
    });

    var abPostList = {
        postUpdateStatusUrl : '<?= \yii\helpers\Url::to(['a-b/post-update-status'])?>',
        postAddEditUrl : '<?= \yii\helpers\Url::to(['a-b/post-add-edit'])?>',
        postStopOrStart : function(obj,id){
            var statusVal = $(obj).attr('title');
            var reStatusVal = '';
            switch(statusVal)
            {
                case '启用':
                    reStatusVal = '禁用';
                    break;
                case '禁用':
                    reStatusVal = '启用';
                    break;
            }

            var _this = this;
            layer.confirm('确认要'+statusVal+'吗？',function(index){
                Common.tabStatus(id, obj, _this.postUpdateStatusUrl, reStatusVal, statusVal);
            });
        },

        postAddModel : function()
        {
            layer_show({},'添加加盟商权限', this.postAddEditUrl, 550, 550);
        },

        postEditModel : function(id)
        {
            var param = {
                id : id,
            };
            layer_show(param, '修改加盟商权限', this.postAddEditUrl, 550, 550);
        },

        showPostPermissions : function (content)
        {
            layer_show_content('具体权限',content);
        }
    };
</script>