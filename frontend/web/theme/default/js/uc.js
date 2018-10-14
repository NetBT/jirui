document.write("<script src='js/function.js'></script>");
document.write("<script src='js-unit/jquery-easyui/plugins/jquery.pagination.js'></script>");
$.extend({
    initTable: function(domObject, options) {
        var _op = $.extend({
            width: '920',
            pagination: true,
            columns: [],
            data: [],
            queryParams: {},
            title: false,
            fitColumns: true,
            singleSelect: false,    //是否只允许选中一行
            // rownumbers: false,
            striped: true,
            method: 'get',
            loadMsg: '数据加载中.....',
            showFooter: true,
            pageSize: 30,
            pageList: [20, 30, 50, 100],//可以设置每页记录条数的列表
            onLoadSuccess: function () {
            }
        }, options);
        $(domObject).datagrid(_op);
        if (_op.pagination === true) {
            $(domObject).datagrid('getPager').pagination({
                beforePageText: '第',//页数文本框前显示的汉字
                afterPageText: '页    共 {pages} 页',
                displayMsg: '当前显示 {from} - {to} 条记录   共 {total} 条记录'
            });
        }
    }

});

var EchartsDIY = {
    startTime: '',
    endTime: '',
    showId: '',
    data: '',
    dateLineUrl: '',
    pieChartUrl: '',
    barChartUrl: '',
    topList: [],

    directSubTopUrl: '',
    dateLineOptions: {
        title: {
            text: ''
        },
        tooltip: {
            trigger: 'axis',
            axisPointer: {
                animation: false
            }
        },
        xAxis: {
            type: 'time',
            splitLine: {
                show: false
            }
        },
        yAxis: {
            type: 'value',
            boundaryGap: [0, '100%'],
            splitLine: {
                show: false
            }
        },
        series: [{
            name: '新增下级:',
            type: 'line',
            showSymbol: false,
            hoverAnimation: false,
            data: []
        }]
    },
    pieOptions : {
        title : {
            text: '',
            subtext: '',
            x:'center'
        },
        tooltip : {
            trigger: 'item',
        },
        legend: {
            orient: 'vertical',
            left: 'left',
            data: []
        },
        series : [
            {
                name: '',
                type: 'pie',
                radius : '55%',
                center: ['50%', '60%'],
                data:[],
                itemStyle: {
                    emphasis: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                }
            }
        ]
    },
    barOptions: {
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
                type : 'category',
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
    init: function() {
    },

    /**
     * 初始化时间线坐标图
     */
    initDateLine: function(){
        this.init();
        this._renderDateLine();
    },
    _renderDateLine: function(){
        this._getDateLineData();
        var myChart = echarts.init(document.getElementById('newAddSubLevel'));
        this.dateLineOptions.series[0].data = this.data;
        myChart.setOption(this.dateLineOptions);
        this._renderDirectSubTop();
    },

    _renderDirectSubTop: function() {
        this._getDirectSubTop();
        var html = '';
        $.each(this.topList, function(k, v){
           html += "<tr>";
           html += "<td>" +(k + 1)+ "</td>";
           html += "<td>" + v.newNums + "</td>";
           html += "<td>" + v.directName + "</td>";
           html += "</tr>";
        });
        $("#js-sub-top-table").find("tbody").html('').append(html);
    },
    _getDirectSubTop: function(){
        var _this = this;
        $.ajax({
            url: _this.directSubTopUrl,
            type: "post",
            dataType: "json",
            data: {startTime: _this.startTime, endTime: _this.endTime},
            success: function (data) {
                _this.topList = data.data;
            }
        });
    },

    _getDateLineData: function(){
        var _this = this;
        $.ajax({
            url: _this.dateLineUrl,
            type: "post",
            dataType: "json",
            async: false,
            data: {startTime: _this.startTime, endTime: _this.endTime},
            success: function (data) {
                _this.data = data.data;
            }

        });
    },
    /**
     * 初始化饼状图坐标图
     */
    initPieChart: function(){
        this.init();
        this._renderPieChart();
    },
    _renderPieChart: function(){
        this._getPieChartData();
        var myChart = echarts.init(document.getElementById('noActiveRate'));
        this.pieOptions.series[0].data = this.data;
        myChart.setOption(this.pieOptions);
    },
    _getPieChartData: function(){
        this._getDate();
        var _this = this;
        _this.pieOptions.legend.data = [];
        $.ajax({
            url: _this.pieChartUrl,
            type: "post",
            dataType: "json",
            data: {startTime: _this.startTime, endTime: _this.endTime},
            success: function (data) {
                _this.data = data.data;
                $.each (data.data, function(k, v) {
                    _this.pieOptions.legend.data.push(v.name);
                });
            }

        });
    },

    /**
     * 初始化柱状图坐标图
     */
    initBarChart: function(){
        this.init();
        this._renderBarChart();
    },
    _renderBarChart: function(){
        this._getBarChartData();
        var myChart = echarts.init(document.getElementById('subLevelPlay'));
        myChart.setOption(this.barOptions);
    },
    _getBarChartData: function(){
        this._getDate();
        var _this = this;
        _this.barOptions.xAxis[0].data = [];
        _this.barOptions.series[0].data = [];
        $.ajax({
            url: _this.barChartUrl,
            type: "post",
            dataType: "json",
            async: false,
            data: {startTime: _this.startTime, endTime: _this.endTime},
            success: function (data) {
                $.each (data.data, function(k, v) {
                    _this.barOptions.series[0].name = '玩彩情况:';
                    _this.barOptions.xAxis[0].data.push(v.name);
                    _this.barOptions.series[0].data.push(v.value);
                });
            }

        });
    },
};

var DataTime = {
    format: "yyyy-mm-dd hh:ii:ss",
    minView: "hour",
    startTime: "",
    endTime: "",
    init: function(){
        var _this = this;
        $(".form_start_datetime").datetimepicker("remove");
        $(".form_end_datetime").datetimepicker("remove");
        // console.log(this.format);
        $(".form_start_datetime").datetimepicker({
            minView: _this.minView,
            language: 'zh-CN',
            autoclose:true,
            format: _this.format,
            todayHighlight: true
        }).on("click",function(){
            $(this).datetimepicker("setEndDate", $(this).next('.form_end_datetime').val());
            if (_this.startTime != '') {
                $(this).datetimepicker("setStartDate", _this.startTime)
            }
        });
        $(".form_end_datetime").datetimepicker({
            minView: _this.minView,
            language: 'zh-CN',
            autoclose:true,
            format: _this.format,
            todayHighlight: true
        }).on("click",function(){
            $(this).datetimepicker("setStartDate", $(this).prev(".form_start_datetime").val());
            $(this).datetimepicker("setEndDate", new Date)
        });
    },
    setFormat: function (format, minView) {
        this.format = format;
        this.minView = minView;
    },
    setStartTime: function (startTime) {
        this.startTime = startTime;
    }
};

var TableGrids = {
    id: '',

    data: [],
    url : '',
    columns: [],
    queryParams: {},
    pagination: true,
    showFooter: true,
    onLoadSuccess: function(){},


    exampleTable: {
        url : "#",
        columns : [
            [
                {field: 'fieldOne', width: 110, title: '第一列', sortable: false, align: 'center', frozen: true},
                {field: 'fieldTwo', width: 80, title: '第二列', sortable: false, align: 'center', frozen: true},
                {field: 'fieldThree', width: 80, title: '第三列', sortable: false, align: 'center', frozen: true},
            ]
        ]
    },

    showTable : function (){
        var _this = this;
        $.initTable(_this.id, {
            url: _this.url,
            data: [],
            columns: _this.columns,
            queryParams: _this.queryParams,
            pagination: _this.pagination,
            showFooter: _this.showFooter,
            onLoadSuccess: _this.onLoadSuccess
        });
    },

    _loadTable: function(){
        var _this = this;
        $(_this.id).datagrid('load', _this.queryParams);
    },
    init: function(initFn, id) {
        eval('this.' + initFn + '(\''+id+'\')');
        this.showTable();
    },
    /*
     *  初始化游戏记录 投注记录报表
     *
     */
    initExampleTable: function(id) {
        this.columns = this.exampleTable.columns;
        this.url = this.exampleTable.url;
        this.id = id;
        this.pagination = true;
        this.showFooter = true;

        this._getTableParams();
        this._bindSearchEvent();
        this._bindDateSpeed('#startTime', "#endTime");
    },

    _bindSearchEvent: function (){
        var _this = this;
        $("#searchButton").unbind("click").bind("click", function(){
            _this._getTableParams();
            _this._loadTable();
        });

    },

    _getTableParams: function(){
        this.queryParams = {
        };
    },
    /**
     * 绑定日期选择按钮
     * @param startTimeId
     * @param endTimeId
     * @private
     */
    _bindDateSpeed: function(startTimeId, endTimeId){
        var _this = this;
        $(".js-date-speed").unbind("click").bind("click", function(){
            _this.queryParams.startTime = $(this).attr("data-start-date");
            _this.queryParams.endTime = $(this).attr("data-end-date");

            $(startTimeId).val(_this.queryParams.startTime);
            $(endTimeId).val(_this.queryParams.endTime);

            _this._loadTable();
        });
    }
};