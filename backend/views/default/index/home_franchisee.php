<?php \backend\assets\EChartAsset::register($this);?>
<div class="sub">
    <div style="margin-left:10px;">
        <div class="home_top">
            <p class="left">首页</p>
            <a class="btn" href="javascript:void(0)" onclick="sendMessage()">意见反馈</a>
<!--            <a class="box">请假</a>-->
<!--            <div class="home_top_info">-->
<!--                <ul>-->
<!--                    <li><a href="#">北京西单大悦城店铺申请延期操作 . . .</a></li>-->
<!--                    <li><a href="#">北京西单大悦城店铺申请延期操作 . . .</a></li>-->
<!--                    <li><a href="#">北京西单大悦城店铺申请延期操作 . . .</a></li>-->
<!--                </ul>-->
<!--            </div>-->
            <div class="clear"></div>
        </div>
    </div>
    <div class="home_left">
        <div class="home_left_top">
            <div class="home_left_sub">
                <img src="<?= \yii\helpers\Url::to(['@web/theme/default/images/home_left_sub1.png'])?>">
                <p style="color:#f39800;" class="bt">今日销售额</p>
                <p style="color:#f39800;" class="ct"><?= $todayIncome?></p>
            </div>
        </div>
        <div class="home_left_top">
            <div class="home_left_sub">
                <img src="<?= \yii\helpers\Url::to(['@web/theme/default/images/home_left_sub2.png'])?>">
                <p style="color:#57bdde;" class="bt">本月销售额</p>
                <p style="color:#57bdde;" class="ct"><?= $monthIncome?></p>
            </div>
        </div>
        <div class="home_left_top">
            <div class="home_left_sub">
                <img src="<?= \yii\helpers\Url::to(['@web/theme/default/images/home_left_sub3.png'])?>">
                <p style="color:#6dc7be;" class="bt">会员总数</p>
                <p style="color:#6dc7be;" class="ct"><?= $totalMember?></p>
            </div>
        </div>
        <div class="clear"></div>
        <div class="home_left_tab">
            <p class="bt">近12个月销售额增长趋势(本店)</p>
            <div class="home_tab" id="dateLine" style="width: 100%; height: 400px;"></div>
        </div>
    </div>
    <div class="home_right">
        <div style="margin-left:10px;">
            <div class="home_right_info">
                <p class="bt">集睿平台消息</p>
                <ul style="padding:10px 20px">
                    <?php foreach ($noticeList as $v) :?>
                    <li><a href="javascript: void(0);" onclick="showNotice('<?= $v['id']?>')"><?= $v['title']?></a></li>
                    <?php endforeach;?>
                </ul>
                <i></i>
            </div>
            <?php foreach ($advertList as $v) :?>
            <img class="pic" alt="<?= $v['advert_name']?>" src="/uploads/<?= $v['advert_matter']?>">
            <?php endforeach;?>
        </div>
    </div>
</div>

<script>
    function sendMessage() {
        layer_show({}, '意见反馈', '<?= \yii\helpers\Url::to(['message/send'])?>',580,580);
    }

    function showNotice(id) {
        layer_show({id: id}, '平台公告', '<?= \yii\helpers\Url::to(['notice/show-notice'])?>',900);
    }

    function showModalAdvert() {
        layer_show({}, '资讯', '<?= \yii\helpers\Url::to(['advert/modal-advert'])?>', 900);
    }
    $(function () {
        EchartsDIY.init('initSalesVolumeOptions', 'dateLine', '<?= \yii\helpers\Url::to(['ab-statement/ab-home-echarts-data'])?>', {});
        <?php if (!empty($modalAdvertList)) :?>
        setTimeout(showModalAdvert, 2000);
        <?php endif;?>
    });
</script>