<article class="page-container">
    <form id="refund" class="form form-horizontal" action="" method="post">
        <input type="hidden" value="1" name="submit" id="submit">
    </form>

    <div class="col-xs-12 col-sm-12 cl text-center margin-top-20 margin-bottom-10">
        <button type="button" onclick="save()" class='btn btn-hot btn-md margin-right-30'>确认</button>
        <button type="button" class="btn btn-default btn-md layui-layer-close">取消</button>
    </div>
</article>
<script>
    function save() {
        ajaxSubmitForm('#upload-image', '<?= \yii\helpers\Url::to(['member-order/upload-image'])?>');
    }

</script>
