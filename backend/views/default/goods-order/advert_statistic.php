<?php
\frontend\assets\EChartAsset::register($this);
?>
<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-4"><b>交易订单统计</b>Trade Order Statistics</div>
        <div class="header-search col-xs-7">
            <div class="col-xs-8 input-area">
                <label class="col-xs-3 text-right">日期</label>
                <div class="col-xs-4">
                    <input type="text" value="<?= date('Y-m-d', strtotime("-7 days"))?>" onclick="WdatePicker({readOnly: true, dateFmt: 'yyyy-MM-dd',maxDate:'#F{$dp.$D(\'endTime\',{d:-1});}'});" class="form-control" id="startTime" placeholder="开始日期">
                </div>
                <label class="col-xs-1 clear-padding text-center">至</label>
                <div class="col-xs-4">
                    <input type="text" value="<?= date('Y-m-d')?>" onclick="WdatePicker({readOnly: true, dateFmt: 'yyyy-MM-dd', minDate: '#F{$dp.$D(\'startTime\', {d:1});}'});" class="form-control" id="endTime" placeholder="结束日期">
                </div>
            </div>
            <div class="col-xs-4 pull-right text-r">
                <button class="btn btn-yellow" onclick="currMonth()">本月</button>
                <button class="btn btn-yellow" onclick="currYear()">本年</button>
                <button class="btn btn-hot " id="doSearch">查 询</button>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="main-body form-tab col-xs-12">
        <div class="form">
            <div class="form_box col-xs-12">
                <p class="tab col-xs-12">
                    <a href="javascript:void(0);" data-call-back="" onclick="renderOrderQuantity()">下单量</a>
                    <a href="javascript:void(0);" data-call-back="" onclick="renderVolume()">成交量</a>
                    <a href="javascript:void(0);" data-call-back="" onclick="renderTurnVolume()">成交金额</a>
                    <a href="javascript:void(0);" data-call-back="" onclick="renderRefundAmount()">退款金额</a>
                </p>
                <div class="form_box_sub">
                    <div class="form_box_tab" id="orderQuantity" style="width: 900px; height: 380px;"></div>
                </div>
                <div class="form_box_sub">
                    <div class="form_box_tab" id="volume" style="width: 900px; height: 380px;"></div>
                </div>
                <div class="form_box_sub">
                    <div class="form_box_tab" id="turnVolume" style="width: 900px; height: 380px;"></div>
                </div>
                <div class="form_box_sub">
                    <div class="form_box_tab" id="refundAmount" style="width: 900px; height: 380px;"></div>
                </div>
            </div>
            <div class="form-footer col-xs-12">
                <p class="box"><img src="/theme/default/images/tb38.png">总下单量：<span style="color:#ff7f50;">退款金额</span></p>
                <p class="box"><img src="/theme/default/images/tb39.png">总成交金额：<span style="color:#22ac38;">13522</span></p>
                <div class="clear"></div>
                <p class="box"><img src="/theme/default/images/tb40.png">总成交量：<span style="color:#00b7ee;">1200</span></p>
                <p class="box"><img src="/theme/default/images/tb41.png">总退款金额：<span style="color:#8c97cb;">2000</span></p>
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
    });

    function currMonth () {
        var start = '<?= date('Y-m-01')?>';
        var end = '<?= date('Y-m-t')?>';
        $("#startTime").val(start);
        $("#endTime").val(end);
        $("#doSearch").click();
    };
    function currYear () {
        var start = '<?= date('Y-01-01')?>';
        var end = '<?= date('Y-12-31')?>';
        $("#startTime").val(start);
        $("#endTime").val(end);
        $("#doSearch").click();
    };
    var currFunc = 'currFunc';

    function renderOrderQuantity() {
        EchartsDIY.releaseEcharts();
        EchartsDIY.init('initOrderNumStatistics', 'orderQuantity', '<?= yii\helpers\Url::to(['goods-order/echarts-order-quantity'])?>', getParams());
        currFunc = 'renderOrderQuantity';
    }

    function renderVolume() {
        EchartsDIY.releaseEcharts();
        EchartsDIY.init('initVolumeStatistics', 'volume', '<?= yii\helpers\Url::to(['goods-order/echarts-volume'])?>', getParams());
        currFunc = 'renderVolume';
    }

    function renderTurnVolume() {
        EchartsDIY.releaseEcharts();
        EchartsDIY.init('initTurnVolumeStatistics', 'turnVolume', '<?= yii\helpers\Url::to(['goods-order/echarts-turn-volume'])?>', getParams());
        currFunc = 'renderTurnVolume';
    }

    function renderRefundAmount() {
        EchartsDIY.releaseEcharts();
        EchartsDIY.init('initRefundAmountStatistics', 'refundAmount', '<?= yii\helpers\Url::to(['goods-order/echarts-refund-amount'])?>', getParams());
        currFunc = 'renderRefundAmount';
    }

    function getParams() {
        return {
            start: $("#startTime").val(),
            end: $("#endTime").val()
        };
    }

    function setTotalInfo() {

    }
</script>