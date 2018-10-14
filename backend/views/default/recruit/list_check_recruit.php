<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-4"><b>招聘职位列表</b>List of Recruit</div>
        <div class="header-search col-xs-7">
            <div class="col-xs-7 input-area">
                <label class="col-xs-3 text-right">状态</label>
                <div class="col-xs-3">
                    <select class="selectpicker" data-width="100" id="searchCheckStatus" aria-required="true">
                        <option value="0">全部</option>
                        <?php foreach (\common\models\Status::recruitCheckStatusMap() as $k => $v) :?>
                            <option value="<?= $k?>"><?= $v?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>

            <div class="col-xs-3 pull-right text-r">
                <button class="btn btn-hot " id="doSearch">查 询</button>
                <button class="btn btn-success " id="doRefresh">刷 新</button>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="main-body col-xs-12">
        <table class="tab" width="100%" border="0" cellspacing="0" cellpadding="0" id="recruitCheckList"></table>
    </div>
</div>
    <script>
        $(function(){
            $("#searchCheckStatus").selectpicker({
                title: '选择状态',
                style: 'btn-default',
                width: '100px',
            });

            DataTable.init('initRecruitPostCheckList','#recruitCheckList','<?= \yii\helpers\Url::to(['recruit/list-post'])?>',{});
            //刷新
            $("#doRefresh").bind('click',function(){
                DataTable.id = '#recruitCheckList';
                DataTable.drawTable();
            });

            //点击事件
            $("#doSearch").bind('click',function(){
                DataTable.reloadTable(getParams());
            });

        });

        function getParams() {
            return {
                checkStatus : $("#searchCheckStatus").selectpicker('val')
            };
        }
        
        var recruitCheck = {
            checkRecruitUrl : '<?= \yii\helpers\Url::to(['recruit/check-recruit'])?>',
            showRecruitUrl : '<?= \yii\helpers\Url::to(['recruit/show-check-recruit-post'])?>',

            showRecruitModal  : function(id)
            {
                layer_show({id : id},'简历详情', this.showRecruitUrl,700,450);
            },

            checkRecruitModel  : function(obj,id,afterVal)
            {
                var _this = this;
                var $obj = $(obj);
                var operateName = $obj.attr('title');
                layer.confirm('是否【'+operateName+'】该招聘？',function(index){
                    ajaxSubmit(_this.checkRecruitUrl, {id: id, afterVal: afterVal}, function () {
                        layer.closeAll('page');
                        DataTable.id = '#recruitCheckList';
                        DataTable.drawTable();
                    });
                });
            },
        };


    </script>