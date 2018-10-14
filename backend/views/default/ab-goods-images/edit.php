<article class="page-container col-xs-12">
    <div class="pic_tc">
        <div id="scrollImg">
        <?php
            $session = Yii::$app->getSession();
            $imgList = $session->get('AbGoodsImage');
            if (!empty($imgList)) :
                foreach ($imgList as $k => $v) :
            ?>
                <img src="/uploads/<?= $v?>" data-id="<?= $k?>">
                <?php endforeach;?>
            <?php else:?>
        <?php endif;?>
        </div>
        <a class="left"></a>
        <a class="right"></a>
        <p class="btn">
            <span class="btn-upload" title="选择新图片">
                <a href="javascript:void();" class="tb1" id="uploadButton">&nbsp;</a>
                <input type="file"  name="file" id="uploadInput" class="input-file" />
            </span>
            <a class="tb3" onclick="GoodsImageUp.deleteImage()"></a>
        </p>
    </div>
    <i class="tc_bg"></i>
</article>
<style>
    .layui-layer {
        background-color: transparent;
        box-shadow: none;
        width: 100%; !important;
        height: 100%; !important;
        left: 0 !important;
    }
    .layui-layer-setwin .layui-layer-close2 {
        right: -7px;
        top: -8px;
    }
    .layui-layer-content {
        width: 100%;
    }
    .btn-upload {
        height: auto;
        vertical-align: top;
    }
    .input-file {
        width: 42px;
        height: 42px;
        top: 0;
    }

</style>
<script>
    var GoodsImageUp = {
        goodsId: '<?= $id?>',
        addUploadUrl: '<?= \yii\helpers\Url::to(['ab-goods-images/upload'])?>',
        uploadInput: '#uploadInput',
        uploadButton: '#uploadButton',
        deleteUrl: '<?= \yii\helpers\Url::to(['ab-goods-images/delete-image'])?>',
        currImageIndex: 0,
        currImageId: null,
        init: function () {
            var _this = this;
            this.bindChangeEvent();
            this.setCover();
        },
        setImgIndex: function() {
            var _this = this;
            $('#scrollImg').find('img').each(function (k, i) {
                if ($(this).css('display') == 'block') {
                    _this.currImageIndex = k;
                    _this.currImageId = $(this).attr('data-id');
                }
            });
        },
        setCover: function() {
            var len = $('#scrollImg').find('img').length;
            if (len > 0) {
                $("#album_cover").find('img').attr('src', $('#scrollImg').find('img').eq(0).attr('src'));
            }
        },
        bindChangeEvent: function() {
            var _this = this;
            $(_this.uploadInput).on('change', function(){
                var formData = new FormData();
                formData.append("imageFile", document.getElementById("uploadInput").files[0]);
                formData.append("id", _this.goodsId);
                _this.ajaxSubmitImage(_this.addUploadUrl, formData)
            });
        },
        ajaxSubmitImage: function(url, formData) {
            var _this = this;
            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                /**
                 *必须false才会自动加上正确的Content-Type
                 */
                contentType: false,
                /**
                 * 必须false才会避开jQuery对 formdata 的默认处理
                 * XMLHttpRequest会对 formdata 进行正确的处理
                 */
                processData: false,
                success: function (data) {
                    if (data.code == 1000) {
                        $("img","#scrollImg").fadeOut(300);
                        $("#scrollImg").append('<img src="/uploads/'+data.data+ '">');
                        _this.setCover();
                    } else {
                        alert(data.message);
                    }
                },
                error: function () {
                    alert("网络繁忙");
                },
                complete: function() {
                }
            });
        },
        deleteImage: function() {
            var _this = this;
            _this.setImgIndex();
            $.ajax({
                url:  _this.deleteUrl,
                type: 'post',
                data: {index: _this.currImageIndex, id: _this.goodsId, imageId: _this.currImageId},
                dataType: 'json',
                success: function (data) {
                    if (data.code == 1000) {
                        alert(data.message);
                        $("#scrollImg").find("img:eq(" + _this.currImageIndex+ ")").remove();
                        $("img","#scrollImg").eq(_this.currImageIndex - 1).fadeIn(300);
                        _this.setCover();
                    } else {
                        alert(data.message);
                    }
                },
                error: function () {
                    alert('网络错误');
                }
            });
        }
    };
    $(function(){
        $(".layui-layer").css({
            width: '100%',
            height: '100%'
        });
        var pin = 0;

        $(".pic_tc img").css('display', 'none').eq(0).css('display', 'block');
        $(".pic_tc a.left").click(function(){
            var plen = $(".pic_tc img").length;
            if( pin == 0 ){
                pin = plen
            };
            pin--;
            GoodsImageUp.currImageIndex = pin;
            $(".pic_tc img").fadeOut(300).eq(pin).stop().fadeIn(300);
        });
        $(".pic_tc a.right").click(function(){
            var plen = $(".pic_tc img").length;
            pin++;
            if(pin == plen){
                pin = 0
            };
            GoodsImageUp.currImageIndex = pin;
            $(".pic_tc img").fadeOut(300).eq(pin).stop().fadeIn(300);
        });
        GoodsImageUp.init();
    });

</script>
