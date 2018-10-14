<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-12"><b>会员列表</b>List of member</div>
        <div class="header-search col-xs-12">

            <div class="col-xs-1" style="margin-left:25px">
                <div class="vip_box_input">
                    <a class="box" style="width: 100px">筛选条件</a>
                </div>
            </div>

            <div class="col-xs-3 input-area">
                <label class="col-xs-3 text-right">姓名</label>
                <div class="col-xs-9">
                    <input type="text" value="" class="form-control" id="searchName" placeholder="请输入姓名">
                </div>
            </div>

            <div class="col-xs-3 input-area">
                <label class="col-xs-3 text-right">手机号</label>
                <div class="col-xs-9">
                    <input type="text" value="" class="form-control" id="searchTel" placeholder="请输入手机号">
                </div>
            </div>

            <div class="col-xs-3 pull-right text-r">
                <button class="btn btn-hot " id="doSearch">查 询</button>
                <button class="btn btn-yellow" onclick="member.addModal();">新 建</button>
                <button class="btn btn-success" id="doRefresh">刷 新</button>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="main-body col-xs-12">
        <table class="tab" width="100%" border="0" cellspacing="0" cellpadding="0" id="memberList"></table>
    </div>
</div>
<script>
    $(function(){

        DataTable.init('initMemberList','#memberList','<?= \yii\helpers\Url::to(['member/list'])?>',getParams());

        //点击事件
        $("#doSearch").bind('click',function(){
            DataTable.reloadTable(getParams());
        });

        //刷新
        $("#doRefresh").bind('click',function(){
            DataTable.id = '#memberList';
            DataTable.drawTable();
        });
    });
    /*
     * 搜集搜索条件
     */
    function getParams () {
        return {
            name : $('#searchName').val(),
            tel : $('#searchTel').val(),
        }
    }

    var member = {
        addUrl : '<?= \yii\helpers\Url::to(['member/add']) ?>',
        editUrl : '<?= \yii\helpers\Url::to(['member/edit']) ?>',
        deleteUrl : '<?= \yii\helpers\Url::to(['member/delete']) ?>',
        rechargeUrl : '<?= \yii\helpers\Url::to(['member/recharge']) ?>',
        referrerUrl : '<?= \yii\helpers\Url::to(['member/referrer']) ?>',
        integralUrl : '<?= \yii\helpers\Url::to(['member/integral']) ?>',
        addOrderUrl : '<?= \yii\helpers\Url::to(['member-order/add']) ?>',
        addModal : function()
        {
//            layer_show({}, '添加会员', this.addUrl,860,500);
            layer_show_full('添加会员', this.addUrl);
        },

        editModal : function(id)
        {
            var param = {
                id : id,
            };
//            layer_show(param, '编辑会员', this.editUrl,780,500);
            layer_show_full('编辑会员', this.editUrl+'?id='+id);
        },

        deleteModal : function(id){
            var _this = this;
            layer.confirm('确认要删除吗？',function(index){
                ajaxSubmit(_this.deleteUrl, {id: id}, function () {
                    DataTable.drawTable();
                });
            });
        },

        rechargeModal : function(id){
            layer_show({id: id}, '会员充值', this.rechargeUrl,580);
        },

        referrerModal : function(id){
            layer_show({id: id}, '推荐信息', this.referrerUrl,900);
        },

        integralModal : function(id){
            layer_show({id: id}, '积分操作', this.integralUrl,580);
        },

        addOrderModal : function(id) {
            creatIframe(this.addOrderUrl+'?memberId='+id,'添加订单');
        }
    };
</script>