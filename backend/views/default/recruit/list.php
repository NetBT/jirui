<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-7"><b>招聘套餐列表</b>Combo Recruit List</div>
        <div class="header-search col-xs-4 pull-right">
            <div class="col-xs-12 pull-right text-r">
                <button class="btn btn-hot " id="doSearch">刷新</button>
                <button type="button" onclick="addComboRecruit()" class="btn btn-orange ">添加新套餐</button>
                <button class="btn btn-success" id="doRefresh">刷 新</button>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="main-body col-xs-12">
        <table class="tab" width="100%" border="0" cellspacing="0" cellpadding="0" id="list"></table>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function()
    {
        DataTable.init('initRecruitComboList', '#list', '<?= \yii\helpers\Url::to(['recruit/combo-list-data']);?>', getParam());

        //点击事件
        $("#doSearch").bind('click',function(){
            DataTable.reloadTable();
        });

        //刷新事件
        $("#doRefresh").bind('click',function(){
            DataTable.id = '#list';
            DataTable.drawTable();
        });
    });

    function getParam() {
        return {};
    }
    function addComboRecruit() {
        layer_show({}, '添加招聘套餐', '<?= \yii\helpers\Url::to(['recruit/add'])?>');
    }
    /**
     * 编辑公告
     */
    function editRecruitCombo(id)
    {
        layer_show({id: id}, '编辑招聘套餐', '<?= \yii\helpers\Url::to(['recruit/edit'])?>');
    }
</script>
