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
            "pageLength" : 3,
            "pagingType": "simple_numbers",
            "sDom":'<"toolbar"rtlip<"clear">>',// 自定义布局  此例为隐藏顶部搜索框
            // "sDom":'<"toolbar"prtli<"clear">>',// 自定义布局  此例为隐藏顶部搜索框
            "oLanguage": {//国际语言转化
                "sLengthMenu": "显示 _MENU_ 记录",
                "sZeroRecords": "对不起，查询不到任何相关数据",
                "sEmptyTable": "未有相关数据",
                "sLoadingRecords": "正在加载数据-请等待...",
                "sProcessing": '<div class="loading"></div>',
                "sInfo": "当前显示 _START_ 到 _END_ 条，共 _TOTAL_ 条记录。",
                "sInfoEmpty": "当前显示0到0条，共0条记录",
                "sInfoFiltered": "（数据库中共为 _MAX_ 条记录）",
                "sSearch": "模糊查询：",
                "sUrl": "",
                //多语言配置文件
                "oPaginate": {
                    "sFirst": "<<",
                    "sPrevious": " < ",
                    "sNext": " > ",
                    "sLast": " >> ",
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
        $(domObject).DataTable(_op);
    }
});
var TableCommon = {
    sDom : '<"toolbar"rtp<"clear">>',
    scrollY : false,
    columns : [],
    id : '',
    ajax: {
        url: '',
        type: 'POST',
        data: ''
    },
    drawCallback : '',
    exampleTable: {
        columns : [
            {data: 'fieldOne', sTitle: '第一列', orderable: false, render: function(Data, type, row, meta){
                return   '<p><img src="'+row.myPlatformLogo+'" width="40"></p><p>'+row.myPlatform+'</p>';
            }},
            {data: 'fieldTwo', sTitle: '第二列', orderable: false},
            {data: 'fieldThree', sTitle: '第三列', orderable: false},
            {data: 'fieldFour', sTitle: '第四列', orderable: false, render: function(Data, type, row, meta){
                return   '<p><img src="'+row.wantPlatformLogo+'" width="40"></p><p>'+row.wantPlatform+'</p>';
            }},
        ],
    },

    //总初始化
    initTable: function () {
        $.initTable(this.id, {columns: this.columns, ajax: this.ajax, drawCallback : this.drawCallback, sDom : this.sDom, scrollY : this.scrollY});
    },

    init: function(initTableName, sId, sUrl) {
        var str = 'this.' + initTableName + '(\'' + sUrl +'\')';
        this.id = sId;
        this.ajax.url = sUrl;
        this.ajax.data = function(d) {
            d.extra_search = getParams();
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
        // $(this.id).DataTable().ajax.reload();
    },

    //实现当前页刷新
    drawTable : function()
    {
        $(this.id).DataTable().draw(false);
    },

    //===============================  具体列表初始化  ======================================



    /**
     * 绑定日期选择按钮
     * @param startTimeId
     * @param endTimeId
     * @private
     */
    _bindDateSpeed: function(startTimeId, endTimeId, getParamsFn){
        var _this = this;
        $(".js-date-speed").unbind("click").bind("click", function(){

            $(startTimeId).val($(this).attr("data-start-date"));
            $(endTimeId).val($(this).attr("data-end-date"));

            _this.reloadTable(getParamsFn);
        });

    },
    /**
     * 初始化账户明细记录
     * @param id
     * @param params
     */
    initExampleTable: function() {
        this.columns = this.exampleTable.columns;
        //数据加载完执行的方法
        this.drawCallback = function(settings, json) {
            $('thead').find('th').removeClass('sorting sorting_asc sorting_desc').css({'text-align' : 'center','border':'none'});
            $(".dataTables_scrollBody").slimScroll({
                height: getTableHeight() + 'px',
            });
        };
    },
};
