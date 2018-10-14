<?php \backend\assets\EChartAsset::register($this)?>
<div class="sub">
    <div style="margin-left:10px;">
        <div class="home_top">
            <p class="left">首页</p>
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
            <div class="home_tab" id="homeLine" style="width: 100%; height: 400px;"></div>
        </div>
    </div>
    <div class="home_right">
        <div style="margin-left:10px;">
            <?= \backend\components\HeadquarterHomeRateWidget::widget()?>
            <div class="home_right_info">
                <p class="bt">系统公告</p>
                <ul>
                    <?php foreach ($noticeList as $v) : ?>
                    <li><a href="javascript: void(0);" onclick="showNotice('<?= $v['id']?>')"><?= $v['title']?></a></li>
                    <?php endforeach;?>
                </ul>
                <p class="bt">用户反馈</p>
                <ul>
                    <?php foreach ($cpts as $v) :?>
                    <li><a href="javascript: void(0)" onclick="reply('<?= $v['id']?>')"><?= $v['content']?></a></li>
                    <?php endforeach;?>
                </ul>
                <p class="bt">系统建议</p>
                <ul>
                    <?php foreach ($xtjy as $v) :?>
                        <li><a href="javascript: void(0)" onclick="reply2('<?= $v['id']?>')"><?= $v['content']?></a></li>
                    <?php endforeach;?>
                </ul>
                <i></i>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        EchartsDIY.init('initHeadHomeLine', 'homeLine', '<?= yii\helpers\Url::to(['goods-order/echarts-head-home-line'])?>', {});
    });
    function showNotice(id) {
        layer_show({id: id}, '平台公告', '<?= \yii\helpers\Url::to(['notice/show-notice'])?>',900);
    }
    function reply(id) {
        layer_show({id: id, rand: Math.random()}, '回复消息', '<?= \yii\helpers\Url::to(['message/reply'])?>', 580, 580);
    }
    function reply2(id) {
        layer_show({id: id, rand: Math.random()}, '回复消息', '<?= \yii\helpers\Url::to(['message/reply'])?>', 580, 580);
    }
</script>
