<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-12"><b>普通套系</b>List of General Combo</div>
        <div class="header-search col-xs-12">
            <div class="col-xs-1" style="margin-left:25px">
                <div class="vip_box_input">
                    <a class="box" style="width: 100px">筛选条件</a>
                </div>
            </div>
            <div class="col-xs-4 input-area">
                <label class="col-xs-3 text-right">套系名称</label>
                <div class="col-xs-9">
                    <input type="text" value="" class="form-control" id="searchName" placeholder="请输入套系名称">
                </div>
            </div>

            <div class="col-xs-3 pull-right text-r">
                <button class="btn btn-hot " id="doSearch">查 询</button>
                <button class="btn btn-yellow" onclick="comboGeneral.addModal();">新 建</button>
                <button class="btn btn-success" id="doRefresh">刷新</button>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="main-body col-xs-12">
        <table class="tab" width="100%" border="0" cellspacing="0" cellpadding="0" id="generalComboList"></table>
    </div>
</div>
<script>
    $(function(){

        DataTable.init('initComboGeneralList','#generalComboList','<?= \yii\helpers\Url::to(['combo/list','type' => \common\models\Status::COMBO_TYPE_GENERAL])?>',getParams());

        //点击事件
        $("#doSearch").bind('click',function(){
            DataTable.reloadTable(getParams());
        });

        //刷新事件
        $("#doRefresh").bind('click',function(){
            DataTable.id = '#generalComboList';
            DataTable.drawTable();
        });
    });
    /*
     * 搜集搜索条件
     */
    function getParams () {
        return {
            name : $('#searchName').val(),
        }
    }
    var comboGeneral = {
        addUrl : '<?= \yii\helpers\Url::to(['combo/general-add-edit']) ?>',
        editUrl : '<?= \yii\helpers\Url::to(['combo/general-add-edit']) ?>',
        deleteUrl : '<?= \yii\helpers\Url::to(['combo/delete']) ?>',
        addModal : function()
        {
            layer_show({}, '添加套系', this.addUrl,780,500,'LAYER_general_combo');
        },

        editModal : function(id)
        {
            var param = {
                id : id,
            };
            layer_show(param, '编辑套系', this.editUrl,780,500);
        },

        deleteModal : function(id){
            var _this = this;
            layer.confirm('确认要删除吗？',function(index){
                ajaxSubmit(_this.deleteUrl, {id: id}, function () {
                    DataTable.drawTable();
                });
            });
        },

        showContent : function (content)
        {
            layer_show_content('商品列表',content);
        }
    };
</script>