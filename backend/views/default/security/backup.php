<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-7"><b>数据备份</b></div>
        <div class="clear"></div>
    </div>
    <div class="main-body col-xs-12">
        <div class="skin-minimal col-xs-12 text-center">
            <div class="check-box">
                <input type="checkbox" name="backup[]" value="storeManage">
                <label for="checkbox-1">店铺信息</label>
            </div>
            <div class="check-box">
                <input type="checkbox" name="backup[]" value="goodsManage">
                <label for="checkbox-1">商品信息</label>
            </div>
            <div class="check-box">
                <input type="checkbox" name="backup[]" value="orderManage">
                <label for="checkbox-1">订单信息</label>
            </div>
            <div class="check-box">
                <input type="checkbox" name="backup[]" value="advertManage">
                <label for="checkbox-1">广告信息</label>
            </div>
            <div class="check-box">
                <input type="checkbox" name="backup[]" value="recruitManage">
                <label for="checkbox-1">招聘信息</label>
            </div>
            <div class="check-box">
                <input type="checkbox" name="backup[]" value="employeeManage">
                <label for="checkbox-1">员工信息</label>
            </div>
            <div class="check-box">
                <input type="checkbox" name="backup[]" value="configManage">
                <label for="checkbox-1">配置信息</label>
            </div>
            <div class="check-box">
                <input type="checkbox" name="backup[]" value="messageManage">
                <label for="checkbox-1">消息信息</label>
            </div>
        </div>
        <div class="col-xs-12 text-center margin-top-30">
            <button class="btn btn-hot" type="button">数据备份</button>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function()
    {
        $('.skin-minimal input').iCheck({
            checkboxClass: 'icheckbox-blue',
            radioClass: 'iradio-blue',
            increaseArea: '20%'
        })
    });
</script>
