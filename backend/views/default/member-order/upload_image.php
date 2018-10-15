<? \backend\assets\DropZoneAsset::register($this); ?>
<article class="page-container">
    <div id="upload-image" class="dropzone"></div>
    <div class="col-xs-12 col-sm-12 cl text-center margin-top-20 margin-bottom-10">
        <button type="button" onclick="save()" class='btn btn-hot btn-md margin-right-30'>确认</button>
        <button type="button" class="btn btn-default btn-md layui-layer-close">取消</button>
    </div>
</article>
<script>
    Dropzone.options.uploadImage = {
        paramName: "file", // The name that will be used to transfer the file
        maxFilesize: 2, // MB
        addRemoveLinks: true,
        dictRemoveFile: "删除",
        autoProcessQueue:true
    };
    var myDropzone = new Dropzone("#upload-image", {url: "<?= \yii\helpers\Url::to(['member-order/upload-image'])?>"});
    function save() {
        ajaxSubmitForm('#upload-image', '<?= \yii\helpers\Url::to(['member-order/upload-image'])?>');
    }


</script>
