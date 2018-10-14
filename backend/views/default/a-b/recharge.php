<article class="page-container col-xs-12">
   <form id="rechargeForm">
       <div class="col-xs-12 col-sm-12 form-group cl text-center">
           <label class="form-label col-xs-4 text-right">合同编号</label>
           <div class="formControls col-xs-8">
               <input type="text" readonly class="input-text" value="<?= $info['AB_number']?>" />
               <input type="hidden" readonly  name="id" class="input-text" value="<?= $info['id']?>" />
           </div>
       </div>
       <div class="col-xs-12 form-group col-sm-12 cl text-center">
           <label class="form-label col-xs-4 text-right">店铺名称</label>
           <div class="formControls col-xs-8">
               <input type="text" readonly class="input-text" value="<?= $info['AB_name']?>" />
           </div>
       </div>
       <div class="col-xs-12 col-sm-12 form-group cl text-center">
           <label class="form-label col-xs-4  text-right">充值金额</label>
           <div class="formControls col-xs-8">
               <input type="number" class="input-text" name="recharge_money" value="0.00" style="width:100%;" onkeyup="return !isNaN($(this).val())"/>
           </div>
       </div>
   </form>
    <div class="col-xs-12 col-sm-12 cl text-center">
        <button type="button" onclick="recharge()" class='btn btn-hot btn-md margin-right-30'>充值</button>
        <button type="button" class="btn btn-default btn-md layui-layer-close">取消</button>
    </div>
</article>
<script>
    function recharge() {
        ajaxSubmit('<?= \yii\helpers\Url::to(['a-b/do-recharge'])?>', $('#rechargeForm').serialize(), function(){
            layer.closeAll();
            DataTable.drawTable();
        });
    }
</script>
