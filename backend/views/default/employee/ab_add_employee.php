<article class="page-container">

    <?php $form = \yii\bootstrap\ActiveForm::begin([
            'id' => 'addEmployee',
            'options' => ['class' => 'form form-horizontal'],
            'fieldConfig'=>[
                'inputOptions'=>['class'=>'form-control input-text'],//改变input输入框
//                'option' => [],//改变外层自动生成的div
//                'labelOptions' => [],//改变label
                'labelOptions' => [
                    'class' => 'form-label col-xs-3 text-right'
                ]
            ],
            'method' => 'post',
            'enableAjaxValidation' => true,
            'validationUrl' => \yii\helpers\Url::to(['employee/validate-form', 'type' => 'addEmployee']),
        ]
    );
    ?>
    <?= $form->field($model,'id',[
        'template' => '{input}',
        'options' => ['class' => ''],
    ])->hiddenInput(['id' => 'employeeId'])?>

    <div class="vip">
        <div class="vip_top" style="margin-bottom: 0">
            <p class="bt"><b>职位信息</b></p>
            <div class="clear"></div>
        </div>
        <div class="vip_box" style="margin-bottom:10px;padding:0 40px;">
            <div class="vip_box_input">
                <a class="box">选择职位</a>
                <div class="col-xs-6 col-sm-5 cl text-center">
                    <?= $form->field($model,'post_id',[
                        'template' => '{label} <div class="formControls col-xs-6">{input}</div>',
                        'labelOptions' => [
                            'class' => 'form-label col-xs-3 text-right'
                        ]
                    ])->dropDownList(\backend\models\EmployeePost::getFormList(),[
                        'class' => 'selectpicker',
                        'id' => 'selectPost',
                        'onChange' => 'changePost()',
                    ])?>
                </div>

                <div class="col-xs-6 col-sm-5 cl text-center" id="colorArea" style="display: none">
                    <?= $form->field($model,'photographer_color',[
                        'template' => '{label} <div class="formControls col-xs-5">{input}{error}<label class="background" id="label-background"></label></div>',
                        'errorOptions' => [
                            'tag' => 'label',
                            'class' => 'error'
                        ],
                        'labelOptions' => [
                            'class' => 'form-label col-xs-3 text-right'
                        ]
                    ])->textInput(['onChange' => 'changeColor(this)','placeholder'=>"颜色",'style' => ['width' => '120px', 'height' => '32px']])->label('颜色')?>
                </div>
            </div>

            <div class="clear"></div>
        </div>
    </div>

    <div class="vip">
        <div class="vip_top clear-margin-bottom">
            <p class="bt"><b>员工信息</b></p>
            <div class="clear"></div>
        </div>
        <div class="vip_box clear-margin-bottom" style="margin-bottom:10px;padding:0 40px;">
            <div class="vip_box_input" style="height:65px;">
                <a class="box">筛选条件</a>
                <input type="text" id="employeeName" style="width: 178px;" placeholder="输入要搜索的员工名称">
                <a class="btn" href="javascript:void(0);" onclick="getEmployeeInfo()">查询</a>
                <div id="memberSimpleInfo">
                    <!--                    <p class="right">姓名：大海</p><p class="right">余额：300</p><p class="right">积分：2000</p>-->
                </div>
            </div>
            <div class="vip_tab">
                <p class="tab"><a class="hover" style="width: 155px;">详细信息</a></p>
                <table class="top" width="100%" border="0" cellspacing="0" cellpadding="0" id="employeeTable">
                    <thead>
                    <tr>
                        <th scope="col">账号</th>
                        <th scope="col">姓名</th>
                        <th scope="col">性别</th>
                        <th scope="col">电话</th>
                        <th scope="col">生日</th>
                        <th scope="col">微信</th>
                        <th scope="col">年龄</th>
                        <th scope="col">QQ</th>
                        <th scope="col">民族</th>
                        <th scope="col">邮箱</th>
                        <th scope="col">学历</th>
                        <th scope="col">省份</th>
                        <th scope="col">学校</th>
                        <th scope="col">地址</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <?php \yii\bootstrap\ActiveForm::end(); ?>
    <div class="col-xs-12 col-sm-12 cl text-center margin-top-20 margin-bottom-10">
        <button type="button" onclick="add()" class='btn btn-hot btn-md margin-right-30'>添加</button>
        <button type="button" class="btn btn-default btn-md layui-layer-close">取消</button>
    </div>
</article>

<style>
    a.btn{
        width: 65px !important;
        line-height: 28px !important;
    }


</style>
<script>
    $(function(){

        $('#employee-photographer_color').colorpicker({
            format : "hex",
        });

        $("#birthday").on('click', function() {
            WdatePicker({readOnly: true, dateFmt: 'yyyy-MM-dd'});
        });
        $("#selectDegree").selectpicker({
            title: '请选择学历',
            style: 'btn-default',
            width: '100%',
            liveSearch: true
        });
        $("#selectNation").selectpicker({
            title: '请选择民族',
            style: 'btn-default',
            width: '100%',
            liveSearch: true,
            dropupAuto : false
        });

        $("#selectPost").selectpicker({
            title: '请选择职位',
            style: 'btn-default',
            width: '100%',
//            liveSearch: true,
            dropupAuto : false
        });
    });

    //改变职位的变化
    function changePost()
    {
        $.ajax({
            url : '<?= \yii\helpers\Url::to(['employee/change-a-b-post'])?>',
            type : 'POST',
            async: false,
            data : {postId : $('#selectPost').selectpicker('val')},
            dataType : 'JSON',
            success: function(data)
            {
                if (data.code == 1000) {
                    if (data.data == true) {
                        $('#colorArea').css('display', 'block')
                    } else {
                        $('#colorArea').css('display', 'none')
                    }

                } else {
                    layer.msg(data.message, {icon: 5, time: 2000});
                }
            },
            error: function()
            {
                layer.msg('网络错误',{icon:5,time:2000});
            }
        });
    }


    //改变颜色的变化
    function changeColor(obj)
    {
        var $obj = $(obj);
        var color = $obj.val();
        $('#label-background').css('background-color',color);
    }

    function getEmployeeInfo()
    {
        $.ajax({
            url : '<?= \yii\helpers\Url::to(['employee/get-employee-info-by-where'])?>',
            type : 'POST',
            async: false,
            data : {name: $('#employeeName').val()},
            dataType : 'JSON',
            success: function(data)
            {
                if (data.code == 1000) {
                    if(data.data) {
                        var info = data.data;
                        var employeeInfo = '';
                        employeeInfo += '<tr>';
                        employeeInfo += '<td>'+info.login_name+'</td>';
                        employeeInfo += '<td>'+info.employee_name+'</td>';
                        employeeInfo += '<td>'+info.sex+'</td>';
                        employeeInfo += '<td>'+info.tel+'</td>';
                        employeeInfo += '<td>'+info.birthday+'</td>';
                        employeeInfo += '<td>'+info.wechat+'</td>';
                        employeeInfo += '<td>'+info.QQ+'</td>';
                        employeeInfo += '<td>'+info.age+'</td>';
                        employeeInfo += '<td>'+info.nation+'</td>';
                        employeeInfo += '<td>'+info.email+'</td>';
                        employeeInfo += '<td>'+info.degree+'</td>';
                        employeeInfo += '<td>'+info.province+'</td>';
                        employeeInfo += '<td>'+info.school+'</td>';
                        employeeInfo += '<td>'+info.address+'</td>';
                        employeeInfo += '</tr>';

                        $('tbody','#employeeTable').html('');
                        $('tbody','#employeeTable').append(employeeInfo);

                        $('#employeeId').val(info.id);
                    }
                } else {
                    layer.msg(data.message, {icon: 5, time: 2000});
                }
            },
            error: function()
            {
                layer.msg('网络错误',{icon:5,time:2000});
            }
        });
    }


    function add() {

        var postId = $('#selectPost').selectpicker('val');
        var employeeId = $('#employeeId').val();

        if(!postId) {
            layer.msg('请选择职位',{icon:5,time:2000});
            return false;
        }

        if(!employeeId) {
            layer.msg('请选择员工信息',{icon:5,time:2000});
            return false;
        }

        ajaxSubmitForm('#addEmployee', '<?= \yii\helpers\Url::to(['employee/add-a-b-employee'])?>');
    }
</script>
