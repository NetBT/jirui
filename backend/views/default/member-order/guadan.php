<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-12"><b>会员订单列表</b>List of Member Order</div>
        <div class="header-search col-xs-12 clear-padding" >

            <input type="hidden" id="orderNumber" value="">
            <div class="col-xs-1" style="margin-left:25px">
                    <div class="vip_box_input">
                        <a class="box" style="width: 100px">筛选条件</a>
                    </div>
            </div>

            <div class="col-xs-2 input-area">
                <label class="col-xs-4 text-right">会员</label>
                <div class="col-xs-8">
                    <input type="text" style="width: 130px" class="form-control" id="searchMemberName" placeholder="请输入会员姓名">
                </div>
            </div>

            <div class="col-xs-2 input-area">
                <label class="col-xs-4 text-right">编号</label>
                <div class="col-xs-8">
                    <input type="text" style="width: 130px" value="" class="form-control" id="searchOrderNum" placeholder="请输入订单编号">
                </div>
            </div>

            <!--            <div class="col-xs-3 input-area">-->
            <!--                <label class="col-xs-4 text-right">订单类型</label>-->
            <!--                <div class="col-xs-8">-->
            <!--                    <select class="selectpicker bs-select-hidden" id="searchOrderType" aria-required="true">-->
            <!--                    </select>-->
            <!--                </div>-->
            <!--            </div>-->

            <div class="col-xs-4 input-area">
                <label class="col-xs-3 text-right">日期</label>
                <div class="col-xs-4">
                    <input type="text" style="width: 100px" onclick="WdatePicker({readOnly: true, dateFmt: 'yyyy-MM-dd',maxDate:'#F{$dp.$D(\'endTime\',{d:-1});}'});" class="form-control" id="startTime" placeholder="开始日期">
                </div>
                <label class="col-xs-1 clear-padding text-center">至</label>
                <div class="col-xs-4">
                    <input type="text" style="width: 100px;" onclick="WdatePicker({readOnly: true, dateFmt: 'yyyy-MM-dd', minDate: '#F{$dp.$D(\'startTime\', {d:1});}'});" class="form-control" id="endTime" placeholder="结束日期">
                </div>
            </div>


            <div class="col-xs-3 pull-right text-center" style="margin-right:-25px;">
                <button class="btn btn-success " id="doRefresh">刷新</button>
                <button class="btn btn-hot " id="doSearch">查 询</button>
              <!--  <button class="btn btn-yellow" onclick="memberOrder.addModal();">新 建</button>-->
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="main-body col-xs-12">
        <table class="tab" width="100%" border="0" cellspacing="0" cellpadding="0" id="memberOrderGuaList"></table>
    </div>
</div>

<style>



</style>

<script>
    $(function(){

        DataTable.init('initMemberOrderGuaList','#memberOrderGuaList','<?= \yii\helpers\Url::to(['member-order/guadan-list'])?>',getParams());

        //点击事件
        $("#doSearch").bind('click',function(){
            DataTable.reloadTable(getParams());
        });
        //刷新
        $("#doRefresh").bind('click',function(){
            DataTable.id = '#memberOrderGuaList';
            DataTable.drawTable();
        });

        $("#searchOrderType").selectpicker({
            title: '选择订单状态',
            style: 'btn-default',
            width: '120',
            liveSearch: true
        });

    });
    /*
     * 搜集搜索条件
     */
    function getParams () {
        return {
            memberName : $('#searchMemberName').val(),
            orderNumber : $('#searchOrderNum').val(),
            orderType : $('#searchOrderType').val(),
            startTime : $('#startTime').val(),
            endTime : $('#endTime').val(),
            orderstate : '2',
        }
    }

    var memberOrder = {
        deleteUrl : '<?= \yii\helpers\Url::to(['member-order/delete']) ?>',
        addUrl : '<?= \yii\helpers\Url::to(['member-order/add']) ?>',
        editUrl : '<?= \yii\helpers\Url::to(['member-order/edit']) ?>',
        refundUrl : '<?= \yii\helpers\Url::to(['member-order/refund']) ?>',
        secondUrl : '<?= \yii\helpers\Url::to(['member-order/second']) ?>',
        planComboUrl : '<?= \yii\helpers\Url::to(['member-order/index-order-combo']) ?>',
        planUrl : '<?= \yii\helpers\Url::to(['calendar-plan/list'])?>',
        showOrderComboUrl : '<?= \yii\helpers\Url::to(['member-order/show-order-combo'])?>',
        editShopUrl : '<?= \yii\helpers\Url::to(['member-order/edit-shop'])?>',
        addShopModal : '<?= \yii\helpers\Url::to(['member-order/edit-shop'])?>',
        deleteModal : function(id, order_number){
            var _this = this;
            layer.confirm('确认要删除该条订单吗？',function(index){
                ajaxSubmit(_this.deleteUrl, {id: id,order_number: order_number}, function () {
                    DataTable.id = '#memberOrderGuaList';
                    DataTable.drawTable();
                });
            });
        },
        addModal : function()
        {
//            layer_show({}, '添加订单', this.addUrl,1100,500);
//            layer_show_full('添加订单', this.addUrl);
            creatIframe(this.addUrl,'添加订单');
        },

        editModal : function(id)
        {
            var param = {
                id : id,
            };
//            layer_show(param, '查看订单', this.editUrl,780,500);

            creatIframe(this.editUrl+'?id='+id+'&orderstate=2','订单详情');
//            layer_show_full('订单详情', this.editUrl+'?id='+id);
        },

        refundModal : function(id)
        {
            var param = {
                id : id,
            };
            layer_show(param, '退款', this.refundUrl,780,500);
        },

        secondModal : function(id)
        {
            var param = {
                id : id,
            };
            layer_show(param, '二销售款', this.secondUrl,780,500);
        },

        planComboModal : function(orderNumber)
        {
            var param = {
                orderNumber : orderNumber,
            };
            $("#orderNumber").val(orderNumber);
            layer_show(param, '订单排项', this.planComboUrl,1000);
        },

        planModal : function(orderNumber)
        {
            parent.creatIframe(this.planUrl + "?orderNumber=" + orderNumber, '月视图');
//            layer_show_full('添加排项', this.planUrl + "?orderNumber=" + orderNumber);
        },

        showOrderComboModal : function(comboOrderNumber)
        {
            creatIframe(this.showOrderComboUrl+'?comboOrderNumber='+comboOrderNumber,'订单详情');
        },
        editShopModal : function(comboOrderNumber)
        {
            creatIframe(this.editShopUrl+'?comboOrderNumber='+comboOrderNumber,'编辑商品');
        },
        addShopModal : function(goods_code)
        {
            layer_show_full('编辑会员', this.editUrl);
        },

    };

    function addRow(obj,orderNum) {
        var table = $('#memberOrderGuaList').DataTable();
        var $obj = $(obj);
        var tr = $obj.closest('tr');
        var row = table.row(tr);

        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.next('tr').find('td').toggleClass('td-border-top');
//            tr.removeClass('shown');
        }
        else {
            // Open this row
            $.ajax({
                url : '<?= \yii\helpers\Url::to(['calendar-plan/get-combo-order'])?>',
                type : 'POST',
                async: false,
                data : {orderNum : orderNum},
                dataType : 'JSON',
                success: function(data)
                {
                    if(data.code == 1000) {
                        var combo = data.data;
                        var childTable = '<table class="show-detail-table" cellspacing="0" border="0">';
                        if(combo) {
                            $.each(combo,function(k,v) {
                                childTable += '<tr>';
                                //childTable += '<td>宝宝姓名：<a href="javascript:;" onclick="memberOrder.showOrderComboModal(\''+v.combo_order_number+'\')">'+v.member_name+'</a></td>';
                                //childTable += '<td>订单编号：<a href="javascript:;" onclick="memberOrder.showOrderComboModal(\''+v.combo_order_number+'\')">'+v.combo_order_number+'</a></td>';
                                //childTable += '<td>套系名称：<a href="javascript:;" onclick="memberOrder.showOrderComboModal(\''+v.combo_order_number+'\')">'+v.combo_name+'</a></td>';
                                //childTable += '<td>套系价格：<a href="javascript:;" onclick="memberOrder.showOrderComboModal(\''+v.combo_order_number+'\')">'+v.price+'</a></td>';
                                //childTable += '<td>操作：<a style="text-decoration:none" onClick="memberOrder.planModal(\'' + v.combo_order_number + '\')" href="javascript:;" title="日历排项"><i class="fa fa fa-wpforms"></i></a></td>';

                                childTable += '<td>套系名称：<a href="javascript:;" onclick="memberOrder.showOrderComboModal(\''+v.combo_order_number+'\')">'+v.combo_name+'</a></td>';
                                childTable += '<td>入册张数：<a href="javascript:;" onclick="memberOrder.showOrderComboModal(\''+v.combo_order_number+'\')">'+v.register_count+'</a></td>';
                                childTable += '<td>服装造型：<a href="javascript:;" onclick="memberOrder.showOrderComboModal(\''+v.combo_order_number+'\')">'+v.combo_clothing+'</a></td>';
                                childTable += '<td>操作：<a style="text-decoration:none" onClick="memberOrder.planModal(\'' + v.combo_order_number + '\')" href="javascript:;" title="日历排项"><i class="fa fa fa-wpforms"></i></a>';
                                childTable += '<a style="text-decoration:none;margin-left: 20px" onClick="memberOrder.editShopModal(\'' + v.combo_order_number + '\')" href="javascript:;" title="编辑">编辑</a>';
                                childTable += '<a style="text-decoration:none;margin-left: 20px" onclick="deleteCalender(\''+v.combo_order_number+'\')" href="javascript:;" title="删除">删除</a>';
                                childTable += '</td></tr>';
                            });
                        }
                        childTable += '</table>';
                        row.child( childTable ).show();
                        row.child().find('td').eq(0).css({'border-top':'2px solid #ddd','padding-top':'5px','padding-bottom':'5px', 'padding-left':'20px'});
                        row.child().find('td').eq(0).attr('colspan','12');
                        tr.nextAll('tr').eq(1).find('td').toggleClass('td-border-top');
                        //            tr.addClass('shown');
                    } else {
                        layer.msg(data.message,{icon:6,time:2000});
                    }
                },
                error: function()
                {
                    layer.msg('网络错误,请联系管理员',{icon:5,time:2000});
                }
            });
        }
    }

    function editModal(id)
    {

        var title = "";
        layer_show({id: id}, '修改排项：' + title, '<?= \yii\helpers\Url::to(['calendar-plan/edit'])?>', 800);
        editEvent = event;
    }

    //删除
    function deleteCalender(combo_order_number) {
        layer.confirm('确认要删除该条订单吗？',function(index){
            $.ajax({
                url: '<?= \yii\helpers\Url::to(['member-order/com-delete'])?>',
                type: 'POST',
                async: false,
                data: {combo_order_number: combo_order_number},
                dataType: 'JSON',
                success: function (data) {
                    layer.msg(data.message, {icon: 6, time: 2000});
                },
                error: function () {
                    layer.msg('网络错误', {icon: 5, time: 2000});
                }
            });
        });
    }


</script>

