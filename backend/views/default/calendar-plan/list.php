<?php \backend\assets\FullcalendarAsset::register($this);?>
<link href='/js-unit/fullcalendar/fullcalendar.print.min.css' rel='stylesheet' media='print' />
<div class="main-page padding-top-5 col-xs-12">
    <div class="main-body col-xs-12" style="min-height: 100%;height:100%">
        <div class="col-xs-3 pull-right text-right">
<!--            <div class="col-xs-7 input-area">-->
<!--                <label class="col-xs-5 text-right">合同号或手机号：</label>-->
<!--                <div class="col-xs-7">-->
<!--                    <input type="text" value="" class="form-control" id="JMSnumber" placeholder="请输入合同号或手机号">-->
<!--                </div>-->
<!--            </div>-->
            <button class="btn btn-yellow" onclick="addCalender();">添加排项</button>
        </div>
        <div id='calendar' style="height: 100%"></div>
    </div>
</div>
<script>

    $(document).ready(function() {
        var orderNumber = '<?= isset($orderNumber) && !empty($orderNumber) ? $orderNumber : ''?>';
        $('#calendar').fullCalendar({
            header: {
                left: 'agendaWeek,month',
                center: 'prev title next today',
                right: false
            },
            height: 650,
            navLinks: true, // can click day/week names to navigate views
            nowIndicator: true,
            selectable: true,
            locale: 'zh-cn',
            timeZone: 'UTC',
            fixedWeekCount: false, //设置月试图时显示的周数，默认true。如果是true则每次固定显示6周，如果设置为false，则每月根据实际周数显示4，5，6周。
            selectHelper: true,
            select: function(start, end) {
                layer_show({date:start.format("Y-MM-DD")}, '添加排项', '<?= \yii\helpers\Url::to(['calendar-plan/add', 'orderNumber' => $orderNumber])?>', 800);
            },
//            eventClick: function (event, jsEvent, view) {
//                layer_show({id: event.id}, '修改排项：' + event.title, '<?//= \yii\helpers\Url::to(['calendar-plan/edit'])?>//', 800);
//                editEvent = event;
//            },
            dragOpacity: {//设置拖动时事件的透明度
                agenda: .5,
                '':.6
            },
            eventDrop: function(event, dayDelta, revertFunc) {
                layer.confirm('是否更改排期?',{icon: 0, title: '提示'}, function(){
                    $.ajax({
                        url:'<?= \yii\helpers\Url::to(['calendar-plan/do-drop'])?>',
                        type: 'POST',
                        async: true,
                        data: {
                            id:event.id,
                            start: event.start.format("Y-MM-DD hh:mm:ss"),
                            end: event.end.format("Y-MM-DD hh:mm:ss")
                        },
                        success: function (result) {
                            if(result.code === 2000) {
                                layer.msg(result.message, {icon: 2});
                                revertFunc();
                            }
                            if (result.code == 1000) {
                                layer.msg('修改成功', {icon: 1});
                                refreshCalendar();
                            }
                        },
                        error: function () {
                            layer.msg('网络错误', {icon: 2});
                            revertFunc();
                        }
                    });
                }, revertFunc());
            },
            editable: true,
            eventLimit: true, // allow "more" link when too many events
            events: {
                url: '<?= \yii\helpers\Url::to(['calendar-plan/list-data'])?>',
                cache: true,
                data: {orderNumber: orderNumber},
                type: 'post',
                error: function () {
                    layer.msg('出现错误', {icon: 2});
                }
            },
            eventAfterRender : function(event, element, view) {
                var id = event.id;
                var title = event.title;
              $('.fc-title-'+id).attr('ondblclick','editModal('+id+',\''+title+'\',event)');
            }
        });
    });
    var editEvent = null;

    function editModal(id,title,event)
    {
        layer_show({id: id}, '修改排项：' + title, '<?= \yii\helpers\Url::to(['calendar-plan/edit'])?>', 800);
        editEvent = event;
    }

    function refreshCalendar() {
        $('#calendar').fullCalendar('refetchEvents');
    }

    function addCalender(){
        layer_show({date:'<?= date('Y-m-d')?>'}, '添加排项', '<?= \yii\helpers\Url::to(['calendar-plan/add', 'orderNumber' => $orderNumber])?>', 800);
    }
    function editCalender() {
        $.ajax({
            url: '<?= \yii\helpers\Url::to(['calendar-plan/do-edit'])?>',
            type: 'POST',
            async: false,
            data: $("#edit").serialize(),
            dataType: 'JSON',
            beforeSend: function () {
                if ($(".has-error", "#edit").length) {
                    return false;
                }
            },
            success: function (data) {
                layer.closeAll('page');
                if (data.code == 1000) {
                    editEvent.title = data.data.title;
                    editEvent.start = data.data.start;
                    editEvent.end = data.data.end;
//                    editEvent.textColor = data.data.color;
                    editEvent.id = data.data.id;
//                    $('#calendar').fullCalendar('updateEvent', editEvent);
                    refreshCalendar();

                }
                layer.msg(data.message, {icon: 6, time: 2000});
            },
            error: function () {
                layer.msg('网络错误', {icon: 5, time: 2000});
            }
        });
    }
    function deleteCalender() {
        var id = $("input[name='AbCalendarPlan[id]']", '#edit').val();
        $.ajax({
            url: '<?= \yii\helpers\Url::to(['calendar-plan/do-delete'])?>',
            type: 'POST',
            async: false,
            data: {id: id},
            dataType: 'JSON',
            success: function (data) {
                layer.closeAll('page');
                if (data.code == 1000) {
                    $('#calendar').fullCalendar('removeEvents', editEvent);
                }
                layer.msg(data.message, {icon: 6, time: 2000});
            },
            error: function () {
                layer.msg('网络错误', {icon: 5, time: 2000});
            }
        });
    }

</script>
