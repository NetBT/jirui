<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-7"><b>消息列表</b>List of notice</div>
        <div class="header-search col-xs-4 pull-right">
            <div class="col-xs-12 pull-right text-r">
                <button class="btn btn-hot " id="doSearch">刷新</button>
                <button type="button" onclick="addNotice()" class="btn btn-orange ">发布新公告</button>
                <button class="btn btn-success" id="doRefresh">刷 新</button>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="main-body col-xs-12">
        <table class="tab" width="100%" border="0" cellspacing="0" cellpadding="0" id="noticeList"></table>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function()
    {
        DataTable.init('initNoticeList', '#noticeList', '<?= \yii\helpers\Url::to(['notice/list']);?>', getParam());

        //点击事件
        $("#doSearch").bind('click',function(){
            DataTable.reloadTable();
        });

        //刷新事件
        $("#doRefresh").bind('click',function(){
            DataTable.id = '#noticeList';
            DataTable.drawTable();
        });
    });

    function getParam() {
        return {};
    }

    function fun() {
        console.log('我是列表页');
    }
    /**
     * 切换公告状态
     * */
    function toggleStatus(id, status)
    {
        var msg = '';
        if (status == '<?= \common\models\Status::NOTICE_STATUS_RELEASING?>') {
            msg = '是否停止发布？'
        }
        if (status == '<?= \common\models\Status::NOTICE_STATUS_NOT_RELEASE?>') {
            msg = '是否发布？';
        }
        if (status == '<?= \common\models\Status::NOTICE_STATUS_DELETE ?>') {
            layer.msg('该公告已删除，不能执行此操作', {icon: 2});
            return false;
        }
        layer.confirm(msg, {icon: 0, title: '提示'}, function (index) {
            ajaxSubmit('<?= \yii\helpers\Url::to(['notice/toggle-status'])?>', {id: id}, function () {
                DataTable.reloadTable();
            });
            layer_close(index);
        });
    }

    function addNotice() {
        layer_show_full('添加公告', '<?= \yii\helpers\Url::to(['notice/add'])?>');
    }
    /**
     * 编辑公告
     */
    function editNotice(id)
    {
        layer_show_full('编辑公告', '<?= \yii\helpers\Url::to(['notice/edit'])?>'+ '?id=' + id);
    }

    /**
     * 删除公告
    * */
    function deleteNotice(id) {
        layer.confirm('是否删除？', {icon: 0, title: '提示'}, function (index) {
            ajaxSubmit('<?= \yii\helpers\Url::to(['notice/do-delete'])?>', {id: id}, function () {
                DataTable.reloadTable();
            });
            layer_close(index);
        });
    }

</script>
