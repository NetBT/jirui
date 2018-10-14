<article class="page-container col-xs-12">
   <div class="col-xs-12 form-group col-sm-12 cl text-center">
       <div class="formControls col-xs-12">
           <input type="text" class="input-text" value="" id="colorName" placeholder="颜色名称" />
       </div>
   </div>
    <div class="col-xs-12 col-sm-12 cl text-center">
        <button type="button" onclick="doAddGoodsColor()" class='btn btn-hot btn-md margin-right-30'>添加</button>
        <button type="button" class="btn btn-default btn-md layui-layer-close">取消</button>
    </div>
</article>
<script>
    function doAddGoodsColor() {
        var colorName = $("#colorName").val();

        if(colorName == '') {
            layer.msg('请填写颜色名称', {icon: 0});
            return false;
        }
        $('#goods-goods_color').append('<label class="radio-inline"><input name="Goods[goods_color]" value="' + colorName + '" type="radio">' + colorName + '</label>');
        layer.closeAll();
        layer.msg(colorName + '添加成功', {icon: 3});
    }
</script>
