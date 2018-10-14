<?php
\frontend\assets\EChartAsset::register($this);
?>
<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-4"><b>广告统计</b>Trade Advert Statistics</div>
        <div class="header-search col-xs-7">
            <div class="col-xs-6 input-area">
                <label class="col-xs-2 text-right">日期</label>
                <div class="col-xs-4">
                    <input type="text" style="width: 100px;" value="<?= date('Y-m-d', strtotime("-7 days"))?>" onclick="WdatePicker({readOnly: true, dateFmt: 'yyyy-MM-dd',maxDate:'#F{$dp.$D(\'endTime\',{d:-1});}'});" class="form-control" id="startTime" placeholder="开始日期">
                </div>
                <label class="col-xs-2 text-right">至</label>
                <div class="col-xs-4">
                    <input type="text" style="width: 100px;" value="<?= date('Y-m-d')?>" onclick="WdatePicker({readOnly: true, dateFmt: 'yyyy-MM-dd', minDate: '#F{$dp.$D(\'startTime\', {d:1});}'});" class="form-control" id="endTime" placeholder="结束日期">
                </div>
            </div>
            <div class="col-xs-6 pull-right text-r">
                <button class="btn btn-yellow" onclick="currMonth()">本月</button>
                <button class="btn btn-yellow" onclick="currYear()">本年</button>
                <button class="btn btn-hot " id="doSearch">查 询</button>
                <a class="btn btn-success" id="doRefresh" href="javascript:location.replace(location.href);">刷 新</a>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="main-body form-tab col-xs-12">
        <div class="form">
            <div class="form_box col-xs-12">
                <p class="tab">
                    <a href="javascript:void(0);" data-call-back="" onclick="renderAdvertNum()">广告数</a>
                    <a href="javascript:void(0);" data-call-back="" onclick="renderAdvertRecharge()">充值/续费金额</a>
<!--                    <a href="javascript:void(0);" data-call-back="" onclick="renderTurnVolume()">余额</a>-->
<!--                    <a href="javascript:void(0);" data-call-back="" onclick="renderRefundAmount()">收益金额</a>-->
                </p>
                <div class="form_box_sub">
                    <div class="form_box_tab" id="advertNum" style="width: 1300px; height: 380px;"></div>
                </div>
                <div class="form_box_sub">
                    <div class="form_box_tab" id="advertRecharge" style="width: 1300px; height: 380px;"></div>
                </div>
                <div class="form_box_sub">
                    <div class="form_box_tab" id="turnVolume" style="width: 1300px; height: 380px;"></div>
                </div>
                <div class="form_box_sub">
                    <div class="form_box_tab" id="refundAmount" style="width: 1300px; height: 380px;"></div>
                </div>
            </div>
            <div class="form-footer col-xs-12">
                <p class="box"><img src="/theme/default/images/tb38.png">总广告数：<span style="color:#ff7f50;" id="totalNum">0</span></p>
                <p class="box"><img src="/theme/default/images/tb39.png">总金额：<span style="color:#22ac38;" id="totalMoney">0</span></p>
                <div class="clear"></div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $(".form_box p.tab a").click(function(){
            $(".form_box p.tab a").removeClass("hover");
            $(this).addClass("hover");
            eval($(this).attr('data-call-back'));
            $(".form_box_sub").css("display","none").eq($(".form_box p.tab a").index(this)).css("display","block");
        });

        $(".form_box p.tab a:first").click();
        $("#doSearch").on('click', function () {
            eval(currFunc + '()');
        });
        setTotalInfo();
    });

    function currMonth () {
        var start = '<?= date('Y-m-01')?>';
        var end = '<?= date('Y-m-t')?>';
        $("#startTime").val(start);
        $("#endTime").val(end);
        setTotalInfo();
        $("#doSearch").click();
    }
    function currYear () {
        var start = '<?= date('Y-01-01')?>';
        var end = '<?= date('Y-12-31')?>';
        $("#startTime").val(start);
        $("#endTime").val(end);
        setTotalInfo();
        $("#doSearch").click();
    }
    var currFunc = 'currFunc';

    function renderAdvertNum() {
        EchartsDIY.releaseEcharts();
        EchartsDIY.init('initAdvertNumStatisticsOptions', 'advertNum', '<?= yii\helpers\Url::to(['advert/echarts-advert-num'])?>', getParams());
        currFunc = 'renderAdvertNum';
    }

    function renderAdvertRecharge() {
        EchartsDIY.releaseEcharts();
        EchartsDIY.init('initAdvertRecharge', 'advertRecharge', '<?= yii\helpers\Url::to(['advert/echarts-recharge'])?>', getParams());
        currFunc = 'renderAdvertRecharge';
    }

    function getParams() {
        return {
            start: $("#startTime").val(),
            end: $("#endTime").val()
        };
    }

    function setTotalInfo() {
        if($('#startTime').val() != startTime || $('#endTime').val() != endTime) {
            startTime = $('#startTime').val();
            endTime = $('#endTime').val()
            $.ajax({
                url: '<?= \yii\helpers\Url::to(['advert/get-total'])?>',
                type: 'post',
                async: true,
                data: {start: startTime, end: endTime},
                success: function (result) {
                    if (result.code == 1000) {
                        $('#totalNum').html(result.data.totalNum);
                        $('#totalMoney').html(result.data.totalMoney);
                    } else {
                        layer.msg(result.message, {icon: 2});
                    }
                }
            });
        }
    }
</script>