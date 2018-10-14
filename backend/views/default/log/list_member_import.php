<?php
\frontend\assets\EChartAsset::register($this);
?>
<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-4"><b>进客记录</b>History of Visited</div>
        <div class="header-search col-xs-7">
            <div class="col-xs-6 input-area">
                <label class="col-xs-2 text-right">日期</label>
                <div class="col-xs-4">
                    <input type="text" style="width: 100px" value="<?= date('Y-m-d', strtotime("-7 days"))?>" onclick="WdatePicker({readOnly: true, dateFmt: 'yyyy-MM-dd',maxDate:'#F{$dp.$D(\'endTime\',{d:-1});}'});" class="form-control" id="startTime" placeholder="开始日期">
                </div>
                <label class="col-xs-2 text-right">至</label>
                <div class="col-xs-4">
                    <input type="text" style="width: 100px" value="<?= date('Y-m-d')?>" onclick="WdatePicker({readOnly: true, dateFmt: 'yyyy-MM-dd', minDate: '#F{$dp.$D(\'startTime\', {d:1});}'});" class="form-control" id="endTime" placeholder="结束日期">
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
                    <a href="javascript:void(0);" data-call-back="" onclick="renderRegularRecommend()">老顾客推荐</a>
                    <a href="javascript:void(0);" data-call-back="" onclick="renderNormal()">自然进店</a>
                    <a href="javascript:void(0);" data-call-back="" onclick="renderTel()">电话咨询</a>
                    <a href="javascript:void(0);" data-call-back="" onclick="renderMassesStore()">大众店铺</a>
                    <a href="javascript:void(0);" data-call-back="" onclick="renderPopularizer()">推广商</a>
                    <a href="javascript:void(0);" data-call-back="" onclick="renderOther()">其他</a>
                </p>
                <div class="form_box_sub">
                    <div class="form_box_tab" id="regularRecommend" style="height: 380px;"></div>
                </div>
                <div class="form_box_sub">
                    <div class="form_box_tab" id="normal" style=" height: 380px;"></div>
                </div>
                <div class="form_box_sub">
                    <div class="form_box_tab" id="telVisited" style="height: 380px;"></div>
                </div>
                <div class="form_box_sub">
                    <div class="form_box_tab" id="massesStore" style="height: 380px;"></div>
                </div>
                <div class="form_box_sub">
                    <div class="form_box_tab" id="popularizer" style=" height: 380px;"></div>
                </div>
                <div class="form_box_sub">
                    <div class="form_box_tab" id="other" style="height: 380px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .form_box_tab{
        border-bottom: none !important;
    }
</style>
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

    /**
     * 老客户推荐
     */
    function renderRegularRecommend() {
        EchartsDIY.releaseEcharts();
        EchartsDIY.init('initMemberVisitedBar', 'regularRecommend', '<?= yii\helpers\Url::to(['log/echart-visited', 'type' => \common\models\Status::MEMBER_SOURCE_LGKTJ])?>', getParams());
        currFunc = 'renderRegularRecommend';
    }

    /**
     * 自然进店
     */
    function renderNormal() {
        EchartsDIY.releaseEcharts();
        EchartsDIY.init('initMemberVisitedBar', 'normal', '<?= yii\helpers\Url::to(['log/echart-visited', 'type' => \common\models\Status::MEMBER_SOURCE_ZRJD])?>', getParams());
        currFunc = 'renderNormal';
    }

    /**
     * 电话推荐
     */
    function renderTel() {
        EchartsDIY.releaseEcharts();
        EchartsDIY.init('initMemberVisitedBar', 'telVisited', '<?= yii\helpers\Url::to(['log/echart-visited', 'type' => \common\models\Status::MEMBER_SOURCE_DHZX])?>', getParams());
        currFunc = 'renderTel';
    }

    /**
     * 大众店铺
     */
    function renderMassesStore() {
        EchartsDIY.releaseEcharts();
        EchartsDIY.init('initMemberVisitedBar', 'massesStore', '<?= yii\helpers\Url::to(['log/echart-visited', 'type' => \common\models\Status::MEMBER_SOURCE_DZDP])?>', getParams());
        currFunc = 'renderMassesStore';
    }

    /**
     * 推广商
     */
    function renderPopularizer() {
        EchartsDIY.releaseEcharts();
        EchartsDIY.init('initMemberVisitedBar', 'popularizer', '<?= yii\helpers\Url::to(['log/echart-visited', 'type' => \common\models\Status::MEMBER_SOURCE_TGS])?>', getParams());
        currFunc = 'renderPopularizer';
    }

    /**
     * 其他进店
     */
    function renderOther() {
        EchartsDIY.releaseEcharts();
        EchartsDIY.init('initMemberVisitedBar', 'other', '<?= yii\helpers\Url::to(['log/echart-visited', 'type' => \common\models\Status::MEMBER_SOURCE_QT])?>', getParams());
        currFunc = 'renderOther';
    }

    function getParams() {
//        setTotalInfo();
        return {
            start: $("#startTime").val(),
            end: $("#endTime").val()
        };
    }
//    var startTime = '';
//    var endTime = '';
//    function setTotalInfo() {
//        if($('#startTime').val() != startTime || $('#endTime').val() != endTime) {
//            startTime = $('#startTime').val();
//            endTime = $('#endTime').val()
//            $.ajax({
//                url: '<?//= \yii\helpers\Url::to(['goods-order/set-total'])?>//',
//                type: 'post',
//                async: true,
//                data: {start: startTime, end: endTime},
//                success: function (result) {
//                    if (result.code == 1000) {
//                        $('#totalOrderNum').html(result.data.totalOrderNum);
//                        $('#totalTurnVolume').html(result.data.totalTurnVolume);
//                        $('#totalVolume').html(result.data.totalVolume);
//                        $('#totalRefundAmount').html(result.data.totalRefundAmount);
//                    } else {
//                        layer.msg(result.message, {icon: 2});
//                    }
//
//                }
//            });
//        }
//    }
</script>