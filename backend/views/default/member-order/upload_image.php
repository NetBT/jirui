<?
/**
 * @var $memberOrder \backend\models\MemberOrder
 */
\backend\assets\DropZoneAsset::register($this);
?>
<article class="page-container">
    <div id="upload-image" class="dropzone"></div>
    <div class="col-xs-12 col-sm-12 cl text-center margin-top-20 margin-bottom-10">
        <button type="button" onclick="save()" class='btn btn-hot btn-md margin-right-30'>确定</button>
        <button type="button" class="btn btn-default btn-md layui-layer-close">关闭</button>
    </div>
</article>
<script>
    Dropzone.options.uploadImage = {
        paramName: "file", // The name that will be used to transfer the file
        maxFilesize: 2, // MB
        dictDefaultMessage: "请点击上传，或拖拽图片到这个区域（支持jpg,png,svg,jpeg,gif）",
        acceptedFiles: ".jpg,.png,.svg,.jpeg,.gif",
        autoProcessQueue: true,
        addRemoveBtn: false,
        maxFiles:<?= $memberOrder->getRestRegisterCount()?>,
    };
    var myDropzone = new Dropzone("#upload-image", {
        url: "<?= \yii\helpers\Url::to([
            'member-order/receive-image',
            'order_id' => $memberOrder->id
        ])?>"
    });

    function save() {
        layer.closeAll('page');
        layer_close();
        let callBackFunction = DataTable.drawTable();
        layer.msg(data.message, {icon: 6, time: 2000}, callBackFunction);
    }

</script>
