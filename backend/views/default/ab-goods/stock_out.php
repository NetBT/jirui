<article class="page-container col-xs-12">
    <div class="col-xs-12 form-group col-sm-12 cl text-center">
        <label class="col-xs-3 text-right">商品编号</label>
        <div class="formControls col-xs-6">
            <input type="text" class="input-text" id="searchGoodsCode" value="<?= $code?>" placeholder="商品编号" />
        </div>
        <div class="col-xs-2 text-left">
            <button class="btn btn-primary btn-xs" onclick="searchGoods()">搜索</button>
        </div>
    </div>
    <style>
        .slider .bd, .slider .bd li, .slider .bd img {
            width:100%;
            height: 400px;
        }
    </style>
    <div class="col-xs-12" id="slider">
        <div class="slider">
            <div class="bd">
                <ul id="sliderList">

                </ul>
            </div>
            <ol class="hd cl dots" id="olList">

            </ol>
        </div>
    </div>
    <div class="col-xs-12 form-group col-sm-12 margin-top-30 cl text-center">
        <label class="col-xs-3 text-right">出库数量</label>
        <div class="formControls col-xs-2">
            <input type="text" class="input-text" value="0" id="stockInNum"/>
        </div>
        <div class="col-xs-2 text-left">
            <button class="btn btn-primary btn-xs" onclick="doStockOut()">出库</button>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 cl text-center">
        <button type="button" class="layui-layer-close btn btn-default btn-md">取消</button>
    </div>
</article>

<script>
    $(function () {
        var code = $("#searchGoodsCode").val();
        if (code != '') {
            searchGoods();
        }
    });

    function initSlide(){
        $("#slider .slider").slide({
            mainCell:".bd ul",
            titCell:".hd li",
            trigger:"click",
            effect:"leftLoop",
            autoPlay:true,
            delayTime:700,
            interTime:7000,
            pnLoop:false,
            titOnClassName:"active"
        })
    }
    function searchGoods() {
        var goodsCode = $("#searchGoodsCode").val();
        $.ajax({
            url: '<?= \yii\helpers\Url::to(['ab-goods/search-goods'])?>',
            data:{goodsCode: goodsCode},
            type: 'post',
            beforeSend: function () {
                if (goodsCode == '') {
                    layer.msg('请输入商品编号');
                    return false;
                }
            },
            success: function (result) {
                if (result.code == 1000) {
                    var liHtml = '';
                    var olHtml = '';
                    if (result.data.images.length > 0) {
                        $.each(result.data.images, function (key, val) {
                            liHtml += '<li><img src="/uploads/' + val.image_url + '" ></li>';
                            olHtml += '<li>' + parseInt(key + 1) + '</li>';
                        });
                        $("#sliderList").html(liHtml);
                        $("#olList").html(olHtml);
                        initSlide();
                    }
                } else {
                    layer.msg(result.message, {icon: 2});
                }
            },
            error: function () {
                layer.msg('网络错误', {icon: 2});
            }
        });
    }

    function doStockOut() {
        var goodsCode = $("#searchGoodsCode").val();
        var stockInNum = $("#stockInNum").val();
        $.ajax({
            url: '<?= \yii\helpers\Url::to(['ab-goods/do-stock-out'])?>',
            data:{goodsCode: goodsCode, inNum: stockInNum},
            type: 'post',
            beforeSend: function () {
                if (goodsCode == '') {
                    layer.msg('请输入商品编号');
                    return false;
                }
            },
            success: function (result) {
                if (result.code == 1000) {
                    layer.msg(result.message, {icon: 1});
                    DataTable.reloadTable(getParams());
                } else {
                    layer.msg(result.message, {icon: 2});
                }
            },
            error: function () {
                layer.msg('网络错误', {icon: 2});
            }
        });
    }
</script>
