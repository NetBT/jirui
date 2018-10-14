<article class="page-container col-xs-12">
   <div class="col-xs-12 form-group col-sm-12 cl text-center">
       <div class="formControls col-xs-12">
           <input type="text" class="input-text" value="" id="sizeName" placeholder="颜色名称" />
       </div>
   </div>
    <div class="col-xs-12 col-sm-12 cl text-center">
        <button type="button" onclick="doAddGoodsSize()" class='btn btn-hot btn-md margin-right-30'>添加</button>
        <button type="button" onclick="layer.closeAll()" class="btn btn-default btn-md">取消</button>
    </div>
</article>
<script>
    function doAddGoodsSize() {
        var sizeName = $("#sizeName").val();

        if(sizeName == '') {
            layer.msg('请填写尺寸名称', {icon: 0});
            return false;
        }
        $('#goods-goods_size').append('<label class="radio-inline"><input name="Goods[goods_size]" value="' + sizeName + '" type="radio">' + sizeName + '</label>');
        layer.closeAll();
        layer.msg( sizeName + '添加成功', {icon: 1});
    }
</script>
