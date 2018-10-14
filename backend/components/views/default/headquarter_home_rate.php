<div class="home_right_top">
<!--    <input type="text" value="" placeholder="请输入手机号或合同号查询加盟商">-->
    <div class="col-xs-12" id="pieChart" style="width: 100%; height: 250px;"></div>
    <i></i>
</div>
<script>
    $(function () {
        EchartsDIY.init('initStoreIncomePie', 'pieChart', '<?= \yii\helpers\Url::to(['ab-statement/ab-income-rate-data'])?>', {});
    });
</script>