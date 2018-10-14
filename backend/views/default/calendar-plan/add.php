
<article class="page-container col-xs-12">
    <?php $form = \yii\bootstrap\ActiveForm::begin([
            'id' => 'add',
            'options' => ['class' => 'form form-horizontal'],
            'fieldConfig'=>[
                'hintOptions' => [                                 //修改hint样式和标签
                    'tag' => 'label',
                    'class' => 'hint'
                ],
            ],
            'method' => 'post',
            'enableAjaxValidation' => true,
            'validationUrl' => \yii\helpers\Url::to(['calendar-plan/validate-form', 'type' => 'add']),
        ]
    );
    ?>
    <div class="col-xs-12 cl text-center">
    <?= $form->field($model,'AB_order_number',[
        'template' => '{label} <div class="formControls col-xs-5">{input}{error}{hint}</div><div class="formControls col-xs-2">
            <button type="button" class="btn btn-primary size-M" onclick="quoteOrderInfo()">查询</button>
        </div>',
        'errorOptions' => [
            'tag' => 'label',
            'class' => 'error'
        ],
        'labelOptions' => [
            'class' => 'form-label col-xs-3 text-right'
        ]
        ])->textInput(['placeholder'=>"订单号/手机号", 'value' => $orderNumber, 'class' => 'input-text form-control '])->hint('*必填项')?>
    </div>
    <div class="col-xs-12 vip_box_bottom cl text-center calendar-info-table" id="orderTableInfo">
        <table class="table">
            <thead>
                <th>会员姓名</th>
                <th>年龄</th>
                <th>性别</th>
                <th>套系</th>
                <th>服装套数</th>
                <th>选择</th>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    <div class="col-xs-12 cl text-center">
        <?= $form->field($model,'start',[
            'template' => '{label} <div class="formControls col-xs-3">{input}{error}{hint}</div>',
            'errorOptions' => [
                'tag' => 'label',
                'class' => 'error'
            ],
            'labelOptions' => [
                'class' => 'form-label col-xs-3 text-right'
            ],
        ])->textInput(['placeholder'=>"开始时间", 'id' => 'dateTime', 'value'=> $date, 'class' => 'input-text form-control '])->hint('*必填项')?>
    </div>
    <div class="col-xs-12 cl text-center margin-top-15" id="jsEndTimeBox">
        <div class="form-group">
            <label class="form-label col-xs-3 text-right" for="endTime">时间段</label>
            <div class="formControls col-xs-3">
                <select class="selectpicker" id="selectTimeSlot" name="timeSlot" data-live-search="false">
                    <?php foreach (\backend\models\ABCommon::getTimeSlot() as $k => $v) :?>
                    <option value="<?= $k?>"><?= $v?></option>
                    <?php endforeach;?>
                </select>
                <label class="error"></label>
                <label class="hint">*必填项</label>

            </div>
        </div>
    </div>
    <div class="col-xs-12 cl text-center margin-top-15">
        <?= $form->field($model,'cameraman',[
            'template' => '{label} <div class="formControls col-xs-5">{input}{hint}</div>',
            'labelOptions' => [
                'class' => 'form-label col-xs-3 text-right'
            ]
        ])->dropDownList(\backend\models\Employee::getFormListFormByType(true, \common\models\Status::EMPLOYEE_POST_TYPE_SHEYING), [
            'class' => 'selectpicker form-control',
            'id' => 'selectCameraman',
            'data-title' => '请选择摄影师'

        ])->hint('*必选项')?>
    </div>
    <div class="col-xs-12 cl text-center">
        <?= $form->field($model,'assistant',[
            'template' => '{label} <div class="formControls col-xs-5">{input}{hint}</div>',
            'labelOptions' => [
                'class' => 'form-label col-xs-3 text-right'
            ]
        ])->dropDownList(\backend\models\Employee::getFormListFormByType(true, \common\models\Status::EMPLOYEE_POST_TYPE_ZHULI), [
            'class' => 'selectpicker form-control',
            'id' => 'selectAssistant',
            'data-title' => '请选择助理'

        ])->hint('*必选项')?>
    </div>
    <div class="col-xs-12 cl text-center">
        <?= $form->field($model,'dresser',[
            'template' => '{label} <div class="formControls col-xs-5">{input}{hint}</div>',
            'labelOptions' => [
                'class' => 'form-label col-xs-3 text-right'
            ],

        ])->dropDownList(\backend\models\Employee::getFormListFormByType(true, \common\models\Status::EMPLOYEE_POST_TYPE_HUAZUANG), [
            'class' => 'selectpicker form-control',
            'id' => 'selectDresser',
            'data-title' => '请选择化妆师'
        ])->hint('*必选项')?>
    </div>

    <div class="col-xs-12 cl text-center">
        <?= $form->field($model,'mark',[
            'template' => '{label} <div class="formControls col-xs-8">{input}{error}</div>',
            'errorOptions' => [
                'tag' => 'label',
                'class' => 'error'
            ],
            'labelOptions' => [
                'class' => 'form-label col-xs-3 text-right'
            ]
        ])->textarea(['placeholder'=>"请输入日程说明", 'class' => 'form-control input-text','rows' => "3"])?>
    </div>
    <?php \yii\bootstrap\ActiveForm::end(); ?>
    <div class="col-xs-12 col-sm-12 cl text-center">
        <button type="button" onclick="add()" class='btn btn-hot btn-md margin-right-30'>添加</button>
        <button type="button" onclick="layer.closeAll()" class="btn btn-default btn-md">取消</button>
    </div>
</article>
<style>
    body .demo-class{
        width: 40% !important;
        left : 60% !important
    }
</style>
<script>
    $(function(){
        $("#dateTime").on('click', function() {
            WdatePicker({
                readOnly: true,
                dateFmt: 'yyyy-MM-dd',
                minDate:'%y-%M-%d'});
        });
        $("#selectDresser, #selectAssistant, #selectCameraman").selectpicker({
            style: 'btn-default',
            width: '100%',
            liveSearch: true
        });
        $("#selectTimeSlot").selectpicker({
            style: 'btn-default',
            width: '100%',
            liveSearch: false
        });


        if ($("input[name='AbCalendarPlan[AB_order_number]']").val() != '') {
            quoteOrderInfo();
        }
    });

    function add() {

        $.ajax({
            url : '<?= \yii\helpers\Url::to(['calendar-plan/do-add'])?>',
            type : 'POST',
            async: false,
            data : $("#add").serialize(),
            dataType : 'JSON',
//            beforeSend: function ()
//            {
//                if ($(".has-error", "#add").length)
//                {
//                    return false;
//                }
//            },
            success: function(data)
            {
                if(data.code == 1000)
                {
                    layer.closeAll('page');
                    var eventData = {
                        title: data.data.title,
                        start: data.data.start,
                        end: data.data.end,
                        id: data.data.id
                    };
                    $('#calendar').fullCalendar('renderEvent', eventData, true);
                }
                layer.msg(data.message,{icon:6,time:2000});
            },
            error: function()
            {
                layer.msg('网络错误',{icon:5,time:2000});
            }
        });
    }

    function quoteOrderInfo(){
        var obj = $("input[name='AbCalendarPlan[AB_order_number]']");
        var orderNumber = obj.val();
        $.post('<?= \yii\helpers\Url::to(['member-order/quote-order-info'])?>', {orderNumber: orderNumber}, function (result) {
            if (result.code == 1000) {
                var html = '';
                var i = 0;
                if (result.data.length>0) {
                    for(i==0;i<result.data.length;i++){
                        var row = result.data[i];
                        html += '<tr>';
                        html += '<td>' + row.name + '</td>';
                        html += '<td>' + row.age + '</td>';
                        html += '<td>' + row.sex + '</td>';
                        html += '<td>' + row.combo_name + '</td>';
                        html += '<td>' + row.clothe + '</td>';
                        html += '<td><input class="danxuan" type="radio" value="'+ row.combo_order_number + '" name="danxuan"></td>';
                        html += '</tr>';
                    }
                }
                $("tbody", "#orderTableInfo").html(html);

                $("input[name='danxuan']").click(function (e) {
                    $("input[name='AbCalendarPlan[AB_order_number]']").val($(this).val());
                })
            } else {
                layer.msg(result.message, {icon: 2});
            }
        }).error(function () {
            layer.msg('网络错误', {icon: 2});
        });

    }
</script>
