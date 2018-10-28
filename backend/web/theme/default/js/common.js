$.extend({
    initTable: function(domObject, options){
        var _op = $.extend({
            "serverSide": true,//开启服务器模式
            "processing":true,//当datatable获取数据时候是否显示正在处理提示信息。
            "retrieve": true,//当使用了retrieve属性后，在没有destroy之前，都返回同一个实例，即第一次创建的实例
            "ajax":{
                url : "",
                type : "POST",
                data: '',
            },
            "bJQueryUI": true,//使用JqueryUi
            "sPaginationType": "full_numbers",//分页样式
            "columns":[],
            "pageLength" : 50,
            "sDom":'<"toolbar"rtlip<"clear">>',// 自定义布局  此例为隐藏顶部搜索框
            // "sDom":'<"toolbar"prtli<"clear">>',// 自定义布局  此例为隐藏顶部搜索框
            "oLanguage": {//国际语言转化
                "sLengthMenu": "显示 _MENU_ 记录",
                "sZeroRecords": "对不起，查询不到任何相关数据",
                "sEmptyTable": "未有相关数据",
                "sLoadingRecords": "正在加载数据-请等待...",
                "sProcessing": '<span>正在加载数据-请等待...</span>',
                "sInfo": "当前显示 _START_ 到 _END_ 条，共 _TOTAL_ 条记录。",
                "sInfoEmpty": "当前显示0到0条，共0条记录",
                "sInfoFiltered": "（数据库中共为 _MAX_ 条记录）",
                "sSearch": "模糊查询：",
                "sUrl": "",
                //多语言配置文件
                "oPaginate": {
                    "sFirst": "首页",
                    "sPrevious": " 上一页 ",
                    "sNext": " 下一页 ",
                    "sLast": " 尾页 ",
                }
            },
            //此加载方法对翻页没用
            "initComplete" : '',
            //数据加载完执行的方法，对翻页有用
            "drawCallback": '',
            "scrollX" : false,//不显示水平滚动条
            "sScrollX" : "100%",
            "sScrollXInner": "100%",
            "scrollY" : false,//显示竖直滚动条

        }, options);
        $(domObject).dataTable(_op);
    }
});

var DataTable = {
    sDom : '<"toolbar"rtlip<"clear">>',
    scrollY : false,
    columns : [],
    id : '',
    ajax: {
        url: '',
        type: 'POST',
        data: ''
    },
    drawCallback : '',

    //员工列表
    employeeList : {
        columns:[
            {"data": 'id',"sTitle":'ID','orderable':false},
            {"data": 'alliance_business_id',"sTitle":'加盟商ID','orderable':false},
            {"data": 'post_id',"sTitle":'职位','orderable':false},
            {"data": 'login_name',"sTitle":'账号','orderable':false},
            {"data": 'employee_name',"sTitle":'昵称','orderable':false},
            {"data": 'tel',"sTitle":'电话','orderable':false},
            {"data": 'create_time',"sTitle":'创建时间','orderable':false},
            {"data": 'sex',"sTitle":'性别','orderable':false},
            {"data": 'age',"sTitle":'年龄','orderable':false},
            {"data": 'status',"sTitle":'状态','sClass':'td-status','orderable':false,'render':function(Data, type, row, meta){
                return '<span class="label radius">'+Data+'</span>';
            }},
            {
                "sTitle":'操作',
                "mDataProp": "id",
                'sClass':'td-manage td-icon',
                'orderable':false,
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {//参数：DOM, id, object对象, 当前所在行,当前所在列
                    $(nTd).addClass('center').html(
                        '<a style="text-decoration:none" class="status" onClick="employeeList.employeeStopOrStart(this,'+ oData.id + ')" href="javascript:;" title="'+oData.status+'"><i class="Hui-iconfont"></i></a>'+
                        '<a style="text-decoration:none" onClick="employeeList.employeeUpdatePsdModal('+ oData.id + ')" href="javascript:;" title="修改密码"><i class="Hui-iconfont">&#xe63f;</i></a>'+
                        '<a style="text-decoration:none" onClick="employeeList.employeeEditModal('+ oData.id + ')" href="javascript:;" title="编辑"><i class="fa fa-pencil"></i></a>'+
                        '<a style="text-decoration:none" onClick="employeeList.workingStatusModal('+ oData.id + ')" href="javascript:;" title="离职"><i class="fa fa-remove"></i></a>'
                    );//oData.id,获取json对象里的id
                }
            },
        ],
    },

    noticeList:{
        columns:[
            {"data": 'id',"sTitle":'编号','orderable':false},
            {"data": 'title',"sTitle":'标题','orderable':false},
            {"data": 'create_time',"sTitle":'发布时间', 'orderable':false},
            {"data": 'status_name',"sTitle":'状态','orderable':false},
            {
                "sTitle":'操作',
                "mDataProp": "id",
                'sClass':'td-manage td-icon',
                'orderable':false,
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {//参数：DOM, id, object对象, 当前所在行,当前所在列
                    var html = '<a href="javascript:void(0);" class="status" title="编辑" onclick="editNotice('+ oData.id + ')"><i class="fa fa-pencil"></i></a>'+
                        '<a href="javascript:void(0);" class="status" title="删除" onclick="deleteNotice('+ oData.id + ')"><i class="fa fa-remove"></i></a>';
                    if (oData.status == 1) {
                        html += '<a href="javascript:void(0);" class="status" title="发布" onclick="toggleStatus('+ oData.id + ',\''+oData.status+'\')"><i class="fa fa-arrow-up"></i></a>';
                    }
                    if (oData.status == 2) {
                        html += '<a href="javascript:void(0);" class="status" title="停止发布" onclick="toggleStatus('+ oData.id + ',\''+oData.status+'\')"><i class="fa fa-arrow-down"></i></a>';
                    }
                    $(nTd).addClass('center').html(html);//oData.id,获取json对象里的id
                }
            },
        ],
    },
    //员工职位列表 - 总部
    employeePostList:{
        columns:[
            {"data": 'id',"sTitle":'ID','orderable':false,'sClass':'text-c', 'width' : '5%'},
            {"data": 'post_name',"sTitle":'职位名称','orderable':false, 'sClass':'text-c', 'width' : '20%'},
            {"data": 'short_module_content',"sTitle":'职位内容', 'sClass':'text-c', 'width':'55%','orderable':false,'render':function(Data, type, row, meta) {
                var sData = Data;
                if(Data.indexOf('...') > 0)
                {
                    sData = '<td>'+Data+'</td><button class="btn btn-secondary radius size-MINI" onclick="employeePostList.showPostPermissions(\''+row.module_content+'\')">详情</button>';
                }
                return   sData;
            }},
            {"data": 'status',"sTitle":'状态','sClass':'td-status','width' : '5%','orderable':false,'render':function(Data, type, row, meta){
                return '<span class="label radius">'+Data+'</span>';
            }},
            {
                "sTitle":'操作',
                "mDataProp": "id",
                'orderable':false,
                'sClass':'td-manage td-icon text-c',
                'width' : '10%',
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {//参数：DOM, id, object对象, 当前所在行,当前所在列
                    $(nTd).addClass('center').html(
                        '<a style="text-decoration:none" class="status" onClick="employeePostList.postStopOrStart(this,'+ oData.id + ')" href="javascript:;" title="'+oData.status+'"><i class="fa fa-minus-circle"></i></a>'+
                        '<a style="text-decoration:none" onClick="employeePostList.postEditModel('+ oData.id + ')" href="javascript:;" title="编辑"><i class="fa fa-pencil"></i></a>'
                    );//oData.id,获取json对象里的id
                }
            },
        ],
    },

    //员工职位列表 - 加盟商
    abEmployeePostList:{
        columns:[
            {"data": 'id',"sTitle":'ID','orderable':false,'sClass':'text-c', 'width' : '5%'},
            {"data": 'post_name',"sTitle":'职位名称','orderable':false, 'sClass':'text-c', 'width' : '20%'},
            {"data": 'short_module_content',"sTitle":'职位内容', 'sClass':'text-c', 'width':'55%','orderable':false,'render':function(Data, type, row, meta) {
                var sData = Data;
                if(Data.indexOf('...') > 0)
                {
                    sData = '<td>'+Data+'</td><button class="btn btn-secondary radius size-MINI" onclick="employeePostList.showPostPermissions(\''+row.module_content+'\')">详情</button>';
                }
                return   sData;
            }},
            {"data": 'status',"sTitle":'状态','sClass':'td-status','width' : '5%','orderable':false,'render':function(Data, type, row, meta){
                return '<span class="label radius">'+Data+'</span>';
            }},
            {
                "sTitle":'操作',
                "mDataProp": "id",
                'orderable':false,
                'sClass':'td-manage td-icon text-c',
                'width' : '10%',
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {//参数：DOM, id, object对象, 当前所在行,当前所在列
                    $(nTd).addClass('center').html(
                        '<a style="text-decoration:none" class="status" onClick="abEmployeePostList.postStopOrStart(this,'+ oData.id + ')" href="javascript:;" title="'+oData.status+'"><i class="fa fa-minus-circle"></i></a>'+
                        '<a style="text-decoration:none" onClick="abEmployeePostList.postEditModel('+ oData.id + ')" href="javascript:;" title="编辑"><i class="fa fa-pencil"></i></a>'
                    );//oData.id,获取json对象里的id
                }
            },
        ],
    },

    //员工操作日志列表
    operateLogList:{
        columns:[
            {"data": 'id',"sTitle":'ID','orderable':false,'width' : '5%','sClass':'text-c'},
            {"data": 'operate_user_id',"sTitle":'操作者','orderable':false,'width' : '5%'},
            {"data": 'operate_ab_id',"sTitle":'商户','orderable':false,'width' : '8%'},
            {"data": 'short_operate_condition',"sTitle":'条件', 'sClass':'text-l', 'width':'10%','orderable':false,'render':function(Data, type, row, meta) {
                var sData = Data;
                if(Data.indexOf('...') > 0)
                {
                    sData = '<td>'+Data+'</td><button class="btn btn-secondary radius size-MINI" onclick="showContent(\''+row.operate_condition+'\')">详情</button>';
                }
                return   sData;
            }},
            {"data": 'short_operate_content',"sTitle":'内容', 'sClass':'text-l', 'width':'15%','orderable':false,'render':function(Data, type, row, meta) {
                var sData = Data;
                if(Data.indexOf('...') > 0)
                {
                    sData = '<td>'+Data+'</td><button class="btn btn-secondary radius size-MINI" onclick="showContent(\''+row.operate_content+'\')">详情</button>';
                }
                return   sData;
            }},
            {"data": 'create_time',"sTitle":'时间','orderable':false,'width' : '15%'},
            {"data": 'mark',"sTitle":'备注','orderable':false,'width' : '15%'},
        ],
    },


    adminLoginLogList:{
        columns:[
            {"data": 'id', "sTitle" : '<input type="checkbox" value="0" name="checkbox_wrapper">','sClass' : 'td-checkbox','orderable':false,'width':'1%', 'render':function(Data, type, row, meta){
                return '<input type="checkbox" value="'+Data+'" name="">';
            }},
            {"data": 'login_name',"sTitle":'账号','orderable':false},
            {"data": 'login_position',"sTitle":'登陆地点','orderable':false},
            {"data": 'login_ip',"sTitle":'登录IP','orderable':false},
            {"data": 'login_time',"sTitle":'登录时间','orderable':false},
        ],
    },
    //加盟商列表
    ABList : {
        /*
         * 'id', 'AB_number', 'AB_name', 'AB_principal', 'AB_tel', 'AB_address', 'AB_alliance_fee',
         'AB_balance','AB_start_time', 'AB_end_time', 'AB_operate_id'
         * */
        columns:[
            {"data": 'id',"sTitle":'序号','orderable':false},
            {"data": 'AB_number',"sTitle":'合同编号','orderable':false},
            {"data": 'AB_name',"sTitle":'店铺名称','orderable':false},
            {"data": 'AB_principal',"sTitle":'负责人','orderable':false},
            {"data": 'AB_tel',"sTitle":'联系电话','orderable':false},
            {"data": 'AB_address',"sTitle":'联系地址','orderable':false},
            {"data": 'AB_alliance_fee',"sTitle":'加盟费','orderable':false},
            {"data": 'AB_balance',"sTitle":'余额','orderable':false},
            {"data": 'AB_start_time',"sTitle":'开通时间','orderable':false},
            {"data": 'AB_end_time',"sTitle":'到期时间','orderable':false},
            {"data": 'AB_operate_name',"sTitle":'操作人','orderable':false},
            {"data": 'notice_expire',"sTitle":'到期提醒','sClass':'td-status','orderable':false,'render':function(Data, type, row, meta){
                var sData = '';
                if(Data) {
                    sData = '<span class="label label-warning radius">'+Data+'</span>';
                } else {
                    sData = '<span class="label label-success radius">未提醒</span>';
                }
                return sData;
            }},
            {
                "sTitle":'操作',
                "mDataProp": "id",
                'sClass':'td-manage td-icon',
                'orderable':false,
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {//参数：DOM, id, object对象, 当前所在行,当前所在列
                    var html = '<a style="text-decoration:none" class="status" onClick="jms_edit(' + oData.id + ')" href="javascript:;" title="修改"><i class="fa fa-pencil"></i></a>' +
                        '<a style="text-decoration:none" class="status" onClick="jms_delete(' + oData.id + ')" href="javascript:;" title="删除"><i class="fa fa-remove"></i></a>';
                    if (oData.AB_store_status == 1) {

                        html += '<a style="text-decoration:none" class="status" onClick="jms_start(' + oData.id + ')" href="javascript:;" title="启用"><i class="fa fa-check-circle"></i></a>';
                    } else {
                        html += '<a style="text-decoration:none" class="status" onClick="jms_stop(' + oData.id + ')" href="javascript:;" title="禁用"><i class="fa fa-minus-circle"></i></a>';

                    }
                    html += '<a style="text-decoration:none" class="status" onClick="jms_recharge(' + oData.id + ')" href="javascript:;" title="充值"><i class="fa fa-jpy"></i></a>' +
                        '<a style="text-decoration:none" class="status" onClick="jms_postpone(' + oData.id + ')" href="javascript:;" title="延期"><i class="fa fa-clock-o"></i></a>';
                    $(nTd).addClass('center').html(html);
                }
            },
        ],
    },

    //加盟商权限列表
    ABPostList:{
        columns:[
            {"data": 'id',"sTitle":'ID','orderable':false,'sClass':'text-c', 'width' : '5%'},
            {"data": 'post_name',"sTitle":'权限名称','orderable':false, 'sClass':'text-c', 'width' : '20%'},
            {"data": 'short_module_content',"sTitle":'权限内容', 'sClass':'text-c', 'width':'55%','orderable':false,'render':function(Data, type, row, meta) {
                var sData = Data;
                if(Data.indexOf('...') > 0)
                {
                    sData = '<td>'+Data+'</td><button class="btn btn-secondary radius size-MINI" onclick="abPostList.showPostPermissions(\''+row.module_content+'\')">详情</button>';
                }
                return   sData;
            }},
            {"data": 'status',"sTitle":'启/禁用','sClass':'td-status','width' : '5%','orderable':false,'render':function(Data, type, row, meta){
                return '<span class="label radius">'+Data+'</span>';
            }},
            {
                "sTitle":'操作',
                "mDataProp": "id",
                'orderable':false,
                'sClass':'td-manage td-icon',
                'width' : '10%',
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {//参数：DOM, id, object对象, 当前所在行,当前所在列
                    $(nTd).addClass('center').html(
                        '<a style="text-decoration:none" class="status" onClick="abPostList.postStopOrStart(this,'+ oData.id + ')" href="javascript:;" title="'+oData.status+'"><i class="Hui-iconfont"></i></a>'+
                        '<a style="text-decoration:none" onClick="abPostList.postEditModel('+ oData.id + ')" href="javascript:;" title="编辑"><i class="Hui-iconfont">&#xe6df;</i></a>'
                    );//oData.id,获取json对象里的id
                }
            },
        ],
    },

    //加盟商权限列表
    AbReceiptLog:{
        columns:[
            {"data": 'id',"sTitle":'记录号','orderable':false,'sClass':'text-c', 'width' : '5%'},
            {"data": 'recharge_user',"sTitle":'收款人','orderable':false, 'sClass':'text-c', 'width' : '20%'},
            {"data": 'recharge_money',"sTitle":'收款金额','orderable':false, 'sClass':'text-c', 'width' : '20%'},
            {"data": 'AB_number',"sTitle":'合同编号','orderable':false, 'sClass':'text-c', 'width' : '20%'},
            {"data": 'AB_name',"sTitle":'店铺名称','orderable':false, 'sClass':'text-c', 'width' : '20%'},
            {"data": 'recharge_time',"sTitle":'日志生成时间','orderable':false, 'sClass':'text-c', 'width' : '20%'}
        ]
    },

    //总部商品列表
    GoodsList : {
        columns:[
            {"data": 'id',"sTitle":'序号','orderable':false},
            {"data": 'goods_code',"sTitle":'商品编号','orderable':false},
            {"data": 'goods_name',"sTitle":'名称','orderable':false},
            {"data": 'goods_price',"sTitle":'价格','orderable':false},
            {"data": 'goods_cost',"sTitle":'成本价','orderable':false},
            {"data": 'goods_discount',"sTitle":'折扣价','orderable':false},
            {"data": 'discount_time',"sTitle":'折扣时间','orderable':false},
            {"data": 'goods_color',"sTitle":'颜色','orderable':false},
            {"data": 'goods_size',"sTitle":'尺寸','orderable':false},
            {"data": 'goods_texture',"sTitle":'材质','orderable':false},
            {"data": 'goods_style',"sTitle":'内页/风格','orderable':false},
            {"data": 'goods_num',"sTitle":'数量','orderable':false},
            {"data": 'create_user_name',"sTitle":'创建人','orderable':false},
            {
                "sTitle":'操作',
                "mDataProp": "id",
                'sClass':'td-manage td-icon',
                'orderable':false,
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {//参数：DOM, id, object对象, 当前所在行,当前所在列
                    var html = '<a style="text-decoration:none" class="status" onClick="goods_edit(' + oData.id + ')" href="javascript:;" title="修改"><i class="fa fa-pencil"></i></a>' +
                        '<a style="text-decoration:none" class="status" onClick="goods_delete(' + oData.id + ')" href="javascript:;" title="删除"><i class="fa fa-remove"></i></a>'+
                        '<a style="text-decoration:none" class="status" onClick="goods_show_image(' + oData.id + ')" href="javascript:;" title="查看图片"><i class="Hui-iconfont Hui-iconfont-tuku"></i></a>' ;
                    if (oData.goods_status == 1) {
                        html += '<a style="text-decoration:none" class="status" onClick="goods_shelf(' + oData.id + ', \'是否上架?\')" href="javascript:;" title="上架"><i class="Hui-iconfont Hui-iconfont-shangjia"></i></a>';
                    }

                    if (oData.goods_status == 2) {
                        html += '<a style="text-decoration:none" class="status" onClick="goods_shelf(' + oData.id + ', \'是否下架?\')" href="javascript:;" title="下架"><i class="Hui-iconfont Hui-iconfont-xiajia"></i></a>';
                    }

                    $(nTd).addClass('center').html(html);
                }
            },
        ],
    },
    //加盟商品列表
    ABGoodsList : {
        /*
         * 'id', 'AB_number', 'AB_name', 'AB_principal', 'AB_tel', 'AB_address', 'AB_alliance_fee',
         'AB_balance','AB_start_time', 'AB_end_time', 'AB_operate_id'
         * */
        columns:[
            {"data": 'id',"sTitle":'序号','orderable':false},
            {"data": 'goods_code',"sTitle":'商品编号','orderable':false},
            {"data": 'goods_name',"sTitle":'商品名称','orderable':false},
            {"data": 'goods_price',"sTitle":'商品类型','orderable':false},
            {"data": 'goods_num',"sTitle":'库存数量','orderable':false},
            {"data": 'goods_color',"sTitle":'颜色','orderable':false},
            {"data": 'goods_discount',"sTitle":'折扣价','orderable':false},
            {"data": 'discount_time',"sTitle":'折扣时间','orderable':false},
            {"data": 'goods_color',"sTitle":'颜色','orderable':false},
            {"data": 'goods_size',"sTitle":'尺寸','orderable':false},
            {"data": 'goods_p',"sTitle":'P数','orderable':false},
            {
                "sTitle":'操作',
                "mDataProp": "id",
                'sClass':'td-manage td-icon',
                'orderable':false,
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {//参数：DOM, id, object对象, 当前所在行,当前所在列
                    var html = '<a style="text-decoration:none" class="status" onClick="ab_goods_edit(' + oData.id + ')" href="javascript:;" title="修改"><i class="fa fa-pencil"></i></a>' +
                        '<a style="text-decoration:none" class="status" onClick="ab_goods_delete(' + oData.id + ')" href="javascript:;" title="删除"><i class="fa fa-remove"></i></a>'+
                        '<a style="text-decoration:none" class="status" onClick="ab_goods_show_image(' + oData.id + ')" href="javascript:;" title="查看图片"><i class="fa fa-eye"></i></a>'+
                        '<a style="text-decoration:none" class="status" onClick="ab_goods_stock_in(\'' + oData.goods_code + '\')" href="javascript:;" title="入库">入</a>'+
                        '<a style="text-decoration:none" class="status" onClick="ab_goods_stock_out(\'' + oData.goods_code + '\')" href="javascript:;" title="出库">出</a>' ;
                    if (oData.goods_status == 1) {
                        html +=  '<a style="text-decoration:none" class="status" onClick="ab_goods_sell(' + oData.id + ', \'是否上架?\')" href="javascript:;" title="上架"><i class="Hui-iconfont Hui-iconfont-shangjia"></i></a>';
                    }
                    if (oData.goods_status == 2) {
                        html +=  '<a style="text-decoration:none" class="status" onClick="ab_goods_sell(' + oData.id + ', \'是否下架?\')" href="javascript:;" title="上架"><i class="Hui-iconfont Hui-iconfont-xiajia"></i></a>';
                    }
                    $(nTd).addClass('center').html(html);
                }
            },
        ],
    },
    //订单列表
    GoodsOrderList : {
        /*
         * 'id', 'AB_number', 'AB_name', 'AB_principal', 'AB_tel', 'AB_address', 'AB_alliance_fee',
         'AB_balance','AB_start_time', 'AB_end_time', 'AB_operate_id'
         * */
        columns:[
            {"data": 'id',"sTitle":'序号','orderable':false},
            {"data": 'order_number',"sTitle":'订单编号','orderable':false},
            {"data": 'AB_number',"sTitle":'合同编号','orderable':false},
            {"data": 'order_user',"sTitle":'收件人','orderable':false},
            {"data": 'order_money',"sTitle":'总价','orderable':false},
            {"data": 'order_discount',"sTitle":'限时折扣价','orderable':false},
            {"data": 'order_real_money',"sTitle":'实际成交额','orderable':false},
            {"data": 'operate_user',"sTitle":'操作人','orderable':false},
            {"data": 'create_time',"sTitle":'订单时间','orderable':false},
            {"data": 'order_status_name',"sTitle":'状态','orderable':false},
            {
                "sTitle":'操作',
                "mDataProp": "id",
                'sClass':'td-manage td-icon',
                'orderable':false,
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {//参数：DOM, id, object对象, 当前所在行,当前所在列
                    var html = '<a style="text-decoration:none" class="status" onClick="orderDetial(\'' + oData.order_number + '\')" href="javascript:;" title="查看商品"><i class="fa fa-list-alt"></i></a>' +
                        // '<a style="text-decoration:none" class="status" onClick="editOrder(' + oData.id + ')" href="javascript:;" title="编辑订单"><i class="fa fa-cog"></i></a>'+
                        '<a style="text-decoration:none" class="status" onClick="refundMoney(\'' + oData.order_number + '\')" href="javascript:;" title="退款"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAABSUlEQVRYhe2WwVHDMBBFXYJLSAnuAJ+5hBLcAXSAOhDevyLH0AGugK+4AdNB3AHuAA7BDEwCkWUnGWb8Z/Yo/SftX9lJMmtWoAgYr2g2KrbWcsmVXZwZQLxXvP8s2XqV9Qa4pXPZSQH2zQ8U5M0ruHG4rx/lajJzAnkQwOEa3zau7IJAHlmvPQwVbeiJzYgTfxUB8z07VDxNAkBFS0U7HEDuRgNQ0b5oeU0gPwaxBxA6KUdugHRSEDCfqQ8CoKILMg9qAcQTuKFKFQwA8VMBNLuNYQg8hAPATHYDXmXrgeegEDopqFINeimnHMNg01OK1qYE8vMv7terVLQ2jVrchy7K3LnMK5qoL2VvPrSXtDattVx6lfXu8ZFilPmoEMaYJ8lvPx8DjBXdmNwkdC6joos0NlGB+wviYvPcQ1z0QaFzWXSYZs36D/oA6ABwZLJsqGwAAAAASUVORK5CYII="  /></a>';
                    if (oData.order_status == 2 || oData.order_status == 3) {
                        html += '<a style="text-decoration:none" class="status" onClick="sendOutAllGoods(\'' + oData.order_number + '\')" href="javascript:;" title="发货"><i class="fa fa-truck"></i></a>';
                    }
                    $(nTd).addClass('center').html(html);
                }
            },
        ],
    },
    //加盟商直购列表 -- 总部
    HeadGoodsOrderDetailList : {
        /*
         * 'id', 'AB_number', 'AB_name', 'AB_principal', 'AB_tel', 'AB_address', 'AB_alliance_fee',
         'AB_balance','AB_start_time', 'AB_end_time', 'AB_operate_id'
         * */
        columns:[
            {"data": 'goods_code',"sTitle":'编号','orderable':false},
            {"data": 'goods_name',"sTitle":'名称','orderable':false},
            {"data": 'goods_unit_price',"sTitle":'价格','orderable':false},
            {"data": 'goods_real_price',"sTitle":'折扣价','orderable':false},
            {"data": 'goods_color',"sTitle":'颜色','orderable':false},
            {"data": 'goods_size',"sTitle":'尺寸','orderable':false},
            {"data": 'goods_texture',"sTitle":'材质','orderable':false},
            {"data": 'goods_style',"sTitle":'内页/风格','orderable':false},
            {"data": 'goods_nums',"sTitle":'数量','orderable':false},
            {"data": 'create_time',"sTitle":'时间','orderable':false},
            {"data": 'status_name',"sTitle":'状态','orderable':false},
            {
                "sTitle":'操作',
                "mDataProp": "id",
                'sClass':'td-manage td-icon',
                width: '15%',
                'orderable':false,
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {//参数：DOM, id, object对象, 当前所在行,当前所在列
                    var html = '';
                    if (oData.status == 1 || oData.status == 2) {
                        html += '<a style="text-decoration:none" class="status" onClick="sendOutGoods(' + oData.id + ')" href="javascript:;" title="发货"><i class="fa fa-truck"></i></a>';
                    }
                    if (oData.status == 1) {
                        html += '<a style="text-decoration:none" class="status" onClick="brokenGoods(' + oData.id + ')" href="javascript:;" title="断货"><i class="fa fa-chain-broken"></i></a>';
                    }
                    html += '<a style="text-decoration:none" class="status" onClick="refundGoods(' + oData.id + ')" href="javascript:;" title="退货"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAACn0lEQVRYha1X0ZXiMAxMCZRACXRwdHApgQ42HUAHZjXy8Rk6uOtAzjZAOoAOSAfcR+xgm9hJls17/gmRNB5pJFEUCx4BSsNUG4YYxsUwHvZc+ndUf/Hn7yU+p4Oe1Now1QZ09wLmD+humGpRavVecOCQCiwg458UkEZjvzywUquI4ocwbgIcROtN0k7rjQAHYdwiMJfZbIjWG//WwuiEqVp8CaZKGJ3PRg58b6TUKggOtO/kUZRaCdAGIFL+YtqFcf5u4BffjLOfjvGPgIN/858K7vlvPf+H8MeTWjvqhdFN0S5A2WjsU0eA8sVGqdVQE3EqfIpE0y4w1LSLC+ipilCK/elvOgqcqRpNsUMmjFuaPro61JbGbeLbbQqAjXVzLDiDMpkbZ9TTd3Oo53TDJICw1sqI/skmcxwYYKoE2L4cS3POjwfgWPhtNGUUP4bxaICPrz/0Kz6Nxn7Kl9/OB3kIyOTQ+oU4Rb8w/csB8C59CdHEH/adbCuadn6NuCLsmxfVclJr38YAf3NSDljPAYhpGwMgQNuzQzs3R6ZaeAAgl4IpAANLrpBBdwGZ6UbWAxCgnV2EKQYaJiVMlUuTYapzfpz9swhnyjAG0K9mdBVG5+zmgBiR4YxG5BxbJbhuJsAxptsr2O2or7gRFcWzFRuma/CxrXLXu335GKY6NYzcb746Bp8OPKPzX44OIzmptR0ww3SzHe8oIBNsPGP9IB5syWGUG5U/9Pjb1ujI93OT3FreAZBbSMY+miOn2cH9FOe2rSAVvHCdTviL9szJbauwMgvW6Qb4WBycqXpZ76fWch95mI5eoo3GfmpnsDK8Bmr47npv/+WkpCbRGRnL6JIFNxuEHTZTmn8JzDj/uJwFKF1HjGTV2nfHsZU89/wHW+7/GTc8IwkAAAAASUVORK5CYII="  /></a>';
                    $(nTd).addClass('center').html(html);
                }
            },
        ],
    },
    //加盟商直购列表 -- 加盟商
    GoodsOrderDetailList : {
        /*
         * 'id', 'AB_number', 'AB_name', 'AB_principal', 'AB_tel', 'AB_address', 'AB_alliance_fee',
         'AB_balance','AB_start_time', 'AB_end_time', 'AB_operate_id'
         * */
        columns:[
            {"data": 'goods_code',"sTitle":'商品编号','orderable':false},
            {"data": 'goods_name',"sTitle":'商品名称','orderable':false},
            {"data": 'goods_unit_price',"sTitle":'价格','orderable':false},
            {"data": 'goods_real_price',"sTitle":'折扣价','orderable':false},
            {"data": 'goods_color',"sTitle":'颜色','orderable':false},
            {"data": 'goods_size',"sTitle":'尺寸','orderable':false},
            {"data": 'goods_texture',"sTitle":'材质','orderable':false},
            {"data": 'goods_style',"sTitle":'内页/风格','orderable':false},
            {"data": 'goods_nums',"sTitle":'购买数量','orderable':false},
            {"data": 'create_time',"sTitle":'下单时间','orderable':false},
            {"data": 'status_name',"sTitle":'状态','orderable':false},
            {
                "sTitle":'操作',
                "mDataProp": "id",
                'sClass':'td-manage td-icon',
                'orderable':false,
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {//参数：DOM, id, object对象, 当前所在行,当前所在列
                    var html = '<a style="text-decoration:none" class="status" onClick="goods_show_image(' + oData.id + ')" href="javascript:;" title="全部退款"><i class="fa fa-reply-all"></i></a>';
                    $(nTd).addClass('center').html(html);
                }
            },
        ],
    },

    //帐变列表 -- 总部
    CoinChangeList : {
        columns:[
            {"data": 'id',"sTitle":'序号','orderable':false},
            {"data": 'AB_name',"sTitle":'店铺名称','orderable':false},
            {"data": 'change_money',"sTitle":'变动金额','orderable':false},
            {"data": 'before_change',"sTitle":'变动前','orderable':false},
            {"data": 'after_change',"sTitle":'变动后','orderable':false},
            {"data": 'AB_number',"sTitle":'合同编号','orderable':false},
            {"data": 'change_type',"sTitle":'类型','orderable':false},
            {"data": 'operate_user',"sTitle":'操作人','orderable':false},
            {"data": 'create_time',"sTitle":'时间','orderable':false},
            {"data": 'mark',"sTitle":'备注','orderable':false},
        ],
    },
    //加盟商直购列表
    ABGoodsOrderList : {
        /*
         * 'id', 'AB_number', 'AB_name', 'AB_principal', 'AB_tel', 'AB_address', 'AB_alliance_fee',
         'AB_balance','AB_start_time', 'AB_end_time', 'AB_operate_id'
         * */
        columns:[
            {"data": 'id',"sTitle":'序号','orderable':false},
            {"data": 'order_number',"sTitle":'订单编号','orderable':false},
            {"data": 'order_user',"sTitle":'收件人','orderable':false},
            {"data": 'order_money',"sTitle":'总价','orderable':false},
            {"data": 'order_discount',"sTitle":'限时折扣价','orderable':false},
            {"data": 'order_real_money',"sTitle":'实际成交额','orderable':false},
            {"data": 'operate_user',"sTitle":'操作人','orderable':false},
            {"data": 'create_time',"sTitle":'订单时间','orderable':false},
            {"data": 'order_status_name',"sTitle":'状态','orderable':false},
            {
                "sTitle":'操作',
                "mDataProp": "id",
                'sClass':'td-manage td-icon',
                'orderable':false,
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {//参数：DOM, id, object对象, 当前所在行,当前所在列
                    var html = '<a style="text-decoration:none" class="status" onClick="orderDetial(\'' + oData.order_number + '\')" href="javascript:;" title="查看商品"><i class="fa fa-list-alt"></i></a>';
                    if(oData.order_status == 1) {
                        html +='<a style="text-decoration:none" class="status" onClick="payOrder(\'' + oData.order_number + '\')" href="javascript:;" title="支付"><i class="fa fa-paypal"></i></a>';
                    }
                    html += '<a style="text-decoration:none" class="status" onClick="orderRefund(\'' + oData.order_number + '\')" href="javascript:;" title="全部退款"><i class="fa fa-reply-all"></i></a>';
                    $(nTd).addClass('center').html(html);
                }
            },
        ],
    },
    //加盟商直购列表
    ABGoodsOrderDetailList : {
        /*
         * 'id', 'AB_number', 'AB_name', 'AB_principal', 'AB_tel', 'AB_address', 'AB_alliance_fee',
         'AB_balance','AB_start_time', 'AB_end_time', 'AB_operate_id'
         * */
        columns:[
            {"data": 'goods_code',"sTitle":'商品编号','orderable':false},
            {"data": 'goods_name',"sTitle":'商品名称','orderable':false},
            {"data": 'goods_unit_price',"sTitle":'价格','orderable':false},
            {"data": 'goods_real_price',"sTitle":'折扣价','orderable':false},
            {"data": 'goods_color',"sTitle":'颜色','orderable':false},
            {"data": 'goods_size',"sTitle":'尺寸','orderable':false},
            {"data": 'goods_texture',"sTitle":'材质','orderable':false},
            {"data": 'goods_style',"sTitle":'内页/风格','orderable':false},
            {"data": 'goods_nums',"sTitle":'购买数量','orderable':false},
            {"data": 'create_time',"sTitle":'下单时间','orderable':false},
            {"data": 'import_status_name',"sTitle":'入库','orderable':false},
            {"data": 'status_name',"sTitle":'状态','orderable':false},
            {
                "sTitle":'操作',
                "mDataProp": "id",
                'sClass':'td-manage td-icon',
                'orderable':false,
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {//参数：DOM, id, object对象, 当前所在行,当前所在列
                    var html = '--';
                    // var html = '<a style="text-decoration:none" class="status" onClick="goods_show_image(' + oData.id + ')" href="javascript:;" title="全部退款"><i class="fa fa-reply-all"></i></a>'+
                    if((oData.status == 3) && (oData.import_status == 1)) {
                        var html = '<a style="text-decoration:none" onClick="goods_import(' + oData.id + ',' + oData.goods_id + ',\'' + oData.goods_name + '\',\'' + oData.goods_nums + '\')" href="javascript:;" title="入库"><i class="fa fa-sign-in"></i></a>';
                    }
                    $(nTd).addClass('center').html(html);
                }
            },
        ],
    },
    //广告列表
    AdvertList : {
        /*
         * 'id', 'AB_number', 'AB_name', 'AB_principal', 'AB_tel', 'AB_address', 'AB_alliance_fee',
         'AB_balance','AB_start_time', 'AB_end_time', 'AB_operate_id'
         * */
        columns:[
            {"data": 'id',"sTitle":'序号','orderable':false},
            {"data": 'advert_name',"sTitle":'广告名称','orderable':false},
            {"data": 'advert_matter',"sTitle":'广告素材','orderable':false,'render':function(Data, type, row, meta) {
                return   '<img src="/uploads/' + Data + '" style="width: 100px;height: 100px;margin:0 auto;">';
            }},
            {"data": 'advert_position',"sTitle":'广告位置','orderable':false},
            {"data": 'advert_principal',"sTitle":'负责人','orderable':false},
            {"data": 'advert_commission',"sTitle":'广告佣金','orderable':false},
            {"data": 'advert_tel',"sTitle":'联系电话','orderable':false},
            {"data": 'advert_balance',"sTitle":'余额','orderable':false},
            {"data": 'end_time',"sTitle":'到期时间','orderable':false},
            {"data": 'advert_pay_money',"sTitle":'收款','orderable':false},
            {"data": 'advert_payee',"sTitle":'收款人','orderable':false},
            {"data": 'advert_handler_user',"sTitle":'操作人','orderable':false},
            {"data": 'start_time',"sTitle":'投放时间','orderable':false},
            {
                "sTitle":'操作',
                "mDataProp": "id",
                'sClass':'td-manage td-icon',
                'orderable':false,
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {//参数：DOM, id, object对象, 当前所在行,当前所在列
                    var html = '<a style="text-decoration:none" class="status" onClick="advert_edit(' + oData.id + ')" href="javascript:;" title="编辑"><i class="fa fa-pencil"></i></a>' +
                        '<a style="text-decoration:none" class="status" onClick="advert_delete(' + oData.id + ')" href="javascript:;" title="删除"><i class="fa fa-remove"></i></a>'+
                        '<a style="text-decoration:none" class="status" onClick="advert_postpone(' + oData.id + ')" href="javascript:;" title="延期"><i class="fa fa-clock-o"></i></a>'+
                        '<a style="text-decoration:none" class="status" onClick="advert_recharge(' + oData.id + ')" href="javascript:;" title="充值"><i class="fa fa-jpy"></i></a>';
                    if (oData.status == 1) {
                        html += '<a style="text-decoration:none" class="status" onClick="advert_stop(' + oData.id + ')" href="javascript:;" title="禁用"><i class="fa fa-minus-circle"></i></a>';
                    } else {
                        html += '<a style="text-decoration:none" class="status" onClick="advert_start(' + oData.id + ')" href="javascript:;" title="启用"><i class="fa fa-check-circle"></i></a>';

                    }
                    $(nTd).addClass('center').html(html);
                }
            }
        ],
    },

    //消息列表
    messageList : {
        columns:[
            {"data": 'id',"sTitle":'序号','orderable':false},
            {"data": 'business_name',"sTitle":'商户','orderable':false},
            {"data": 'short_content',"sTitle":'内容', 'sClass':'text-c','orderable':false,'render':function(Data, type, row, meta) {
                var sData = Data;
                if(Data.indexOf('...') > 0)
                {
                    sData = '<td>'+Data+'</td><button class="btn btn-secondary radius size-MINI" onclick="showContent(\''+row.content+'\')">详情</button>';
                }
                return   sData;
            }},
            {"data": 'type',"sTitle":'类型','sClass':'td-status','orderable':false,'render':function(Data, type, row, meta){
                return '<span class="label label-warning radius">'+Data+'</span>';
            }},

            {"data": 'recharge_info',"sTitle":'充值','orderable':false},

            {"data": 'postpone_info',"sTitle":'延期','orderable':false},

            {"data": 'status',"sTitle":'状态','sClass':'td-status','orderable':false,'render':function(Data, type, row, meta){
                return '<span class="label label-success radius">'+Data+'</span>';
            }},
            {"data": 'create_time',"sTitle":'发送时间','orderable':false},
            {"data": 'reply_name',"sTitle":'回复者','orderable':false},
            {"data": 'short_reply_content',"sTitle":'内容', 'sClass':'text-c','orderable':false,'render':function(Data, type, row, meta) {
                var sData = Data;
                if(Data.indexOf('...') > 0)
                {
                    sData = '<td>'+Data+'</td><button class="btn btn-secondary radius size-MINI" onclick="showContent(\''+row.reply_content+'\')">详情</button>';
                }
                return   sData;
            }},
            {"data": 'reply_time',"sTitle":'回复时间','orderable':false},
            {
                "sTitle":'操作',
                "mDataProp": "id",
                'sClass':'td-manage td-icon',
                'orderable':false,
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {//参数：DOM, id, object对象, 当前所在行,当前所在列
                    var html = '<a style="text-decoration:none" class="status" onClick="message.reply(' + oData.id + ')" href="javascript:;" title="回复"><i class="fa fa-reply"></i></a>';
                    $(nTd).addClass('center').html(html);
                }
            }
        ]
    },
    //消息列表
    ABmessageList : {
        columns:[
            {"data": 'id',"sTitle":'序号','orderable':false},
            {"data": 'short_content',"sTitle":'内容', 'sClass':'text-c','orderable':false,'render':function(Data, type, row, meta) {
                var sData = Data;
                if(Data.indexOf('...') > 0)
                {
                    sData = '<td>'+Data+'</td><button class="btn btn-secondary radius size-MINI" onclick="showContent(\''+row.content+'\')">详情</button>';
                }
                return   sData;
            }},
            {"data": 'type',"sTitle":'类型','sClass':'td-status','orderable':false,'render':function(Data, type, row, meta){
                return '<span class="label label-warning radius">'+Data+'</span>';
            }},

            {"data": 'recharge_info',"sTitle":'充值','orderable':false},

            {"data": 'postpone_info',"sTitle":'延期','orderable':false},

            {"data": 'status',"sTitle":'状态','sClass':'td-status','orderable':false,'render':function(Data, type, row, meta){
                return '<span class="label label-success radius">'+Data+'</span>';
            }},
            {"data": 'create_time',"sTitle":'发送时间','orderable':false},
            {"data": 'short_reply_content',"sTitle":'回复', 'sClass':'text-c','orderable':false,'render':function(Data, type, row, meta) {
                var sData = Data;
                if(Data.indexOf('...') > 0)
                {
                    sData = '<td>'+Data+'</td><button class="btn btn-secondary radius size-MINI" onclick="showContent(\''+row.reply_content+'\')">详情</button>';
                }
                return   sData;
            }},
            {"data": 'reply_time',"sTitle":'回复时间','orderable':false}
        ]
    },

    //会员列表
    memberList : {
        columns:[
            {"data": 'id',"sTitle":'序号','orderable':false},
            {"data": 'name',"sTitle":'姓名','orderable':false},
            {"data": 'sex',"sTitle":'性别','orderable':false},
            {"data": 'wechat',"sTitle":'微信','orderable':false},
            {"data": 'tel',"sTitle":'电话','orderable':false},
            {"data": 'valid_money',"sTitle":'余额','orderable':false},
            {"data": 'integral',"sTitle":'积分','orderable':false},
            {"data": 'total_consume',"sTitle":'总计消分','orderable':false},
            {"data": 'operate_id',"sTitle":'操作者','orderable':false},
            {"data": 'create_time',"sTitle":'创建时间','orderable':false},
            {
                "sTitle":'操作',
                "mDataProp": "id",
                'sClass':'td-manage td-icon',
                'orderable':false,
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {//参数：DOM, id, object对象, 当前所在行,当前所在列
                    var html = '<a style="text-decoration:none" class="status" onClick="member.deleteModal(' + oData.id + ')" href="javascript:;" title="删除会员"><i class="fa fa-remove"></i></a>'+
                        '<a style="text-decoration:none" class="status" onClick="member.editModal(' + oData.id + ')" href="javascript:;" title="编辑会员"><i class="fa fa-pencil"></i></a>'+
                        '<a style="text-decoration:none" class="status" onClick="member.rechargeModal(' + oData.id + ')" href="javascript:;" title="会员充值"><i class="fa fa-cny"></i></a>'+
                        '<a style="text-decoration:none" class="status" onClick="member.referrerModal(' + oData.id + ')" href="javascript:;" title="推荐信息"><i class="fa fa-users"></i></a>'+
                        '<a style="text-decoration:none" class="status" onClick="member.integralModal(' + oData.id + ')" href="javascript:;" title="积分操作"><i class="fa fa-pencil-square-o"></i></a>'+
                        '<a style="text-decoration:none" class="status" onClick="member.addOrderModal(' + oData.id + ')" href="javascript:;" title="创建订单"><i class="fa fa-newspaper-o"></i></a>';
                    $(nTd).addClass('center').html(html);
                }
            },
        ],
    },

    //普通套系列表
    comboGeneralList : {
        columns:[
            {"data": 'id',"sTitle":'序号','orderable':false},
            {"data": 'combo_name',"sTitle":'套系名称','orderable':false},
            {"data": 'combo_price',"sTitle":'套系价格','orderable':false},
            {"data": 'register_count',"sTitle":'入底入册','orderable':false},
            {"data": 'goods_content',"sTitle":'商品列表','orderable':false,'render':function(Data, type, row, meta){
                return '<button class="btn btn-success size-S" onclick="comboGeneral.showContent(\''+row.goods_content+'\')">查看</button>';
            }},
            {"data": 'combo_discount',"sTitle":'套系折扣','orderable':false},
            {"data": 'combo_integral',"sTitle":'套系积分','orderable':false},
            {"data": 'mark',"sTitle":'备注','orderable':false},
            {"data": 'create_time',"sTitle":'创建时间','orderable':false},
            {
                "sTitle":'操作',
                "mDataProp": "id",
                'sClass':'td-manage td-icon',
                'orderable':false,
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {//参数：DOM, id, object对象, 当前所在行,当前所在列
                    var html =
                        '<a style="text-decoration:none" class="status" onClick="comboGeneral.editModal(' + oData.id + ')" href="javascript:;" title="编辑套系"><i class="fa fa-pencil"></i></a>'+
                        '<a style="text-decoration:none" class="status" onClick="comboGeneral.deleteModal(' + oData.id + ')" href="javascript:;" title="删除套系"><i class="fa fa-remove"></i></a>';
                    $(nTd).addClass('center').html(html);
                }
            },
        ],
    },

    //普通套系选择商品列表
    comboABGoodsList : {
        /*
         * 'id', 'AB_number', 'AB_name', 'AB_principal', 'AB_tel', 'AB_address', 'AB_alliance_fee',
         'AB_balance','AB_start_time', 'AB_end_time', 'AB_operate_id'
         * */
        columns:[
            {"data": 'id', "sTitle" : '<input type="checkbox" value="0" name="checkbox_wrapper">','sClass' : 'td-checkbox','orderable':false,'width':'1%', 'render':function(Data, type, row, meta){
                return '<input type="checkbox" '+row.checked+' value="'+Data+'" name="">';
            }},
            {"data": 'id',"sTitle":'序号','orderable':false},
            {"data": 'goods_code',"sTitle":'商品编号','orderable':false},
            {"data": 'goods_name',"sTitle":'商品名称','orderable':false},
            {"data": 'goods_price',"sTitle":'商品类型','orderable':false},
            {"data": 'goods_num',"sTitle":'库存数量','orderable':false},
            {"data": 'goods_color',"sTitle":'颜色','orderable':false},
            {"data": 'goods_discount',"sTitle":'折扣价','orderable':false},
            {"data": 'discount_time',"sTitle":'折扣时间','orderable':false},
            {"data": 'goods_color',"sTitle":'颜色','orderable':false},
            {"data": 'goods_size',"sTitle":'P数','orderable':false},
        ],
    },

    //成长套系列表
    comboGrowList : {
        columns:[
            {"data": 'id',"sTitle":'序号','orderable':false},
            {"data": 'combo_name',"sTitle":'套系名称','orderable':false},
            {"data": 'combo_price',"sTitle":'套系价格','orderable':false},
            {"data": 'combo_content',"sTitle":'套系列表','orderable':false,'render':function(Data, type, row, meta){
                return '<button class="btn btn-success size-S" onclick="comboGrow.showContent(\''+row.combo_content+'\')">查看</button>';
            }},
            {"data": 'combo_discount',"sTitle":'套系折扣','orderable':false},
            {"data": 'combo_integral',"sTitle":'套系积分','orderable':false},
            {"data": 'mark',"sTitle":'备注','orderable':false},
            {"data": 'create_time',"sTitle":'创建时间','orderable':false},
            {
                "sTitle":'操作',
                "mDataProp": "id",
                'sClass':'td-manage td-icon',
                'orderable':false,
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {//参数：DOM, id, object对象, 当前所在行,当前所在列
                    var html =
                        '<a style="text-decoration:none" class="status" onClick="comboGrow.editModal(' + oData.id + ')" href="javascript:;" title="编辑套系"><i class="fa fa-pencil"></i></a>'+
                        '<a style="text-decoration:none" class="status" onClick="comboGrow.deleteModal(' + oData.id + ')" href="javascript:;" title="删除套系"><i class="fa fa-remove"></i></a>';
                    $(nTd).addClass('center').html(html);
                }
            },
        ],
    },

    //成长套系选择普通套系列表
    selectGeneralComboList : {
        columns:[
            {"data": 'id', "sTitle" : '<input type="checkbox" value="0" name="checkbox_wrapper">','sClass' : 'td-checkbox','orderable':false,'width':'1%', 'render':function(Data, type, row, meta){
                return '<input type="checkbox" '+row.checked+' value="'+Data+'" name="">';
            }},
            {"data": 'id',"sTitle":'序号','orderable':false},
            {"data": 'combo_name',"sTitle":'套系名称','orderable':false},
            {"data": 'combo_price',"sTitle":'套系价格','orderable':false},
            {"data": 'register_count',"sTitle":'入底入册','orderable':false},
            {"data": 'combo_discount',"sTitle":'套系折扣','orderable':false},
            {"data": 'combo_integral',"sTitle":'套系积分','orderable':false},
            {"data": 'mark',"sTitle":'备注','orderable':false},
        ],
    },

    //会员订单列表
    memberOrderList : {
        columns:[
            {"data": 'id',"sTitle":'序号','orderable':false,'render':function(Data, type, row, meta){
                return '<a style="text-decoration:none" class="show-detail" onClick="memberOrder.editModal(' + row.id + ')" href="javascript:;" title="查看订单">'+Data+'</a>';
            }},
            {"data": 'member_id',"sTitle":'宝宝姓名','orderable':false,'render':function(Data, type, row, meta){
                return '<a style="text-decoration:none" class="show-detail" onClick="memberOrder.editModal(' + row.id + ')" href="javascript:;" title="查看订单">'+Data+'</a>';
            }},
            {"data": 'order_number',"sTitle":'订单编号','orderable':false,'render':function(Data, type, row, meta){
                return '<a style="text-decoration:none" class="show-detail" onClick="memberOrder.editModal(' + row.id + ')" href="javascript:;" title="查看订单">'+Data+'</a>';
            }},

            {"data": 'order_type',"sTitle":'套系类型','orderable':true,'render':function(Data, type, row, meta){
                // return '<a style="text-decoration:none" class="show-detail" onClick="memberOrder.editModal(' + row.id + ')" href="javascript:;" title="查看订单">'+Data+'</a>';
                // var event = '<a style="text-decoration:none" class="show-detail" href="javascript:;" title="查看订单">'+Data+'</a>';
                // var eventObj = $(event);
                // eventObj.click(function(){
                //     addRow(row);
                // });
                // return event;
                if(row.order_type == '成长套系') {
                    return '<a style="text-decoration:none" class="show-detail" onClick="addRow(this,\'' + row.order_number + '\')" href="javascript:;" title="查看订单">'+Data+'</a>';
                } else {
                    return '<a style="text-decoration:none" class="show-detail" onClick="memberOrder.editModal(' + row.id + ')" href="javascript:;" title="查看订单">'+Data+'</a>';
                }
            }},

            {"data": 'combo_price',"sTitle":'套系价格','orderable':false,'render':function(Data, type, row, meta){
                return '<a style="text-decoration:none" class="show-detail" onClick="memberOrder.editModal(' + row.id + ')" href="javascript:;" title="查看订单">'+Data+'</a>';
            }},

            {"data": 'discount',"sTitle":'折扣','orderable':false,'render':function(Data, type, row, meta){
                return '<a style="text-decoration:none" class="show-detail" onClick="memberOrder.editModal(' + row.id + ')" href="javascript:;" title="查看订单">'+Data+'</a>';
            }},

            {"data": 'integral',"sTitle":'积分','orderable':false,'render':function(Data, type, row, meta){
                return '<a style="text-decoration:none" class="show-detail" onClick="memberOrder.editModal(' + row.id + ')" href="javascript:;" title="查看订单">'+Data+'</a>';
            }},

            {"data": 'price',"sTitle":'成单价','orderable':false,'render':function(Data, type, row, meta){
                return '<a style="text-decoration:none" class="show-detail" onClick="memberOrder.editModal(' + row.id + ')" href="javascript:;" title="查看订单">'+Data+'</a>';
            }},
            {"data": 'total_money',"sTitle":'实收','orderable':true,'render':function(Data, type, row, meta){
                return '<a style="text-decoration:none" class="show-detail" onClick="memberOrder.editModal(' + row.id + ')" href="javascript:;" title="查看订单">'+Data+'</a>';
            }},
            {"data": 'earnest',"sTitle":'定金','orderable':false,'render':function(Data, type, row, meta){
                return '<a style="text-decoration:none" class="show-detail" onClick="memberOrder.editModal(' + row.id + ')" href="javascript:;" title="查看订单">'+Data+'</a>';
            }},
            // {"data": 'number',"sTitle":'数量','orderable':false,'render':function(Data, type, row, meta){
            //     return '<a style="text-decoration:none" class="show-detail" onClick="memberOrder.editModal(' + row.id + ')" href="javascript:;" title="查看订单">'+Data+'</a>';
            // }},

            {"data": 'final_payment',"sTitle":'尾款','orderable':true,'render':function(Data, type, row, meta){
                return '<a style="text-decoration:none" class="show-detail" onClick="memberOrder.editModal(' + row.id + ')" href="javascript:;" title="查看订单">'+Data+'</a>';
            }},
            // {"data": 'order_type',"sTitle":'','orderable':false},
            {"data": 'create_time',"sTitle":'签约时间','orderable':true,'render':function(Data, type, row, meta){
                return '<a style="text-decoration:none" class="show-detail" onClick="memberOrder.editModal(' + row.id + ')" href="javascript:;" title="查看订单">'+Data+'</a>';
            }},
            {"data": 'short_mark',"sTitle":'备注', 'sClass':'text-c','orderable':false,'render':function(Data, type, row, meta) {
                if(Data.indexOf('...') > 0)
                {
                    return  '<td><a style="text-decoration:none" class="show-detail" onClick="memberOrder.editModal(' + row.id + ')" href="javascript:;" title="查看订单">'+Data+'</a></td><button class="btn btn-secondary radius size-MINI" onclick="showContent(\''+row.mark+'\')">详情</button>';
                } else {
                    return '<a style="text-decoration:none" class="show-detail" onClick="memberOrder.editModal(' + row.id + ')" href="javascript:;" title="查看订单">'+Data+'</a>';
                }
            }},
            {
                "sTitle":'操作',
                "mDataProp": "id",
                'sClass':'td-manage td-icon',
                'orderable':false,
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {//参数：DOM, id, object对象, 当前所在行,当前所在列
                    var html =
                        '<a style="text-decoration:none" onClick="memberOrder.editModal(' + oData.id + ')" href="javascript:;" title="查看订单"><i class="fa fa-pencil"></i></a>'+
                        '<a style="text-decoration:none" onClick="memberOrder.deleteModal(' + oData.id + ',\''+oData.order_number+'\')" href="javascript:;" title="删除"><i class="fa fa-remove"></i></a>';

                    if(oData.final_payment > '0') {
                        html += '<a style="text-decoration:none" onClick="memberOrder.secondModal(\'' + oData.order_number + '\')" href="javascript:;" title="尾款"><i class="fa fa-chain"></i></a>';
                    }
                        html += '<a style="text-decoration:none" onClick="memberOrder.uploadImage(' + oData.id + ')" href="javascript:;" title="上传图片"><i class="fa fa-upload"></i></a>';
                        html += '<a style="text-decoration:none" onClick="memberOrder.refundModal(' + oData.id + ')" href="javascript:;" title="退款"><i class="fa fa-reply-all"></i></a>';
                    if(oData.order_type == '普通套系') {
                        html += '<a style="text-decoration:none" onClick="memberOrder.planModal(\'' + oData.combo_order_number + '\')" href="javascript:;" title="日历排项"><i class="fa fa fa-wpforms"></i></a>';
                    }
                    $(nTd).addClass('center').html(html);
                }
            },
        ],
    },

    //会员挂单列表
    memberOrderGuaList : {
        columns:[
            {"data": 'id',"sTitle":'序号','orderable':false,'render':function(Data, type, row, meta){
                    return '<a style="text-decoration:none" class="show-detail" onClick="memberOrder.editModal(' + row.id + ')" href="javascript:;" title="查看订单">'+Data+'</a>';
                }},
            {"data": 'member_id',"sTitle":'宝宝姓名','orderable':false,'render':function(Data, type, row, meta){
                    return '<a style="text-decoration:none" class="show-detail" onClick="memberOrder.editModal(' + row.id + ')" href="javascript:;" title="查看订单">'+Data+'</a>';
                }},
            {"data": 'order_number',"sTitle":'订单编号','orderable':false,'render':function(Data, type, row, meta){
                    return '<a style="text-decoration:none" class="show-detail" onClick="memberOrder.editModal(' + row.id + ')" href="javascript:;" title="查看订单">'+Data+'</a>';
                }},

            {"data": 'order_type',"sTitle":'套系类型','orderable':true,'render':function(Data, type, row, meta){
                    // return '<a style="text-decoration:none" class="show-detail" onClick="memberOrder.editModal(' + row.id + ')" href="javascript:;" title="查看订单">'+Data+'</a>';
                    // var event = '<a style="text-decoration:none" class="show-detail" href="javascript:;" title="查看订单">'+Data+'</a>';
                    // var eventObj = $(event);
                    // eventObj.click(function(){
                    //     addRow(row);
                    // });
                    // return event;
                    if(row.order_type == '成长套系') {
                        return '<a style="text-decoration:none" class="show-detail" onClick="addRow(this,\'' + row.order_number + '\')" href="javascript:;" title="查看订单">'+Data+'</a>';
                    } else {
                        return '<a style="text-decoration:none" class="show-detail" onClick="memberOrder.editModal(' + row.id + ')" href="javascript:;" title="查看订单">'+Data+'</a>';
                    }
                }},

            {"data": 'combo_price',"sTitle":'套系价格','orderable':false,'render':function(Data, type, row, meta){
                    return '<a style="text-decoration:none" class="show-detail" onClick="memberOrder.editModal(' + row.id + ')" href="javascript:;" title="查看订单">'+Data+'</a>';
                }},

            {"data": 'discount',"sTitle":'折扣','orderable':false,'render':function(Data, type, row, meta){
                    return '<a style="text-decoration:none" class="show-detail" onClick="memberOrder.editModal(' + row.id + ')" href="javascript:;" title="查看订单">'+Data+'</a>';
                }},

            {"data": 'integral',"sTitle":'积分','orderable':false,'render':function(Data, type, row, meta){
                    return '<a style="text-decoration:none" class="show-detail" onClick="memberOrder.editModal(' + row.id + ')" href="javascript:;" title="查看订单">'+Data+'</a>';
                }},

            {"data": 'price',"sTitle":'成单价','orderable':false,'render':function(Data, type, row, meta){
                    return '<a style="text-decoration:none" class="show-detail" onClick="memberOrder.editModal(' + row.id + ')" href="javascript:;" title="查看订单">'+Data+'</a>';
                }},
            {"data": 'total_money',"sTitle":'实收','orderable':true,'render':function(Data, type, row, meta){
                    return '<a style="text-decoration:none" class="show-detail" onClick="memberOrder.editModal(' + row.id + ')" href="javascript:;" title="查看订单">'+Data+'</a>';
                }},
            {"data": 'earnest',"sTitle":'定金','orderable':false,'render':function(Data, type, row, meta){
                    return '<a style="text-decoration:none" class="show-detail" onClick="memberOrder.editModal(' + row.id + ')" href="javascript:;" title="查看订单">'+Data+'</a>';
                }},
            // {"data": 'number',"sTitle":'数量','orderable':false,'render':function(Data, type, row, meta){
            //     return '<a style="text-decoration:none" class="show-detail" onClick="memberOrder.editModal(' + row.id + ')" href="javascript:;" title="查看订单">'+Data+'</a>';
            // }},

            {"data": 'final_payment',"sTitle":'尾款','orderable':true,'render':function(Data, type, row, meta){
                    return '<a style="text-decoration:none" class="show-detail" onClick="memberOrder.editModal(' + row.id + ')" href="javascript:;" title="查看订单">'+Data+'</a>';
                }},
            // {"data": 'order_type',"sTitle":'','orderable':false},
            {"data": 'create_time',"sTitle":'签约时间','orderable':true,'render':function(Data, type, row, meta){
                    return '<a style="text-decoration:none" class="show-detail" onClick="memberOrder.editModal(' + row.id + ')" href="javascript:;" title="查看订单">'+Data+'</a>';
                }},
            {"data": 'short_mark',"sTitle":'备注', 'sClass':'text-c','orderable':false,'render':function(Data, type, row, meta) {
                    if(Data.indexOf('...') > 0)
                    {
                        return  '<td><a style="text-decoration:none" class="show-detail" onClick="memberOrder.editModal(' + row.id + ')" href="javascript:;" title="查看订单">'+Data+'</a></td><button class="btn btn-secondary radius size-MINI" onclick="showContent(\''+row.mark+'\')">详情</button>';
                    } else {
                        return '<a style="text-decoration:none" class="show-detail" onClick="memberOrder.editModal(' + row.id + ')" href="javascript:;" title="查看订单">'+Data+'</a>';
                    }
                }},
            {
                "sTitle":'操作',
                "mDataProp": "id",
                'sClass':'td-manage td-icon',
                'orderable':false,
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {//参数：DOM, id, object对象, 当前所在行,当前所在列
                    var ht =
                        '<a style="text-decoration:none" onClick="memberOrder.editModal(' + oData.id + ')" href="javascript:;" title="付款"><i class="fa fa-pencil"></i></a>'+
                        '<a style="text-decoration:none" onClick="memberOrder.deleteModal(' + oData.id + ',\''+oData.order_number+'\')" href="javascript:;" title="删除"><i class="fa fa-remove"></i></a>';

                    $(nTd).addClass('center').html(ht);
                }
            },
        ],
    },


    //会员订单套系列表
    memberOrderComboList : {
        columns:[
            // {"data": 'id',"sTitle":'序号','orderable':false},
            {"data": 'combo_order_number',"sTitle":'套系订单编号','orderable':false},
            {"data": 'member_name',"sTitle":'宝宝姓名','orderable':false},
            {"data": 'combo_name',"sTitle":'套系名称','orderable':false},
            {"data": 'price',"sTitle":'成单价','orderable':false},
            {"data": 'discount',"sTitle":'折扣','orderable':false},
            {"data": 'integral',"sTitle":'积分','orderable':false},
            {"data": 'plan_status',"sTitle":'排项','orderable':false,'render':function(Data, type, row, meta){
                if(!Data){
                    return '--';
                } else {
                    return '<span class="label label-primary radius">'+Data+'</span>';
                }
            }},
            {"data": 'shoot_status',"sTitle":'拍摄','orderable':false,'render':function(Data, type, row, meta){
                if(!Data){
                    return '--';
                } else {
                    return '<span class="label label-secondary radius">'+Data+'</span>';
                }
            }},
            {"data": 'select_status',"sTitle":'选片','orderable':false,'render':function(Data, type, row, meta){
                return '<span class="label label-default radius">'+Data+'</span>';
            }},

            {"data": 'composite_status',"sTitle":'后期','orderable':false,'render':function(Data, type, row, meta){
                if(!Data){
                    return '--';
                } else {
                    return '<span class="label label-danger radius">'+Data+'</span>';
                }
            }},
            {"data": 'deal_status',"sTitle":'理件','orderable':false,'render':function(Data, type, row, meta){
                if(!Data){
                    return '--';
                } else {
                    return '<span class="label label-warning radius">'+Data+'</span>';
                }
            }},
            {"data": 'take_park_status',"sTitle":'取件','orderable':false,'render':function(Data, type, row, meta){
                if(!Data){
                    return '--';
                } else {
                    return '<span class="label label-success radius">'+Data+'</span>';
                }
            }},
            // {"data": 'mark',"sTitle":'备注','orderable':false},
            // {"data": 'create_time',"sTitle":'创建时间','orderable':false},
            {
                "sTitle":'操作',
                "mDataProp": "id",
                'sClass':'td-manage td-icon',
                'orderable':false,
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {//参数：DOM, id, object对象, 当前所在行,当前所在列
                    var html = '<a style="text-decoration:none" onClick="memberOrder.planModal(\'' + oData.combo_order_number + '\')" href="javascript:;" title="日历排项"><i class="fa fa fa-wpforms"></i></a>';
                    if(oData.shoot_status == '已拍摄'){
                        html = '--';
                    }
                    $(nTd).addClass('center').html(html);
                }
            },
        ],
    },

    //会员订单套系全部列表
    memberOrderComboAllList : {
        columns:[
            // {"data": 'id',"sTitle":'序号','orderable':false},
            {"data": 'combo_order_number',"sTitle":'套系订单编号','orderable':false},
            {"data": 'member_name',"sTitle":'宝宝姓名','orderable':false},
            {"data": 'combo_name',"sTitle":'套系名称','orderable':false},
            {"data": 'price',"sTitle":'成单价','orderable':false},
            {"data": 'integral',"sTitle":'积分','orderable':false},
            {"data": 'plan_status',"sTitle":'排项','orderable':true,'render':function(Data, type, row, meta){
                if(!Data){
                    return '--';
                } else {
                    return '<span class="label label-primary radius">'+Data+'</span>';
                }
            }},
            {"data": 'shoot_status',"sTitle":'拍摄','orderable':true,'render':function(Data, type, row, meta){
                if(!Data){
                    return '--';
                } else {
                    return '<span class="label label-secondary radius">'+Data+'</span>';
                }
            }},
            {"data": 'select_status',"sTitle":'选片','orderable':true,'render':function(Data, type, row, meta){
                return '<span class="label label-default radius">'+Data+'</span>';
            }},

            {"data": 'composite_status',"sTitle":'后期','orderable':true,'render':function(Data, type, row, meta){
                if(!Data){
                    return '--';
                } else {
                    return '<span class="label label-danger radius">'+Data+'</span>';
                }
            }},
            {"data": 'deal_status',"sTitle":'理件','orderable':true,'render':function(Data, type, row, meta){
                if(!Data){
                    return '--';
                } else {
                    return '<span class="label label-warning radius">'+Data+'</span>';
                }
            }},
            {"data": 'take_park_status',"sTitle":'取件','orderable':true,'render':function(Data, type, row, meta){
                if(!Data){
                    return '--';
                } else {
                    return '<span class="label label-success radius">'+Data+'</span>';
                }
            }},
            {"data": 'create_time',"sTitle":'签约时间','orderable':true},
            // {"data": 'mark',"sTitle":'备注','orderable':false},
            // {"data": 'create_time',"sTitle":'创建时间','orderable':false},
            {
                "sTitle":'操作',
                "mDataProp": "id",
                'sClass':'td-manage td-icon',
                'orderable':false,
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {//参数：DOM, id, object对象, 当前所在行,当前所在列
                    var html = '<a style="text-decoration:none" onClick="memberComboOrder.planModal(\'' + oData.combo_order_number + '\')" href="javascript:;" title="日历排项"><i class="fa fa fa-wpforms"></i></a>';
                    if(oData.shoot_status == '已拍摄'){
                        html = '--';
                    }
                    $(nTd).addClass('center').html(html);
                }
            },
        ],
    },

    //未拍摄列表
    notShootOrderList : {
        columns:[
            {"data": 'id',"sTitle":'序号','orderable':false},
            {"data": 'combo_order_number',"sTitle":'套系订单编号','orderable':false},
            {"data": 'member_name',"sTitle":'会员','orderable':false},
            {"data": 'combo_name',"sTitle":'套系名称','orderable':false},
            {"data": 'price',"sTitle":'价格','orderable':false},
            {"data": 'integral',"sTitle":'积分','orderable':false},
            {"data": 'plan_status',"sTitle":'排项状态','orderable':true,'render':function(Data, type, row, meta){
                if(!Data){
                    return '--';
                } else {
                    return '<span class="label label-primary radius">'+Data+'</span>';
                }
            }},
            {"data": 'shoot_status',"sTitle":'拍摄状态','orderable':true,'render':function(Data, type, row, meta){
                if(!Data){
                    return '--';
                } else {
                    return '<span class="label label-secondary radius">'+Data+'</span>';
                }
            }},
            {"data": 'shoot_user',"sTitle":'摄影师','orderable':false},
            {"data": 'create_time',"sTitle":'签约时间','orderable':true},
            {
                "sTitle":'操作',
                "mDataProp": "id",
                'sClass':'td-manage td-icon',
                'orderable':false,
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {//参数：DOM, id, object对象, 当前所在行,当前所在列
                    var html = '';
                    if(oData.shoot_status == '未拍摄') {
                        html += '<a style="text-decoration:none" onClick="notShoot.startShootModal(\'' + oData.combo_order_number + '\')" href="javascript:;" title="开始拍摄"><i class="fa fa-hand-o-up"></i></a>';
                    }
                    if(oData.shoot_status == '已拍摄' || oData.shoot_status == '拍摄中'){
                        html += '<a style="text-decoration:none" onClick="notShoot.endShootModal(\'' + oData.combo_order_number + '\')" href="javascript:;" title="拍摄完成"><i class="fa fa-hand-rock-o"></i></a>';
                    }

                    if(oData.shoot_status == '拍摄中' || oData.plan_status == '未拍完') {
                        html += '<a style="text-decoration:none" onClick="notShoot.notFinished(\'' + oData.combo_order_number + '\')" href="javascript:;" title="未完改期"><i class="fa fa-hourglass-half"></i></a>';
                    }

                    html += '<a style="text-decoration:none" onClick="notShoot.planModal(\'' + oData.combo_order_number + '\')" href="javascript:;" title="日历排项"><i class="fa fa-wpforms"></i></a>';
                    $(nTd).addClass('center').html(html);
                }
            }
        ]
    },

    //未完改期
    replanOrder: {
        columns:[
            {"data": 'id',"sTitle":'序号','orderable':false},
            {"data": 'combo_order_number',"sTitle":'套系订单编号','orderable':false},
            {"data": 'member_name',"sTitle":'会员','orderable':false},
            {"data": 'combo_name',"sTitle":'套系名称','orderable':false},
            {"data": 'price',"sTitle":'价格','orderable':false},
            {"data": 'integral',"sTitle":'积分','orderable':false},
            {"data": 'plan_status',"sTitle":'排项状态','orderable':true,'render':function(Data, type, row, meta){
                if(!Data){
                    return '--';
                } else {
                    return '<span class="label label-primary radius">'+Data+'</span>';
                }
            }},
            {"data": 'shoot_status',"sTitle":'拍摄状态','orderable':true,'render':function(Data, type, row, meta){
                if(!Data){
                    return '--';
                } else {
                    return '<span class="label label-secondary radius">'+Data+'</span>';
                }
            }},
            {"data": 'shoot_user',"sTitle":'摄影师','orderable':false},
            {"data": 'create_time',"sTitle":'签约时间','orderable':true},
            {
                "sTitle":'操作',
                "mDataProp": "id",
                'sClass':'td-manage td-icon',
                'orderable':false,
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {//参数：DOM, id, object对象, 当前所在行,当前所在列
                    var html = '';
                    if(oData.shoot_status == '未拍完') {
                        html += '<a style="text-decoration:none" onClick="replan(\'' + oData.combo_order_number + '\')" href="javascript:;" title="重新排项"><i class="fa fa-wpforms"></i></a>';
                    }
                    html += '<a style="text-decoration:none" onClick="notShoot.endShootModal(\'' + oData.combo_order_number + '\')" href="javascript:;" title="拍摄完成"><i class="fa fa-hourglass-end"></i></a>';
                    $(nTd).addClass('center').html(html);
                }
            },
        ],
    },

    //未选片列表
    notSelectOrderList : {
        columns:[
            {"data": 'id',"sTitle":'序号','orderable':false},
            {"data": 'combo_order_number',"sTitle":'套系订单编号','orderable':false},
            {"data": 'member_name',"sTitle":'会员','orderable':false},
            {"data": 'combo_name',"sTitle":'套系名称','orderable':false},
            {"data": 'price',"sTitle":'价格','orderable':false},
            {"data": 'integral',"sTitle":'积分','orderable':false},
            {"data": 'shoot_status',"sTitle":'拍摄状态','orderable':true,'render':function(Data, type, row, meta){
                if(!Data){
                    return '--';
                } else {
                    return '<span class="label label-secondary radius">'+Data+'</span>';
                }
            }},
            {"data": 'shoot_finish_time',"sTitle":'拍摄完成时间','orderable':true},
            {"data": 'select_photos_user',"sTitle":'选片师','orderable':false,'render':function(Data, type, row, meta){
                if(!Data){
                    return '--';
                } else {
                    return '<span class="label label-success radius">'+Data+'</span>';
                }
            }},
            {"data": 'select_status',"sTitle":'选片状态','orderable':true,'render':function(Data, type, row, meta){
                return '<span class="label label-primary radius">'+Data+'</span>';
            }},
            {"data": 'create_time',"sTitle":'签约时间','orderable':true},
            {
                "sTitle":'操作',
                "mDataProp": "id",
                'sClass':'td-manage td-icon',
                'orderable':false,
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {//参数：DOM, id, object对象, 当前所在行,当前所在列
                    var html = '';
                    if(oData.select_status == '未选片') {
                        html = '<a style="text-decoration:none" onClick="notSelect.startSelectModal(\'' + oData.combo_order_number + '\')" href="javascript:;" title="开始选片"><i class="fa fa-hand-o-up"></i></a>';
                    }
                    if(oData.select_status == '选片中'){
                        html = '<a style="text-decoration:none" onClick="notSelect.continueSelectModal(\'' + oData.combo_order_number + '\')" href="javascript:;" title="重新选片"><i class="fa fa-hand-paper-o"></i></a>' +
                            '<a style="text-decoration:none" onClick="notSelect.endSelectModal(\'' + oData.combo_order_number + '\')" href="javascript:;" title="选片完成"><i class="fa fa-hand-rock-o"></i></a>';
                    }
                    html += '<a style="text-decoration:none" onClick="notSelect.secondModal(\'' + oData.order_number + '\')" href="javascript:;" title="二销售款"><i class="fa fa-chain"></i></a>';
                    $(nTd).addClass('center').html(html);
                }
            },
        ],
    },


    //后期处理列表
    notCompositeOrderList : {
        columns:[
            {"data": 'id',"sTitle":'序号','orderable':false},
            {"data": 'combo_order_number',"sTitle":'套系订单编号','orderable':false},
            {"data": 'member_name',"sTitle":'会员','orderable':false},
            {"data": 'combo_name',"sTitle":'套系名称','orderable':false},
            // {"data": 'price',"sTitle":'价格','orderable':false},
            // {"data": 'discount',"sTitle":'折扣','orderable':false},
            // {"data": 'integral',"sTitle":'积分','orderable':false},
            {"data": 'shoot_finish_time',"sTitle":'拍摄完成时间','orderable':true},
            {"data": 'select_time',"sTitle":'选片时间','orderable':true},
            {"data": 'select_photos_user',"sTitle":'选片师','orderable':false,'render':function(Data, type, row, meta){
                if(!Data){
                    return '--';
                } else {
                    return '<span class="label label-secondary radius">'+Data+'</span>';
                }
            }},
            {"data": 'composite_user',"sTitle":'后期师','orderable':false,'render':function(Data, type, row, meta){
                if(!Data){
                    return '--';
                } else {
                    return '<span class="label label-success radius">'+Data+'</span>';
                }
            }},
            {"data": 'composite_status',"sTitle":'阶段','orderable':true,'render':function(Data, type, row, meta){
                return '<span class="label label-primary radius">'+Data+'</span>';
            }},
            {"data": 'create_time',"sTitle":'签约时间','orderable':true},
            {
                "sTitle":'操作',
                "mDataProp": "id",
                'sClass':'td-manage td-icon',
                'orderable':false,
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {//参数：DOM, id, object对象, 当前所在行,当前所在列
                    var html = '';
                    if(oData.composite_status == '未处理') {
                        html = '<a style="text-decoration:none" onClick="notComposite.designModal(\'' + oData.combo_order_number + '\')" href="javascript:;" title="精修"><i class="fa fa-hand-o-up"></i></a>';
                    }
                    if(oData.composite_status == '精修') {
                        html = '<a style="text-decoration:none" onClick="notComposite.truingModal(\'' + oData.combo_order_number + '\')" href="javascript:;" title="设计"><i class="fa fa-hand-o-right"></i></a>';
                        html += '<a style="text-decoration:none" onClick="notComposite.downloadImages(\'' + oData.combo_order_number + '\')" href="javascript:;" title="下载图片"><i class="fa fa-download"></i></a>';
                    }
                    if(oData.composite_status == '设计') {
                        html = '<a style="text-decoration:none" onClick="notComposite.goBackModal(\'' + oData.combo_order_number + '\')" href="javascript:;" title="返厂"><i class="fa fa-hand-o-left"></i></a>';
                    }
                    // if(oData.composite_status == '已发厂家') {
                    html += '<a style="text-decoration:none" onClick="notComposite.doneModal(\'' + oData.combo_order_number + '\')" href="javascript:;" title="后期完成"><i class="fa fa-hand-rock-o"></i></a>';
                    // }
                    $(nTd).addClass('center').html(html);
                }
            },
        ],
    },

    //成品理件列表
    notDealOrderList : {
        columns:[
            {"data": 'id',"sTitle":'序号','orderable':false},
            {"data": 'combo_order_number',"sTitle":'套系订单编号','orderable':false},
            {"data": 'member_name',"sTitle":'会员','orderable':false},
            {"data": 'combo_name',"sTitle":'套系名称','orderable':false},
            // {"data": 'price',"sTitle":'价格','orderable':false},
            // {"data": 'discount',"sTitle":'折扣','orderable':false},
            // {"data": 'integral',"sTitle":'积分','orderable':false},
            {"data": 'shoot_finish_time',"sTitle":'拍摄完成时间','orderable':true},
            {"data": 'select_time',"sTitle":'选片时间','orderable':true},
            {"data": 'composite_user',"sTitle":'后期师','orderable':false,'render':function(Data, type, row, meta){
                if(!Data){
                    return '--';
                } else {
                    return '<span class="label label-secondary radius">'+Data+'</span>';
                }
            }},
            {"data": 'deal_user',"sTitle":'理件师','orderable':false,'render':function(Data, type, row, meta){
                if(!Data){
                    return '--';
                } else {
                    return '<span class="label label-success radius">'+Data+'</span>';
                }
            }},
            {"data": 'deal_status',"sTitle":'理件','orderable':true,'render':function(Data, type, row, meta){
                return '<span class="label label-primary radius">'+Data+'</span>';
            }},

            {"data": 'take_park_status',"sTitle":'取件','orderable':true,'render':function(Data, type, row, meta){
                if(!Data){
                    return '--';
                } else {
                    return '<span class="label label-warning radius">'+Data+'</span>';
                }
            }},

            {"data": 'create_time',"sTitle":'签约时间','orderable':true},
            {
                "sTitle":'操作',
                "mDataProp": "id",
                'sClass':'td-manage td-icon',
                'orderable':false,
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {//参数：DOM, id, object对象, 当前所在行,当前所在列
                    var html = '';
                    if(oData.deal_status !== '已理件') {
                        html = '<a style="text-decoration:none" onClick="notDeal.quoteDealModal(\'' + oData.combo_order_number + '\')" href="javascript:;" title="理件"><i class="fa fa-hand-o-up"></i></a>';
                    }

                    if(oData.deal_status == '已理件') {
                        if(oData.take_park_status == '未通知') {
                            html = '<a style="text-decoration:none" onClick="notDeal.giveNoticeModal(\'' + oData.combo_order_number + '\')" href="javascript:;" title="通知取件"><i class="fa fa-volume-control-phone"></i></a>';
                        }

                        if(oData.take_park_status == '通知(未取件)') {
                            html = '<a style="text-decoration:none" onClick="notDeal.doneModal(\'' + oData.combo_order_number + '\')" href="javascript:;" title="完成取件"><i class="fa fa-check"></i></a>';
                        }
                    }
                    $(nTd).addClass('center').html(html);
                }
            },
        ],
    },

    //成品理件列表
    memberOrderDetailList : {
        columns:[
            {"data": 'id',"sTitle":'序号','orderable':false},
            {"data": 'combo_order_number',"sTitle":'订单套系编号','orderable':false},
            {"data": 'goods_code',"sTitle":'商品编号','orderable':false},
            {"data": 'goods_name',"sTitle":'名称','orderable':false},
            {"data": 'goods_category',"sTitle":'分类','orderable':false},
            {"data": 'goods_color',"sTitle":'颜色','orderable':false},
            {"data": 'goods_size',"sTitle":'尺寸','orderable':false},
            {"data": 'goods_texture',"sTitle":'材质','orderable':false},
            {"data": 'goods_style',"sTitle":'风格','orderable':false},
            {"data": 'goods_type',"sTitle":'类型','orderable':false},

            {"data": 'deal_status',"sTitle":'状态','orderable':false,'render':function(Data, type, row, meta){
                return '<span class="label label-primary radius">'+Data+'</span>';
            }},
            {
                "sTitle":'操作',
                "mDataProp": "id",
                'sClass':'td-manage td-icon',
                'orderable':false,
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {//参数：DOM, id, object对象, 当前所在行,当前所在列
                    var html = '';
                    if(oData.deal_status == '未理件') {
                        html = '<a style="text-decoration:none" onClick="orderDetail.goBackModal(' + oData.id + ')" href="javascript:;" title="返厂"><i class="fa fa-hand-o-right"></i></a>'+
                            '<a style="text-decoration:none" onClick="orderDetail.doneModal(' + oData.id + ')" href="javascript:;" title="理件"><i class="fa fa-hand-rock-o"></i></a>';
                    }
                    if(oData.deal_status == '返厂') {
                        html = '<a style="text-decoration:none" onClick="orderDetail.doneModal(' + oData.id + ')" href="javascript:;" title="理件"><i class="fa fa-hand-rock-o"></i></a>';
                    }
                    $(nTd).addClass('center').html(html);
                }
            },
        ],
    },

    //员工提成列表
    employeeRateList : {
        columns:[
            {"data": 'id',"sTitle":'序号','orderable':false},
            {"data": 'employee_id',"sTitle":'员工','orderable':false},
            {"data": 'rate_money',"sTitle":'提成金额','orderable':false},
            {"data": 'rate_type',"sTitle":'提成类型','orderable':false},
            {"data": 'create_time',"sTitle":'创建时间','orderable':false},
            {"data": 'mark',"sTitle":'备注','orderable':false},
            {
                "sTitle":'操作',
                "mDataProp": "id",
                'sClass':'td-manage td-icon',
                'orderable':false,
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {//参数：DOM, id, object对象, 当前所在行,当前所在列
                    var html = '<a style="text-decoration:none" class="status" onClick="memberRate.editModal(' + oData.id + ')" href="javascript:;" title="编辑提成"><i class="fa fa-pencil"></i></a>';
                    $(nTd).addClass('center').html(html);
                }
            },
        ],
    },

    //简历列表
    resumeList : {
        columns:[
            {"data": 'id',"sTitle":'序号','orderable':false},
            {"data": 'name',"sTitle":'姓名','orderable':false},
            {"data": 'resume_title',"sTitle":'标题','orderable':false},
            {"data": 'create_time',"sTitle":'创建时间','orderable':false},
            {"data": 'update_time',"sTitle":'更新时间','orderable':false},
            {"data": 'is_default',"sTitle":'默认','sClass':'td-status','orderable':false,'render':function(Data, type, row, meta){
                return '<span class="label radius">'+Data+'</span>';
            }},
            {"data": 'check_status_name',"sTitle":'审核状态','orderable':false,'render':function(Data, type, row, meta){
                return '<span class="label label-warning radius">'+Data+'</span>';
            }},
            {
                "sTitle":'操作',
                "mDataProp": "id",
                'sClass':'td-manage td-icon',
                'orderable':false,
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {//参数：DOM, id, object对象, 当前所在行,当前所在列
                    var html = '<a style="text-decoration:none" onClick="resumeList.editModal(' + oData.id + ')" href="javascript:;" title="编辑"><i class="fa fa-pencil"></i></a>'+
                        '<a style="text-decoration:none" class="status" onClick="resumeList.defaultModal(this,'+ oData.id + ')" href="javascript:;" title="'+oData.is_default+'"><i class="Hui-iconfont"></i>'+
                        '<a style="text-decoration:none" class="status" onClick="resumeList.deleteModal(' + oData.id + ')" href="javascript:;" title="删除"><i class="fa fa-remove"></i></a>';
                    $(nTd).addClass('center').html(html);
                }
            },
        ],
    },

    recruitComboList : {
        columns:[
            {"data": 'id',"sTitle":'序号','orderable':false},
            {"data": 'combo_name',"sTitle":'套餐名称','orderable':false},
            {"data": 'vaild_days',"sTitle":'有效天数','orderable':false},
            {"data": 'origin_price',"sTitle":'原价','orderable':false},
            {"data": 'discount_price',"sTitle":'折扣价','orderable':false},
            {"data": 'mark',"sTitle":'备注','orderable':false},
            {
                "sTitle":'操作',
                "mDataProp": "id",
                'sClass':'td-manage td-icon',
                'orderable':false,
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {//参数：DOM, id, object对象, 当前所在行,当前所在列
                    var html = '<a style="text-decoration:none" class="status" onClick="editRecruitCombo(' + oData.id + ')" href="javascript:;" title="编辑提成"><i class="fa fa-pencil"></i></a>';
                    $(nTd).addClass('center').html(html);
                }
            },
        ],
    },

    //招聘职位
    recruitPostList : {
        columns:[
            {"data": 'id',"sTitle":'序号','orderable':false},
            {"data": 'recruit_title',"sTitle":'标题','orderable':false,'width':'20%',},
            {"data": 'address',"sTitle":'地址','orderable':false,'width':'20%',},
            {"data": 'expected_salary',"sTitle":'期望薪资','orderable':false},
            {"data": 'working_duration',"sTitle":'工作年限','orderable':false},
            {"data": 'degree',"sTitle":'学历','orderable':false},
            {"data": 'post_id',"sTitle":'职位','orderable':false},
            // {"data": 'shop_introduced',"sTitle":'店铺介绍','orderable':false},
            // {"data": 'job_specification',"sTitle":'任职要求','orderable':false},
            {"data": 'create_time',"sTitle":'创建时间','orderable':false},
            {"data": 'check_status_name',"sTitle":'审核状态','orderable':false,'render':function(Data, type, row, meta){
                return '<span class="label label-warning radius">'+Data+'</span>';
            }},
            {
                "sTitle":'操作',
                "mDataProp": "id",
                'sClass':'td-manage td-icon',
                'orderable':false,
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {//参数：DOM, id, object对象, 当前所在行,当前所在列
                    var html = '<a style="text-decoration:none" onClick="recruitPostList.editModal(' + oData.id + ')" href="javascript:;" title="编辑"><i class="fa fa-pencil"></i></a>'+
                        '<a style="text-decoration:none" class="status" onClick="recruitPostList.endModal(' + oData.id + ')" href="javascript:;" title="结束招聘"><i class="fa fa-remove"></i></a>'+
                        '<a style="text-decoration:none" class="status" onClick="recruitPostList.scanResumeModal(' + oData.id + ')" href="javascript:;" title="查看简历"><i class="fa fa-tasks"></i></a>';
                    $(nTd).addClass('center').html(html);
                }
            },
        ],
    },

    //guest显示招聘职位
    guestPostList : {
        columns:[
            // {"data": 'id',"sTitle":'序号','orderable':false},
            {"data": 'recruit_title',"sTitle":'标题','orderable':false,'width':'20%','render':function(Data, type, row, meta){
                return '<a href="javascript:;" onClick="guestPostList.showModal(' + row.id + ')">'+Data+'</a>';
            }},
            {"data": 'address',"sTitle":'地址','orderable':false,'width':'20%',},
            {"data": 'expected_salary',"sTitle":'期望薪资','orderable':false},
            {"data": 'working_duration',"sTitle":'工作年限','orderable':false},
            {"data": 'degree',"sTitle":'学历','orderable':false},
            {"data": 'post_id',"sTitle":'职位','orderable':false},
            // {"data": 'shop_introduced',"sTitle":'店铺介绍','orderable':false},
            // {"data": 'job_specification',"sTitle":'任职要求','orderable':false},
            {"data": 'create_time',"sTitle":'创建时间','orderable':false},
            {
                "sTitle":'操作',
                "mDataProp": "id",
                'sClass':'td-manage td-icon',
                'orderable':false,
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {//参数：DOM, id, object对象, 当前所在行,当前所在列
                    var html = '<a style="text-decoration:none" onClick="guestPostList.applyModal(this,' + oData.id + ')" href="javascript:void(0);" title="申请职位"><i class="fa fa-heart-o"></i></a>';
                    if(oData.is_send) {
                        html = '<a style="text-decoration:none" onClick="already()" href="javascript:void(0);" title="已投递"><i class="fa fa-heart"></i></a>';
                    }
                    $(nTd).addClass('center').html(html);
                }
            },
        ],
    },

    //职位对应的简历标题
    resumeForRecruitPostList : {
        columns:[
            // {"data": 'id',"sTitle":'序号','orderable':false},
            // {"data": 'recruit_title',"sTitle":'标题','orderable':false,'render':function(Data, type, row, meta){
            //     return '<a href="javascript:;" onClick="guestPostList.showModal(' + row.id + ')">'+Data+'</a>';
            // }},
            // {"data": 'title',"sTitle":'简历','orderable':false},
            {"data": 'title',"sTitle":'简历','orderable':false,'render':function(Data, type, row, meta){
                return '<a href="javascript:;" onClick="resumeForRecruitPost.showModal(' + row.id + ')">'+Data+'</a>';
            }},
            {"data": 'name',"sTitle":'姓名','orderable':false},
            {"data": 'address',"sTitle":'地址','orderable':false},
            {"data": 'expected_salary',"sTitle":'期望薪资','orderable':false},
            {"data": 'working_status',"sTitle":'工作状态','orderable':false},
            {"data": 'working_duration',"sTitle":'工作年限','orderable':false},
            {"data": 'degree',"sTitle":'学历','orderable':false},
            // {"data": 'post_id',"sTitle":'职位','orderable':false},
            // {"data": 'shop_introduced',"sTitle":'店铺介绍','orderable':false},
            // {"data": 'job_specification',"sTitle":'任职要求','orderable':false},
            {"data": 'create_time',"sTitle":'投递时间','orderable':false},
            {
                "sTitle":'操作',
                "mDataProp": "id",
                'sClass':'td-manage td-icon',
                'orderable':false,
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {//参数：DOM, id, object对象, 当前所在行,当前所在列
                    var html = '<a style="text-decoration:none" onClick="resumeForRecruitPost.inviteModal(this,' + oData.id + ')" href="javascript:void(0);" title="邀请面试"><i class="fa fa-heart-o"></i></a>';
                    if(oData.is_invite) {
                        html = '<a style="text-decoration:none" onClick="already()" href="javascript:void(0);" title="已邀请"><i class="fa fa-heart"></i></a>';
                    }
                    $(nTd).addClass('center').html(html);
                }
            },
        ],
    },

    //面试邀请列表
    resumeInvitationList : {
        columns:[
            {"data": 'recruit_title',"sTitle":'招聘标题','orderable':false,'width':'20%','render':function(Data, type, row, meta){
                return '<a href="javascript:;" onClick="resumeInvitation.showRecruitModal(' + row.recruit_post_id + ')">'+Data+'</a>';
            }},
            {"data": 'recruit_time',"sTitle":'招聘发布时间','orderable':false},
            {"data": 'resume_title',"sTitle":'简历标题','orderable':false,'render':function(Data, type, row, meta){
                return '<a href="javascript:;" onClick="resumeInvitation.showResumeModal(' + row.id + ')">'+Data+'</a>';
            }},
            {"data": 'resume_time',"sTitle":'投递时间','orderable':false},
            {"data": 'name',"sTitle":'姓名','orderable':false},
            {"data": 'tel',"sTitle":'电话','orderable':false},
            {"data": 'working_duration',"sTitle":'工作年限','orderable':false},
            {"data": 'degree',"sTitle":'学历','orderable':false},
            {"data": 'is_download',"sTitle":'下载','orderable':false},
            {
                "sTitle":'操作',
                "mDataProp": "id",
                'sClass':'td-manage td-icon',
                'orderable':false,
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {//参数：DOM, id, object对象, 当前所在行,当前所在列
                    // var html = '--';
                    var html = '<a style="text-decoration:none" onClick="resumeInvitation.downModal(' + oData.id + ')" href="javascript:void(0);" title="下载简历"><i class="fa fa-arrow-circle-o-down"></i></a>';
                    $(nTd).addClass('center').html(html);
                }
            },
        ],
    },

    //员工提成日志
    employeeRateLog : {
        columns:[
            {"data": 'id',"sTitle":'序号','orderable':false},
            {"data": 'employee_id',"sTitle":'员工','orderable':false},
            {"data": 'rate_money',"sTitle":'提成金额','orderable':false},
            {"data": 'rate_type',"sTitle":'提成类型','orderable':false},
            {"data": 'create_time',"sTitle":'创建时间','orderable':false},
            {"data": 'mark',"sTitle":'备注','orderable':false},
        ],
    },

    //推荐返利报表
    rebateLog : {
        columns:[
            {"data": 'id',"sTitle":'序号','orderable':false},
            {"data": 'recommend_id',"sTitle":'推荐人','orderable':false},
            {"data": 'target_id',"sTitle":'被推荐人','orderable':false},
            {"data": 'rebate_money',"sTitle":'返利金额','orderable':false},
            {"data": 'create_time',"sTitle":'返利时间','orderable':false},
        ],
    },

    //进客记录
    memberLog : {
        columns:[
            {"data": 'id',"sTitle":'序号','orderable':false},
            {"data": 'name',"sTitle":'姓名','orderable':false},
            {"data": 'sex',"sTitle":'性别','orderable':false},
            {"data": 'age',"sTitle":'年龄','orderable':false},
            {"data": 'tel',"sTitle":'电话','orderable':false},
            {"data": 'source',"sTitle":'来源','orderable':false},
            {"data": 'operate_id',"sTitle":'操作者','orderable':false},
            {"data": 'create_time',"sTitle":'时间','orderable':false},

        ],
    },

    //会员订单记录
    memberOrderLog : {
        columns:[
            {"data": 'id',"sTitle":'序号','orderable':false},
            {"data": 'member_id',"sTitle":'宝宝姓名','orderable':false},
            {"data": 'order_number',"sTitle":'订单编号','orderable':false},
            {"data": 'combo_name',"sTitle":'套系','orderable':false},
            {"data": 'price',"sTitle":'成单价','orderable':false},
            {"data": 'earnest',"sTitle":'定金','orderable':false},
            {"data": 'number',"sTitle":'数量','orderable':false},
            {"data": 'discount',"sTitle":'折扣价','orderable':false},
            // {"data": 'integral',"sTitle":'积分','orderable':false},
            {"data": 'final_payment',"sTitle":'尾款','orderable':false},
            {"data": 'create_time',"sTitle":'创建时间','orderable':false},
        ],
    },

    //会员退单记录
    memberOrderRefundLog : {
        columns:[
            {"data": 'id',"sTitle":'序号','orderable':false},
            {"data": 'member_id',"sTitle":'宝宝姓名','orderable':false},
            {"data": 'order_number',"sTitle":'订单编号','orderable':false},
            {"data": 'combo_name',"sTitle":'套系','orderable':true},
            {"data": 'refund_type',"sTitle":'退款方式','orderable':true},
            {"data": 'refund_integral',"sTitle":'退还积分','orderable':true},
            {"data": 'refund_money',"sTitle":'退款金额','orderable':true},
            // {"data": 'integral',"sTitle":'积分','orderable':false},
            {"data": 'create_time',"sTitle":'退单时间','orderable':true},
            {"data": 'short_reason',"sTitle":'退款原因', 'sClass':'text-c','orderable':false,'render':function(Data, type, row, meta) {
                var sData = Data;
                if(Data.indexOf('...') > 0)
                {
                    sData = '<td>'+Data+'</td><button class="btn btn-secondary radius size-MINI" onclick="showContent(\''+row.refund_reason+'\')">详情</button>';
                }
                return   sData;
            }},
        ],
    },

    //出入库记录
    goodsImportExportLog : {
        columns:[
            {"data": 'id',"sTitle":'序号','orderable':false},
            {"data": 'goods_name',"sTitle":'名称','orderable':false},
            {"data": 'goods_color',"sTitle":'颜色','orderable':false},
            {"data": 'goods_size',"sTitle":'尺寸','orderable':false},
            {"data": 'goods_texture',"sTitle":'材质','orderable':false},
            {"data": 'goods_real_price',"sTitle":'单价','orderable':false},
            {"data": 'operate_num',"sTitle":'数量','orderable':false},
            {"data": 'total_money',"sTitle":'总计','orderable':false},
            {"data": 'operate_type',"sTitle":'类型','orderable':false},
            {"data": 'operate_user_name',"sTitle":'操作者','orderable':false},
            {"data": 'create_time',"sTitle":'操作时间','orderable':false},
        ],
    },

    //简历审核列表
    resumeCheckList : {
        columns:[
            {"data": 'id',"sTitle":'序号','orderable':false},
            {"data": 'name',"sTitle":'姓名','orderable':false},
            {"data": 'resume_title',"sTitle":'标题','orderable':false,'render':function(Data, type, row, meta){
                return '<a href="javascript:;" onClick="resumeCheck.showResumeModal(' + row.id + ')">'+Data+'</a>';
            }},
            {"data": 'create_time',"sTitle":'创建时间','orderable':false},
            {"data": 'update_time',"sTitle":'更新时间','orderable':false},
            {"data": 'check_status_name',"sTitle":'审核状态','orderable':false,'render':function(Data, type, row, meta){
                return '<span class="label label-warning radius">'+Data+'</span>';
            }},
            {
                "sTitle":'操作',
                "mDataProp": "id",
                'sClass':'td-manage td-icon',
                'orderable':false,
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {//参数：DOM, id, object对象, 当前所在行,当前所在列
                    var html = '<a style="text-decoration:none" onClick="resumeCheck.showResumeModal(' + oData.id + ')" href="javascript:;" title="查看"><i class="fa fa-file-word-o"></i></a>';
                    if((oData.check_status == 1) || (oData.check_status == 2)) {
                        html += '<a style="text-decoration:none" class="status" onClick="resumeCheck.checkResumeModel(this,'+ oData.id + ',3)" href="javascript:;" title="通过"><i class="fa fa-check"></i></a>';
                        html += '<a style="text-decoration:none" class="status" onClick="resumeCheck.checkResumeModel(this,' + oData.id + ',4)" href="javascript:;" title="不通过"><i class="fa fa-close"></i></a>';
                    }
                    $(nTd).addClass('center').html(html);
                }
            },
        ],
    },

    //招聘职位审核列表
    recruitPostCheckList : {
        columns:[
            {"data": 'id',"sTitle":'序号','orderable':false},
            {"data": 'business_name',"sTitle":'加盟商','orderable':false},
            {"data": 'recruit_title',"sTitle":'标题','orderable':false,'render':function(Data, type, row, meta){
                return '<a href="javascript:;" onClick="recruitCheck.showRecruitModal(' + row.id + ')">'+Data+'</a>';
            }},
            {"data": 'address',"sTitle":'地址','orderable':false},
            {"data": 'expected_salary',"sTitle":'期望薪资','orderable':false},
            {"data": 'working_duration',"sTitle":'工作年限','orderable':false},
            {"data": 'degree',"sTitle":'学历','orderable':false},
            {"data": 'post_id',"sTitle":'职位','orderable':false},
            // {"data": 'shop_introduced',"sTitle":'店铺介绍','orderable':false},
            // {"data": 'job_specification',"sTitle":'任职要求','orderable':false},
            {"data": 'time',"sTitle":'最后时间','orderable':false},
            {"data": 'check_status_name',"sTitle":'审核状态','orderable':false,'render':function(Data, type, row, meta){
                return '<span class="label label-warning radius">'+Data+'</span>';
            }},
            {
                "sTitle":'操作',
                "mDataProp": "id",
                'sClass':'td-manage td-icon',
                'orderable':false,
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {//参数：DOM, id, object对象, 当前所在行,当前所在列
                    var html = '<a style="text-decoration:none" onClick="recruitCheck.showRecruitModal(' + oData.id + ')" href="javascript:;" title="查看"><i class="fa fa-file-word-o"></i></a>';

                    if((oData.check_status == 1) || (oData.check_status == 2)) {
                        html += '<a style="text-decoration:none" class="status" onClick="recruitCheck.checkRecruitModel(this,'+ oData.id + ',3)" href="javascript:;" title="通过"><i class="fa fa-check"></i></a>';
                        html += '<a style="text-decoration:none" class="status" onClick="recruitCheck.checkRecruitModel(this,' + oData.id + ',4)" href="javascript:;" title="不通过"><i class="fa fa-close"></i></a>';
                    }
                    $(nTd).addClass('center').html(html);
                }
            },
        ],
    },

    //数据备份列表
    exportExcelList : {
        columns:[
            {"data": 'module_team',"sTitle":'一级','orderable':false},
            {"data": 'module_detail',"sTitle":'二级','orderable':false},
            {
                "sTitle":'操作',
                "mDataProp": "id",
                'orderable':false,
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {//参数：DOM, id, object对象, 当前所在行,当前所在列
                    var str = '';
                    str += '<input type="hidden" name="module_team_repeat_num" value="'+oData.module_team_repeat_num+'">';
                    // str += '<a href="javascript:void(0);" class="up btn btn-primary btn-xs" onclick="exportExcel('+ oData.id + ',\''+oData.export_excel_url+'\')" data-id="' + oData.question_id + '">导出excel</a>';
                    str += '<a href="'+oData.export_excel_url+'" class="up btn btn-default btn-xs"  data-id="' + oData.question_id + '">导出excel</a>';
                    $(nTd).addClass('center').html(str);//oData.id,获取json对象里的id
                }
            },
        ],
    },

    //加盟商权限列表
    ABSuppliers:{
        columns:[
            {"data": 'id',"sTitle":'序号','orderable':false,'sClass':'text-c', 'width' : '5%'},
            {"data": 'name',"sTitle":'供应商名称','orderable':false, 'sClass':'text-c', 'width' : '20%'},
            {"data": 'address',"sTitle":'供应商地址','orderable':false, 'sClass':'text-c', 'width' : '20%'},
            {"data": 'tel',"sTitle":'供应商电话','orderable':false, 'sClass':'text-c', 'width' : '20%'},
            {"data": 'link_person',"sTitle":'负责人','orderable':false, 'sClass':'text-c', 'width' : '20%'},
            {
                "sTitle":'操作',
                "mDataProp": "id",
                'sClass':'td-manage td-icon',
                'orderable':false,
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {//参数：DOM, id, object对象, 当前所在行,当前所在列
                    var str = '';
                    str += '<a style="text-decoration:none" onClick="edit(' + oData.id + ')" href="javascript:;" title="查看"><i class="fa fa-pencil"></i></a>';
                    // str += '<a href="javascript:void(0);" class="up btn btn-primary btn-xs" onclick="exportExcel('+ oData.id + ',\''+oData.export_excel_url+'\')" data-id="' + oData.question_id + '">导出excel</a>';
                    str += '<a style="text-decoration:none" onClick="doDelete(' + oData.id + ')" href="javascript:;" title="查看"><i class="fa fa-remove"></i></a>';
                    $(nTd).addClass('center').html(str);//oData.id,获取json对象里的id
                }
            },
        ]
    },


    //总初始化
    initTable: function () {
        $.initTable(this.id, {columns: this.columns, ajax: this.ajax, drawCallback : this.drawCallback, sDom : this.sDom, scrollY : this.scrollY});
    },

    init: function(initTableName, sId, sUrl, oParams) {
        var str = 'this.' + initTableName + '(\'' + sUrl +'\')';
        this.id = sId;
        this.ajax.url = sUrl;
        this.ajax.data = function(d) {
            d.extra_search = oParams;
        };
        eval(str);
        this.initTable();
    },

    reloadTable:function(oParams)
    {
        //先销毁上一次实例化，然后重新初始化
        $(this.id).DataTable().destroy();
        this.ajax.data = function(d) {
            d.extra_search = oParams;
        };
        this.initTable();
    },

    //实现当前页刷新
    drawTable : function()
    {
        $(this.id).DataTable().draw(false);
    },

    //===============================  具体列表初始化  ======================================


    /*
     * 员工列表初始化
     */
    initEmployeeList: function () {
        this.columns = this.employeeList.columns;
        //数据加载完执行的方法
        this.drawCallback = function(settings, json) {
            $('th').removeClass('sorting sorting_asc sorting_desc');
            $('a.status','tbody').each(function(){
                var statusValue = $(this).attr('title');
                switch (statusValue)
                {
                    case '启用':
                        $(this).attr('title','禁用');
                        $(this).children().removeClass('Hui-iconfont-gouxuan').addClass('Hui-iconfont-shenhe-tingyong');
                        $(this).parents("tr").find('span',".td-status").addClass('label-success');
                        break;
                    case '禁用':
                        $(this).attr('title','启用');
                        $(this).children().removeClass('Hui-iconfont-shenhe-tingyong').addClass('Hui-iconfont-gouxuan');
                        $(this).parents("tr").find('span',".td-status").addClass('label-defaunt');
                        break;
                }
            });
        };
    },
    /*
     * 网站公告列表初始化
     */
    initNoticeList: function () {
        this.columns = this.noticeList.columns;
        this.drawCallback = function(settings, json) {
            $('th').removeClass('sorting sorting_asc sorting_desc');
            $('tbody').find('a.btn-warning').each(function(){
                var tabValue = $(this).html();
                switch (tabValue)
                {
                    case '启用':
                        $(this).html("禁用");
                        break;
                    case '禁用':
                        $(this).html("启用");
                        break;

                }

            });
        };
    },
    /*
     * 管理员登录日志列表初始化
     */
    initAdminLoginLogList: function () {
        this.columns = this.adminLoginLogList.columns;
        this.drawCallback = function(settings, json) {
            $('tr').addClass('text-c').find('td').css({'border' : 'none','border-top' : '1px dashed #00CCFF'});
            $('th').removeClass('sorting sorting_asc sorting_desc').css({'border':'none'});
        };
    },

    /*
     * 员工职位列表初始化-总部
     */
    initEmployeePostList: function () {
        this.columns = this.employeePostList.columns;
        this.drawCallback = function(settings, json) {
            $('th').removeClass('sorting sorting_asc sorting_desc');
            $('a.status','tbody').each(function(){
                var statusValue = $(this).attr('title');
                switch (statusValue)
                {
                    case '启用':
                        $(this).attr('title','禁用');
                        $(this).children().removeClass('Hui-iconfont-gouxuan').addClass('Hui-iconfont-shenhe-tingyong');
                        $(this).parents("tr").find('span',".td-status").addClass('label-success');
                        break;
                    case '禁用':
                        $(this).attr('title','启用');
                        $(this).children().removeClass('Hui-iconfont-shenhe-tingyong').addClass('Hui-iconfont-gouxuan');
                        $(this).parents("tr").find('span',".td-status").addClass('label-defaunt');
                        break;
                }
            });
        };
    },

    /*
     * 员工职位列表初始化-加盟商
     */
    initABEmployeePostList: function () {
        this.columns = this.abEmployeePostList.columns;
        this.drawCallback = function(settings, json) {
            $('th').removeClass('sorting sorting_asc sorting_desc');
            $('a.status','tbody').each(function(){
                var statusValue = $(this).attr('title');
                switch (statusValue)
                {
                    case '启用':
                        $(this).attr('title','禁用');
                        $(this).children().removeClass('Hui-iconfont-gouxuan').addClass('Hui-iconfont-shenhe-tingyong');
                        $(this).parents("tr").find('span',".td-status").addClass('label-success');
                        break;
                    case '禁用':
                        $(this).attr('title','启用');
                        $(this).children().removeClass('Hui-iconfont-shenhe-tingyong').addClass('Hui-iconfont-gouxuan');
                        $(this).parents("tr").find('span',".td-status").addClass('label-defaunt');
                        break;
                }
            });
        };
    },

    /*
     * 员工操作列表初始化
     */
    initOperateLogList: function () {
        this.columns = this.operateLogList.columns;
        this.drawCallback = function(settings, json) {
            $('th').removeClass('sorting sorting_asc sorting_desc');
        };
    },
    /*
     * 员工操作列表初始化
     */
    initCoinChangeList: function () {
        this.columns = this.CoinChangeList.columns;
        this.drawCallback = function(settings, json) {
            $('th').removeClass('sorting sorting_asc sorting_desc');
        };
    },

    /*
     * 加盟商列表初始化
     */
    initABList: function () {
        this.columns = this.ABList.columns;
        //数据加载完执行的方法
        this.drawCallback = function(settings, json) {
            $('th').removeClass('sorting sorting_asc sorting_desc');
        };
    },
    /*
     * 商品列表初始化
     */
    initGoodsList: function () {
        this.columns = this.GoodsList.columns;
        //数据加载完执行的方法
        this.drawCallback = function(settings, json) {
            $('th').removeClass('sorting sorting_asc sorting_desc');
        };
    },
    /*
     * 商品列表初始化
     */
    initABGoodsList: function () {
        this.columns = this.ABGoodsList.columns;
        //数据加载完执行的方法
        this.drawCallback = function(settings, json) {
            $('th').removeClass('sorting sorting_asc sorting_desc');
        };
    },
    /*
     * 收款日志报表初始化
     */
    initAbReceiptLog: function () {
        this.columns = this.AbReceiptLog.columns;
        //数据加载完执行的方法
        this.drawCallback = function(settings, json) {
            $('th').removeClass('sorting sorting_asc sorting_desc');
        };
    },

    /*
     * 商品订单列表初始化
     */
    initGoodsOrderList: function () {
        this.columns = this.GoodsOrderList.columns;
        //数据加载完执行的方法
        this.drawCallback = function(settings, json) {
            $('th').removeClass('sorting sorting_asc sorting_desc');
        };
    },
    /*
     * 商品订单列表初始化
     */
    initGoodsOrderDetailList: function () {
        this.columns = this.GoodsOrderDetailList.columns;
        //数据加载完执行的方法
        this.drawCallback = function(settings, json) {
            $('th').removeClass('sorting sorting_asc sorting_desc');
        };
    },
    /*
     * 商品订单列表初始化
     */
    initHeadGoodsOrderDetailList: function () {
        this.columns = this.HeadGoodsOrderDetailList.columns;
        //数据加载完执行的方法
        this.drawCallback = function(settings, json) {
            $('th').removeClass('sorting sorting_asc sorting_desc');
        };
    },
    /*
     * 商品订单列表初始化
     */
    initRecruitComboList: function () {
        this.columns = this.recruitComboList.columns;
        //数据加载完执行的方法
        this.drawCallback = function(settings, json) {
            $('th').removeClass('sorting sorting_asc sorting_desc');
        };
    },
    /*
     * 商品订单列表初始化
     */
    initABGoodsOrderList: function () {
        this.columns = this.ABGoodsOrderList.columns;
        //数据加载完执行的方法
        this.drawCallback = function(settings, json) {
            $('th').removeClass('sorting sorting_asc sorting_desc');
        };
    },
    /*
     * 商品订单列表初始化
     */
    initABGoodsOrderDetailList: function () {
        this.columns = this.ABGoodsOrderDetailList.columns;
        //数据加载完执行的方法
        this.drawCallback = function(settings, json) {
            $('th').removeClass('sorting sorting_asc sorting_desc');
        };
    },
    /*
     * 广告列表初始化
     */
    initAdvertList: function () {
        this.columns = this.AdvertList.columns;
        //数据加载完执行的方法
        this.drawCallback = function(settings, json) {
            $('th').removeClass('sorting sorting_asc sorting_desc');
        };
    },
    /*
     * 加盟商职位列表初始化
     */
    initAbPostList: function () {
        this.columns = this.ABPostList.columns;
        this.drawCallback = function(settings, json) {
            $('th').removeClass('sorting sorting_asc sorting_desc');
            $('a.status','tr').each(function(){
                var statusValue = $(this).attr('title');
                switch (statusValue)
                {
                    case '启用':
                        $(this).attr('title','禁用');
                        $(this).children().removeClass('Hui-iconfont-gouxuan').addClass('Hui-iconfont-shenhe-tingyong');
                        $(this).parents("tr").find('span',".td-status").addClass('label-success');
                        break;
                    case '禁用':
                        $(this).attr('title','启用');
                        $(this).children().removeClass('Hui-iconfont-shenhe-tingyong').addClass('Hui-iconfont-gouxuan');
                        $(this).parents("tr").find('span',".td-status").addClass('label-defaunt');
                        break;
                }
            });
        };
    },

    /*
     * 消息列表初始化
     */
    initMessageList: function () {
        this.columns = this.messageList.columns;
        //数据加载完执行的方法
        this.drawCallback = function(settings, json) {
            $('th').removeClass('sorting sorting_asc sorting_desc');
        };
    },
    /*
     * 消息列表初始化
     */
    initABMessageList: function () {
        this.columns = this.ABmessageList.columns;
        //数据加载完执行的方法
        this.drawCallback = function(settings, json) {
            $('th').removeClass('sorting sorting_asc sorting_desc');
        };
    },

    /*
     * 会员列表初始化
     */
    initMemberList: function () {
        this.columns = this.memberList.columns;
        //数据加载完执行的方法
        this.drawCallback = function(settings, json) {
            $('th').removeClass('sorting sorting_asc sorting_desc');
        };
    },

    /*
     * 普通套系列表初始化
     */
    initComboGeneralList: function () {
        this.columns = this.comboGeneralList.columns;
        //数据加载完执行的方法
        this.drawCallback = function(settings, json) {
            $('th').removeClass('sorting sorting_asc sorting_desc');
        };
    },

    /*
     * 普通套系商品选择列表初始化
     */
    initComboABGoodsList: function () {
        this.columns = this.comboABGoodsList.columns;
        //数据加载完执行的方法
        this.drawCallback = function(settings, json) {
            $('th').removeClass('sorting sorting_asc sorting_desc');
        };
    },


    /*
     * 成长套系列表初始化
     */
    initComboGrowList: function () {
        this.columns = this.comboGrowList.columns;
        //数据加载完执行的方法
        this.drawCallback = function(settings, json) {
            $('th').removeClass('sorting sorting_asc sorting_desc');
        };
    },

    /*
     * 选择普通套系
     */
    initSelectGeneralComboList : function () {
        this.columns = this.selectGeneralComboList.columns;
        //数据加载完执行的方法
        this.drawCallback = function(settings, json) {
            $('th').removeClass('sorting sorting_asc sorting_desc');
        };
    },

    /*
     * 会员订单初始化
     */
    initMemberOrderList : function () {
        this.columns = this.memberOrderList.columns;
        //数据加载完执行的方法
        this.drawCallback = function(settings, json) {
            $('th').eq(0).removeClass('sorting sorting_asc sorting_desc');
        };
    },

    /*
 * 会员订单初始化
 */
    initMemberOrderGuaList : function () {
        this.columns = this.memberOrderGuaList.columns;
        //数据加载完执行的方法
        this.drawCallback = function(settings, json) {
            $('th').eq(0).removeClass('sorting sorting_asc sorting_desc');
        };
    },

    /*
     * 会员订单套系初始化
     */
    initMemberOrderComboList : function () {
        this.columns = this.memberOrderComboList.columns;
        //数据加载完执行的方法
        this.drawCallback = function(settings, json) {
            $('th').removeClass('sorting sorting_asc sorting_desc');
        };
    },

    /*
     * 会员订单套系初始化
     */
    initMemberOrderComboAllList : function () {
        this.columns = this.memberOrderComboAllList.columns;
        //数据加载完执行的方法
        this.drawCallback = function(settings, json) {
            $('th').eq(0).removeClass('sorting sorting_asc sorting_desc');
            // $('th').removeClass('sorting sorting_asc sorting_desc');
        };
    },

    /*
     * 会员订单套系初始化
     */
    initMemberOrderDetailList : function () {
        this.columns = this.memberOrderDetailList.columns;
        //数据加载完执行的方法
        this.drawCallback = function(settings, json) {
            $('th').removeClass('sorting sorting_asc sorting_desc');
        };
    },

    /*
     * 员工提成列表初始化
     */
    initEmployeeRateList : function () {
        this.columns = this.employeeRateList.columns;
        //数据加载完执行的方法
        this.drawCallback = function(settings, json) {
            $('th').removeClass('sorting sorting_asc sorting_desc');
        };
    },

    /*
     * 未拍摄订单
     */
    initMemberOrderComboNotShootList : function () {
        this.columns = this.notShootOrderList.columns;
        //数据加载完执行的方法
        this.drawCallback = function(settings, json) {
            $('th').eq(0).removeClass('sorting sorting_asc sorting_desc');
        };
    },

    initReplanOrderList: function() {
        this.columns = this.replanOrder.columns;
        //数据加载完执行的方法
        this.drawCallback = function(settings, json) {
            $('th').removeClass('sorting sorting_asc sorting_desc');
        };
    },

    /*
     * 未选片订单
     */
    initMemberOrderComboNotSelectList : function () {
        this.columns = this.notSelectOrderList.columns;
        //数据加载完执行的方法
        this.drawCallback = function(settings, json) {
            $('th').removeClass('sorting sorting_asc sorting_desc');
        };
    },

    /*
     * 后期处理订单
     */
    initMemberOrderComboNotCompositeList : function () {
        this.columns = this.notCompositeOrderList.columns;
        //数据加载完执行的方法
        this.drawCallback = function(settings, json) {
            $('th').removeClass('sorting sorting_asc sorting_desc');
        };
    },

    /*
     * 成品理件订单
     */
    initMemberOrderComboNotDealList : function () {
        this.columns = this.notDealOrderList.columns;
        //数据加载完执行的方法
        this.drawCallback = function(settings, json) {
            $('th').removeClass('sorting sorting_asc sorting_desc');
        };
    },

    /*
     * 简历
     */
    initResumeList : function () {
        this.columns = this.resumeList.columns;
        //数据加载完执行的方法
        this.drawCallback = function(settings, json) {
            $('th').removeClass('sorting sorting_asc sorting_desc');
            $('a.status','tbody').each(function(){
                var statusValue = $(this).attr('title');
                switch (statusValue)
                {
                    case '是':
                        $(this).attr('title','取消默认');
                        $(this).children().removeClass('Hui-iconfont-gouxuan').addClass('Hui-iconfont-shenhe-tingyong');
                        $(this).parents("tr").find('span',".td-status").addClass('label-success');
                        break;
                    case '否':
                        $(this).attr('title','设为默认');
                        $(this).children().removeClass('Hui-iconfont-shenhe-tingyong').addClass('Hui-iconfont-gouxuan');
                        $(this).parents("tr").find('span',".td-status").addClass('label-defaunt');
                        break;
                }
            });
        };
    },

    /*
     * 招聘职位列表初始化
     */
    initRecruitPostList : function () {
        this.columns = this.recruitPostList.columns;
        //数据加载完执行的方法
        this.drawCallback = function(settings, json) {
            $('th').removeClass('sorting sorting_asc sorting_desc');
        };
    },

    /*
     * guest招聘职位列表初始化
     */
    initGuestPostList : function () {
        this.columns = this.guestPostList.columns;
        //数据加载完执行的方法
        this.drawCallback = function(settings, json) {
            $('th').removeClass('sorting sorting_asc sorting_desc');
        };
    },

    /*
     * 职位投递简历列表初始化
     */
    initResumeForRecruitPostList : function () {
        this.columns = this.resumeForRecruitPostList.columns;
        //数据加载完执行的方法
        this.drawCallback = function(settings, json) {
            $('th').removeClass('sorting sorting_asc sorting_desc');
        };
    },

    /*
     * 面试邀请简历初始化
     */
    initResumeInvitationList : function () {
        this.columns = this.resumeInvitationList.columns;
        //数据加载完执行的方法
        this.drawCallback = function(settings, json) {
            $('th').removeClass('sorting sorting_asc sorting_desc');
        };
    },

    /*
     * 员工提成日志初始化
     */
    initEmployeeRateLog : function () {
        this.columns = this.employeeRateLog.columns;
        //数据加载完执行的方法
        this.drawCallback = function(settings, json) {
            $('th').removeClass('sorting sorting_asc sorting_desc');
        };
    },

    /*
     * 推荐返利日志初始化
     */
    initRebateLog : function () {
        this.columns = this.rebateLog.columns;
        //数据加载完执行的方法
        this.drawCallback = function(settings, json) {
            $('th').removeClass('sorting sorting_asc sorting_desc');
        };
    },

    /*
     * 进客记录报表
     */
    initMemberLog: function () {
        this.columns = this.memberLog.columns;
        //数据加载完执行的方法
        this.drawCallback = function(settings, json) {
            $('th').removeClass('sorting sorting_asc sorting_desc');
        };
    },

    /*
     * 会员订单报表
     */
    initMemberOrderLog : function () {
        this.columns = this.memberOrderLog.columns;
        //数据加载完执行的方法
        this.drawCallback = function(settings, json) {
            $('th').removeClass('sorting sorting_asc sorting_desc');
        };
    },

    /*
     * 会员退单报表
     */
    initMemberOrderRefundLog : function () {
        this.columns = this.memberOrderRefundLog.columns;
        //数据加载完执行的方法
        this.drawCallback = function(settings, json) {
            $('th').eq(0).removeClass('sorting sorting_asc sorting_desc');
        };
    },

    /*
     * 出入库记录初始化
     */
    initGoodsImportExportLog : function () {
        this.columns = this.goodsImportExportLog.columns;
        //数据加载完执行的方法
        this.drawCallback = function(settings, json) {
            $('th').removeClass('sorting sorting_asc sorting_desc');
        };
    },

    /*
     *简历审核列表初始化
     */
    initResumeCheckList : function () {
        this.columns = this.resumeCheckList.columns;
        //数据加载完执行的方法
        this.drawCallback = function(settings, json) {
            $('th').removeClass('sorting sorting_asc sorting_desc');
        };
    },

    /*
     *职位审核列表初始化
     */
    initRecruitPostCheckList : function () {
        this.columns = this.recruitPostCheckList.columns;
        //数据加载完执行的方法
        this.drawCallback = function(settings, json) {
            $('th').removeClass('sorting sorting_asc sorting_desc');
        };
    },

    /*
     * 数据备份-导出excel
     */
    initExportExcelList : function()
    {
        this.sDom = '<"toolbar"rt<"clear">>';
        this.scrollY = '70%';
        this.columns = this.exportExcelList.columns;
        //数据加载完执行的方法
        this.drawCallback = function(settings, json) {
            $('th').removeClass('sorting sorting_asc sorting_desc');
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last = null;
            var tr = null;
            api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                tr = $(rows[i]);
                if(group != '--') {
                    if (last !== group) {
                        $("td:first", tr).attr("rowspan", $("input[name='module_team_repeat_num']", tr).val());
                        $("td:first", tr).html(group);
                        last = group;
                    } else {
                        $("td:first", tr).hide();
                    }
                }
            } );
            $('tbody tr').each(function(){
                // $(this).find('td').eq(0).css({'vertical-align' : 'middle'});
                // $(this).find('td').eq(1).css({'vertical-align' : 'middle'});
                $(this).find('td').css({'vertical-align' : 'middle'});
            });
        };
    },
    /*
     *职位审核列表初始化
     */
    initABSuppliers : function () {
        this.columns = this.ABSuppliers.columns;
        //数据加载完执行的方法
        this.drawCallback = function(settings, json) {
            $('th').removeClass('sorting sorting_asc sorting_desc');
        };
    },
};

//===========================公共类=========================
var Common = {
    /**
     * 执行启用禁用操作
     * @param id    传入的当前ID
     * @param obj   点击的按钮
     * @param url      ajax的URL
     * @param buttonValue   成功之后给button赋值
     * @param tdValue   成功之后修改表单的值
     */
    tabStatus : function (id, obj, url, buttonValue, tdValue)
    {
        var $obj = $(obj);
        $.ajax({
            url:url,
            type:'POST',
            data:{id:id,status:tdValue},
            async:false,
            dataType:'json',
            success:function(data){
                if(data.code == 1000)
                {
                    $obj.attr('title',buttonValue);
                    $obj.children().toggleClass('Hui-iconfont-gouxuan');
                    $obj.children().toggleClass('Hui-iconfont-shenhe-tingyong');
                    $obj.parents("tr").find('span',".td-status").text(tdValue);
                    $obj.parents("tr").find('span',".td-status").toggleClass('label-defaunt');
                    $obj.parents("tr").find('span',".td-status").toggleClass('label-success');
                }
                layer.msg(data.message,{icon: 6,time:1000});
            },
            error:function()
            {
                layer.msg('网络错误!',{icon: 5,time:1000});
            }
        });
    },

    //判断是否为整数
    isInt : function (currentValue)
    {
        var reg = /^\+?[1-9][0-9]*$/;
        var flag = reg.test(currentValue);
        return flag;
    },

    //判断是否为数字
    isNumber : function (currentValue)
    {
        var flag = true;
        if(currentValue === '' || currentValue ==null){
            flag = false;
        } else {
            var reg = /^\d+(\.\d+)?$/; //非负浮点数
            flag = reg.test(currentValue);
        }
        return flag;
    },

    //判断时间段的格式是否正确
    isTime :function (currentValue)
    {
        // var reg = /^[0-9][0-9]:[0-9][0-9]$/;
        // var flag = reg.test(currentValue);
        // return flag;
    },

    /*
     * 执行重新加载页面
     */
    doReload : function ()
    {
        $("#editAlert").modal("hide");
        window.location.reload();
    },

    /*
     * 执行初始化列表
     */
    doReloadTable : function()
    {
        TableType.drawTable();//实现当前页刷新
        $("#doReload").attr("onclick","");
    },

    /**
     * 获取当前日期 格式：2017-04-22
     */
    getDate : function ()
    {
        var date = new Date();
        var seperator = "-";
        var month = date.getMonth() + 1;
        var strDate = date.getDate();
        if (month >= 1 && month <= 9) {
            month = "0" + month;
        }
        if (strDate >= 0 && strDate <= 9) {
            strDate = "0" + strDate;
        }
        var currentDate = date.getFullYear() + seperator + month + seperator + strDate;
        return currentDate;
    },

};

var EchartsDIY = {
    showId : '',
    width : false,
    data : null,
    topList : [],
    dataUrl : '',
    pieDataUrl : '',
    dateLineOptions : null,
    pieOptions : null,
    currObj : null,
    //首页近12个月销售额增长趋势
    salesVolumeOptions: {
        title : {
            //左上角标题
            show: false,
            text: '',
            //左上角标题下面灰色小标题
            subtext: ''
        },
        //提示框 -- 鼠标悬浮交互时的信息展示
        tooltip : {
            //触发类型，默认数据触发，见下图，可选为：'item' | 'axis'
            trigger: 'axis'
        },
        //图例，每个图表最多仅有一个图例，混搭图表共享
        legend: {
            data:['销售额']
        },
        xAxis : [
            {
                //坐标轴类型，横轴默认为类目型'category'，纵轴默认为数值型'value'
                type : 'category',
                boundaryGap : false,
            }
        ],
        yAxis : [
            {
                type : 'value',
                axisLabel : {
                    formatter: '¥{value}'
                }
            }
        ],
        series : [
            {
                //系列名称，如启用legend，该值将被legend.data索引相关
                name:'销售额',
                /*
                 * 图表类型，必要参数！如为空或不支持类型，则该系列数据不被显示。可选为：
                 * 'line'（折线图） | 'bar'（柱状图） | 'scatter'（散点图） | 'k'（K线图）
                 * 'pie'（饼图） | 'radar'（雷达图） | 'chord'（和弦图） | 'force'（力导向布局图） | 'map'（地图）
                 */
                type:'line',
                data:[],
                markPoint : {
                    data : [
                        {type : 'max', name: '最大值'},
                        {type : 'min', name: '最小值'}
                    ]
                },
                markLine : {
                    data : [
                        {type : 'average', name: '平均值'}
                    ]
                }
            }
        ]
    },
    headHomeLine: {
        title : {
            //左上角标题
            show: false,
            text: '',
            //左上角标题下面灰色小标题
            subtext: ''
        },
        //提示框 -- 鼠标悬浮交互时的信息展示
        tooltip : {
            //触发类型，默认数据触发，见下图，可选为：'item' | 'axis'
            trigger: 'axis'
        },
        //图例，每个图表最多仅有一个图例，混搭图表共享
        legend: {
            data:['销售额']
        },
        xAxis : [
            {
                //坐标轴类型，横轴默认为类目型'category'，纵轴默认为数值型'value'
                type : 'category',
                boundaryGap : false,
            }
        ],
        yAxis : [
            {
                type : 'value',
                axisLabel : {
                    formatter: '¥{value}'
                }
            }
        ],
        series : [
            {
                //系列名称，如启用legend，该值将被legend.data索引相关
                name:'销售额',
                /*
                 * 图表类型，必要参数！如为空或不支持类型，则该系列数据不被显示。可选为：
                 * 'line'（折线图） | 'bar'（柱状图） | 'scatter'（散点图） | 'k'（K线图）
                 * 'pie'（饼图） | 'radar'（雷达图） | 'chord'（和弦图） | 'force'（力导向布局图） | 'map'（地图）
                 */
                type:'line',
                data:[],
                markPoint : {
                    data : [
                        {type : 'max', name: '最大值'},
                        {type : 'min', name: '最小值'}
                    ]
                },
                markLine : {
                    data : [
                        {type : 'average', name: '平均值'}
                    ]
                }
            }
        ]
    },
    //下单量统计
    orderNumStatisticsOptions: {
        title : {
            show: false,
            text: '',
            subtext: ''
        },
        tooltip : {
            trigger: 'axis'
        },
        legend: {
            data:['下单量'],
        },
        xAxis : [
            {
                type : 'time',
                boundaryGap : false,
                data : []
            }
        ],
        yAxis : [
            {
                type : 'value',
                axisLabel : {
                    formatter: '{value}'
                }
            }
        ],
        series : [
            {
                name:'下单量',
                type:'line',
                // data:{
                //     width:'100%'
                // },
                data:[],
                markPoint : {
                    data : [
                        {type : 'max', name: '最大值'},
                        {type : 'min', name: '最小值'}
                    ]
                },
                markLine : {
                    data : [
                        {type : 'average', name: '平均值'}
                    ]
                }
            }
        ]
    },
    //成交量统计
    volumeStatisticsOptions: {
        title : {
            show: false,
            text: '',
            subtext: ''
        },
        tooltip : {
            trigger: 'axis'
        },
        legend: {
            data:['成交量']
        },
        xAxis : [
            {
                type : 'time',
                boundaryGap : false,
                data : []
            }
        ],
        yAxis : [
            {
                type : 'value',
                axisLabel : {
                    formatter: '{value}'
                }
            }
        ],
        series : [
            {
                name:'成交量',
                type:'line',
                data:[],
                markPoint : {
                    data : [
                        {type : 'max', name: '最大值'},
                        {type : 'min', name: '最小值'}
                    ]
                },
                markLine : {
                    data : [
                        {type : 'average', name: '平均值'}
                    ]
                }
            }
        ]
    },
    //成交金额统计
    turnVolumeStatisticsOption: {
        title : {
            show: false,
            text: '',
            subtext: ''
        },
        tooltip : {
            trigger: 'axis'
        },
        legend: {
            data:['成交金额']
        },
        xAxis : [
            {
                type : 'time',
                boundaryGap : false,
                data : []
            }
        ],
        yAxis : [
            {
                type : 'value',
                axisLabel : {
                    formatter: '{value}'
                }
            }
        ],
        series : [
            {
                name:'成交金额',
                type:'line',
                data:[],
                markPoint : {
                    data : [
                        {type : 'max', name: '最大值'},
                        {type : 'min', name: '最小值'}
                    ]
                },
                markLine : {
                    data : [
                        {type : 'average', name: '平均值'}
                    ]
                }
            }
        ]
    },
    //退款金额统计
    refundAmountStatisticsOption: {
        title : {
            show: false,
            text: '',
            subtext: ''
        },
        tooltip : {
            trigger: 'axis'
        },
        legend: {
            data:['退款金额']
        },
        xAxis : [
            {
                type : 'time',
                boundaryGap : false,
                data : []
            }
        ],
        yAxis : [
            {
                type : 'value',
                axisLabel : {
                    formatter: '{value}'
                }
            }
        ],
        series : [
            {
                name:'退款金额',
                type:'line',
                data:[],
                markPoint : {
                    data : [
                        {type : 'max', name: '最大值'},
                        {type : 'min', name: '最小值'}
                    ]
                },
                markLine : {
                    data : [
                        {type : 'average', name: '平均值'}
                    ]
                }
            }
        ]
    },
    //广告数统计
    advertNumStatisticsOptions: {
        title : {
            show: false,
            text: '',
            subtext: ''
        },
        tooltip : {
            trigger: 'axis'
        },
        legend: {
            data:['广告数']
        },
        xAxis : [
            {
                type : 'time',
                boundaryGap : false,
                data : []
            }
        ],
        yAxis : [
            {
                type : 'value',
                axisLabel : {
                    formatter: '{value}'
                }
            }
        ],
        series : [
            {
                name:'广告数',
                type:'line',
                data:[]
            }
        ]
    },
    //充值续费统计
    advertRechargeOptions: {
        title : {
            show: false,
            text: '',
            subtext: ''
        },
        tooltip : {
            trigger: 'axis'
        },
        legend: {
            data:['充值续费统计']
        },
        xAxis : [
            {
                type : 'time',
                boundaryGap : false,
                data : []
            }
        ],
        yAxis : [
            {
                type : 'value',
                axisLabel : {
                    formatter: '￥ {value}'
                }
            }
        ],
        series : [
            {
                name:'充值续费统计',
                type:'line',
                data:[]
            }
        ]
    },
    //开店数量
    ABOpenNum: {
        title : {
            show: false,
            text: '',
            subtext: ''
        },
        tooltip : {
            trigger: 'axis'
        },
        legend: {
            data:['开店数量']
        },
        xAxis : [
            {
                type : 'time',
                boundaryGap : false,
                data : []
            }
        ],
        yAxis : [
            {
                type : 'value',
                axisLabel : {
                    formatter: '{value}'
                }
            }
        ],
        series : [
            {
                name:'开店数量',
                type:'line',
                data:[]
            }
        ]
    },
    //开店数量
    ABRecharge: {
        title : {
            show: false,
            text: '',
            subtext: ''
        },
        tooltip : {
            trigger: 'axis'
        },
        legend: {
            data:['充值/续费金额']
        },
        xAxis : [
            {
                type : 'time',
                boundaryGap : false,
                data : []
            }
        ],
        yAxis : [
            {
                type : 'value',
                axisLabel : {
                    formatter: '{value}'
                }
            }
        ],
        series : [
            {
                name:'充值/续费金额',
                type:'line',
                data:[]
            }
        ]
    },

    storeIncomePieOptions :{
        title : {
            text: '店铺营收占比',
            subtext: '',
            right:'5%',
            top: '12%'
        },
        tooltip : {
            trigger: 'item',
            formatter: "{a} <br/>{b}: {c} ({d}%)"
        },
        legend: {
            orient: 'vertical',
            right: '5%',
            top: 'middle',
            data: []
        },
        series : [
            {
                name: '',
                type: 'pie',
                radius: ['50%', '70%'],
                center: ['30%', '50%'],
                data:[],
                label: {
                    normal: {
                        show: false,
                        position: 'center'
                    },
                    emphasis: {
                        show: true,
                        textStyle: {
                            fontSize: '16',
                            fontWeight: 'bold'
                        }
                    }
                },
                labelLine: {
                    normal: {
                        show: false
                    }
                }
            }
        ]
    },
    //进客记录柱状图
    memberVisitedOptions: {
        color: ['#3398DB'],
        tooltip : {
            trigger: 'axis',
            axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
            }
        },
        grid: {
            left: '3%',
            right: '4%',
            bottom: '30%',
            containLabel: true
        },
        xAxis : [
            {
                type : 'time',
                data : [],
                axisTick: {
                    alignWithLabel: true
                },
                axisLabel: {
                    interval: 0,
                    rotate: 60
                }

            }
        ],
        yAxis : [
            {
                type : 'value'
            }
        ],
        series : [
            {
                name:'',
                type:'bar',
                barWidth: '60%',
                data:[]
            }
        ]
    },
    init: function(initFunction, showId, dataUrl, params) {
        this.showId = showId;
        this.dataUrl = dataUrl;
        this.params = params;
        eval('this.' + initFunction + '()');
    },
    /**
     * 渲染折线图
     * @private
     */
    _renderDateLine: function(){
        this._getDateLineData();
        var myChart = document.getElementById(this.showId);
        if(this.width) {
            myChart.style.width = (window.innerWidth - 120)+'px';
        }
        this.currObj = echarts.init(myChart);
        if (this.dateLineOptions.xAxis[0].type == 'category') {
            this.dateLineOptions.xAxis[0].data = this.data.xAxis.data;
        }
        this.dateLineOptions.series[0].data = this.data.series.data;
        this.currObj.setOption(this.dateLineOptions);
    },
    /**
     * 获取折线图数据
     * @private
     */
    _getDateLineData: function(){
        var _this = this;
        $.ajax({
            url: _this.dataUrl,
            type: "post",
            dataType: "json",
            async: false,
            data: _this.params,
            success: function (data) {
                _this.data = data;
            }
        });
    },

    /**
     * 近12个月销售趋势
     */
    initSalesVolumeOptions: function () {
        this.dateLineOptions = this.salesVolumeOptions;
        this._renderDateLine();
    },
    /**
     * 总部-近12个月销售趋势
     */
    initHeadHomeLine: function () {
        this.dateLineOptions = this.headHomeLine;
        this._renderDateLine();
    },
    /**
     * 下单量
     */
    initOrderNumStatistics: function () {
        this.dateLineOptions = this.orderNumStatisticsOptions;
        this.width = true;
        this._renderDateLine();
    },
    /**
     * 成交量
     */
    initVolumeStatistics: function () {
        this.dateLineOptions = this.volumeStatisticsOptions;
        this.width = true;
        this._renderDateLine();
    },
    /**
     * 成交金额
     */
    initTurnVolumeStatistics: function () {
        this.dateLineOptions = this.turnVolumeStatisticsOption;
        this.width = true;
        this._renderDateLine();
    },
    /**
     * 退款金额
     */
    initRefundAmountStatistics: function () {
        this.dateLineOptions = this.refundAmountStatisticsOption;
        this.width = true;
        this._renderDateLine();
    },
    /**
     * 广告数
     */
    initAdvertNumStatisticsOptions: function () {
        this.dateLineOptions = this.advertNumStatisticsOptions;
        this.width = true;
        this._renderDateLine();
    },
    /**
     * 广告  充值/续费金额
     */
    initAdvertRecharge: function () {
        this.dateLineOptions = this.advertRechargeOptions;
        this.width = true;
        this._renderDateLine();
    },

    /**
     * 加盟商 开店数
     */
    initOpenABNum: function () {
        this.dateLineOptions = this.ABOpenNum;
        this.width = true;
        this._renderDateLine();
    },
    /**
     * 加盟商 充值/续费金额
     */
    initABRecharge: function () {
        this.dateLineOptions = this.ABRecharge;
        this.width = true;
        this._renderDateLine();
    },
    /**
     * 店铺营收占比
     */
    initStoreIncomePie: function() {
        this.pieOptions = this.storeIncomePieOptions;
        this._renderPieChart();
    },
    _renderPieChart: function(){
        this._getPieChartData();
        this.currObj = echarts.init(document.getElementById(this.showId));
        this.pieOptions.legend.data = this.data.legend.data;
        this.pieOptions.series[0].data = this.data.series.data;
        this.currObj.setOption(this.pieOptions);
    },
    _getPieChartData: function(){
        var _this = this;
        $.ajax({
            url: _this.dataUrl,
            type: "post",
            dataType: "json",
            async: false,
            data: _this.params,
            success: function (data) {
                _this.data = data;
            }

        });
    },
    /*
     *   =============== 柱状图 ===================
     */

    initMemberVisitedBar: function() {
        this.barOptions = this.memberVisitedOptions;
        this.width = true;
        this._renderBar();
    },
    /**
     * 渲染折线图
     * @private
     */
    _renderBar: function(){
        this._getBarData();
        var myChart = document.getElementById(this.showId);
        if(this.width) {
            myChart.style.width = (window.innerWidth - 120)+'px';
        }
        this.currObj = echarts.init(myChart);
        // this.currObj = echarts.init(document.getElementById(this.showId));
        if (this.barOptions.xAxis[0].type == 'category') {
            this.barOptions.xAxis[0].data = this.data.xAxis.data;
        }
        this.barOptions.series[0].data = this.data.series.data;
        this.currObj.setOption(this.barOptions);
    },

    _getBarData: function() {
        var _this = this;
        $.ajax({
            url: _this.dataUrl,
            type: "post",
            dataType: "json",
            async: false,
            data: _this.params,
            success: function (data) {
                _this.data = data;
            }

        });
    },
    releaseEcharts: function() {
        if (this.currObj !== null) {
            this.currObj.dispose();
            this.currObj = null;
        }
    }

};
