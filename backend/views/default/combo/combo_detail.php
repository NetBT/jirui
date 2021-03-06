<article class="page-container col-xs-12">
    <div class="main-body col-xs-12">
        <table class="tab" width="100%" border="0" cellspacing="0" cellpadding="0" id="List"></table>
    </div>
    <div class="row cl">
        <div class="col-xs-12 col-sm-12 clear-padding text-center margin-top-30">
            <input class="btn btn-warning btn-sm" type="button" onclick="selectCombo()" value="&nbsp;&nbsp;确定&nbsp;&nbsp;">
            <input class="btn btn-default btn-md layui-layer-close margin-left-10" type="button" value="&nbsp;&nbsp;取消&nbsp;&nbsp;">
        </div>
    </div>
</article>
<script>
    $(function(){
        DataTable.init('initSelectGeneralComboList','#List','<?= \yii\helpers\Url::to(['combo/list','type' => \common\models\Status::COMBO_TYPE_GENERAL])?>',getDetailParams());
        tableCheckbox();
    });

    //全选
    function tableCheckbox()
    {
        $('table').on("change", ":checkbox", function() {
            // 列表复选框
            if ($(this).is("[name='checkbox_wrapper']")) {
                // 全选
                $(":checkbox", 'table').prop("checked",$(this).prop("checked"));
            }
        });
    }

    function getDetailParams (){
        var idStr = '';
        if($('#comboContent').html()){
            $(":checkbox", '#comboContent').each(function(){
                if($(this).is(":checked")){
                    idStr += $(this).val()+',';
                }
            });
        }
        return {
            selectCombo : idStr,
        };
    }

    //选择商品
    function selectCombo(){
        //获取值
        var idStr = '';
        var valueStr = '';
        $(":checkbox", 'table').each(function(){
            if(!$(this).is("[name='checkbox_wrapper']")){
                if($(this).is(":checked")){
                    idStr += ','+$(this).val();
                    var value = $(this).parents('tr').children('td').eq(2).html();
                    valueStr += '<label class="checkbox-inline"><input type="checkbox" value="'+$(this).val()+'" class="disabled" name="Combo[combo_content][]" checked >'+value+'</label>'
                }
            }
        });
        //赋值
        $('#comboContent').html('');
        $('#comboContent').append(valueStr);
        //关闭最新弹出的layer层
        layer.close(layer.index);
    }
</script>
