<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-12"><b>消息列表</b>List of message</div>
        <div class="header-search col-xs-12">
            <div class="col-xs-3 input-area">
                <label class="col-xs-5 text-right">类型：</label>
                <div class="col-xs-7">
                    <select class="form-control selectpicker" data-title="请选择类型" id="type">
                        <option value="">全部</option>
                        <?php foreach (\common\models\Status::messageTypeCommonMap() as $k => $v) : ?>
                        <option value="<?= $k?>"><?= $v?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>

            <div class="col-xs-3 input-area">
                <label class="col-xs-5 text-right">状态：</label>
                <div class="col-xs-7">
                    <select class="form-control selectpicker" data-title="请选择状态" id="status">
                        <option value="">全部</option>
                        <?php foreach (\common\models\Status::messageStatusMap() as $k => $v) : ?>
                            <option value="<?= $k?>"><?= $v?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>
            <div class="col-xs-3 pull-right text-r">
                <button class="btn btn-hot " id="doSearch">查 询</button>
                <button class="btn btn-warning size-M" href="javascript: void(0)" onclick="sendMessage()">发信息</button>
                <button class="btn btn-success" id="doRefresh">刷 新</button>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="main-body col-xs-12">
        <table class="tab" width="100%" border="0" cellspacing="0" cellpadding="0" id="messageList"></table>
    </div>
</div>
<script>
    $(function(){

        DataTable.init('initABMessageList','#messageList','<?= \yii\helpers\Url::to(['message/ab-list'])?>',getParams());

        //点击事件
        $("#doSearch").bind('click',function(){
            DataTable.reloadTable(getParams());
        });

        //刷新事件
        $("#doRefresh").bind('click',function(){
            DataTable.id = '#messageList';
            DataTable.drawTable();
        });

        $('.selectpicker').selectpicker({
            style: 'btn-default',
            width : '110px'
        });
    });
    /*
     * 搜集搜索条件
     */
    function getParams () {
        return {
            status : $('#status').selectpicker('val'),
            type : $('#type').selectpicker('val')
        }
    }

    function sendMessage() {
        layer_show({}, '发送消息', '<?= \yii\helpers\Url::to(['message/send'])?>',580,400);
    }
</script>