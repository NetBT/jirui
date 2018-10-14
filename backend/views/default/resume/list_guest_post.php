<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-3"><b>职位列表</b>List Of Post</div>
        <div class="header-search col-xs-8">
            <div class="col-xs-3 input-area">
                <label class="col-xs-6 text-right">投递状态</label>
                <div class="col-xs-6">
                    <select class="selectpicker bs-select-hidden" id="searchSendStatus" data-title="投递状态">
                        <?php foreach (\common\models\Status::resumeSendMap() as $key => $value) : ?>
                            <option value="<?= $key?>"><?= $value?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>
            <div class="col-xs-3 pull-right text-r">
                <button class="btn btn-yellow" id="doSearch">查询</button>
                <button class="btn btn-success" id="refresh">刷 新</button>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="main-body col-xs-12">
        <table class="tab" width="100%" border="0" cellspacing="0" cellpadding="0" id="guestPostList"></table>
    </div>
</div>
<script>
    $(function(){
        DataTable.init('initGuestPostList','#guestPostList','<?= \yii\helpers\Url::to(['resume/list-guest-post'])?>',getParams());
        //刷新
        $("#refresh").bind('click',function(){
            DataTable.drawTable();
        });
        //点击事件
        $("#doSearch").bind('click',function(){
            DataTable.reloadTable(getParams());
        });

        $("#searchSendStatus").selectpicker({
            style: 'btn-default',
            width: '100px',
        });
    });

    function getParams() {
        return {
            sendStatus :  $("#searchSendStatus").selectpicker('val')
        }
    }
    var guestPostList = {
        applyUrl : '<?= \yii\helpers\Url::to(['resume/apply'])?>',
        showUrl : '<?= \yii\helpers\Url::to(['resume/show-recruit-post'])?>',

        showModal  : function(id)
        {
            layer_show({id : id},'职位详情', this.showUrl,700,450);
        },

        applyModal  : function(obj,id)
        {
            var _this = this;
            var $obj = $(obj);
            layer.confirm('确定使用您的默认简历投递该职位？',function(index){
                ajaxSubmit(_this.applyUrl, {id: id}, function () {
                    $obj.attr('title','已投递');
                    $obj.attr('onclick','already()');
                    $obj.children().toggleClass('fa fa-heart-o');
                    $obj.children().toggleClass('fa fa-heart');
                });
            });
        },
    };

    function already()
    {
        layer.msg('已投递，请选择其他职位',{icon:5,time:2000});
    }
</script>