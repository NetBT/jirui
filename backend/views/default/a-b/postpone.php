<article class="page-container col-xs-12">
   <form id="postponeForm">
       <div class="col-xs-12 col-sm-12 form-group cl text-center">
           <label class="form-label col-xs-3 text-right">合同编号</label>
           <div class="formControls col-xs-8">
               <input type="text" disabled class="input-text" value="<?= $info['AB_number']?>" />
               <input type="hidden" readonly  name="id" class="input-text" value="<?= $info['id']?>" />
           </div>
       </div>
       <div class="col-xs-12 form-group col-sm-12 cl text-center">
           <label class="form-label col-xs-3 text-right">店铺名称</label>
           <div class="formControls col-xs-8">
               <input type="text"  disabled class="input-text" value="<?= $info['AB_name']?>" />
           </div>
       </div>
       <div class="col-xs-12 form-group col-sm-12 cl text-center">
           <label class="form-label col-xs-3 text-right">延期</label>
           <div class="formControls col-xs-4">
               <input type="text" class="input-text" name="postponeNum" value="1" />
           </div>
           <div class="formControls col-xs-4">
               <select class="selectpicker" name="timeUnit">
                   <?php
                    $timeUnit = \common\models\Status::AbPostponeTimeUnitMap();
                    foreach ($timeUnit as $k => $v) :
                   ?>
                   <option value="<?= $k?>"><?= $v?></option>
                   <?php endforeach;?>
               </select>
           </div>
       </div>
       <div class="col-xs-12 col-sm-12 form-group cl text-center">
           <label class="form-label col-xs-3  text-r">付款方式</label>
           <div class="formControls col-xs-8 text-l">
               <?php
               $payWay = \common\models\Status::AbPostponePayWayMap();
               foreach ($payWay as $k => $v) :?>
               <label class="radio-inline">
                   <input type="radio" name="payWay" <?= $k == \common\models\Status::AB_POSTPONE_PAY_WAY_WECHAT ? 'checked' : 0?> value="<?= $k?>"> <?= $v?>
               </label>
               <?php endforeach;?>
           </div>
       </div>
   </form>
    <div class="col-xs-12 col-sm-12 cl text-center">
        <button type="button" onclick="postpone()" class='btn btn-hot btn-md margin-right-30'>延期</button>
        <button type="button" class="btn btn-default btn-md layui-layer-close">取消</button>
    </div>
</article>
<script>
    $(function () {
       $(".selectpicker").selectpicker({
           width: '100%',
           style: 'btn-default'
       });
    });
    function postpone() {
        ajaxSubmit('<?= \yii\helpers\Url::to(['a-b/do-postpone'])?>', $('#postponeForm').serialize(), function(){
            layer.closeAll();
            DataTable.drawTable();
        });
    }
</script>
