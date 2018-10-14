<article class="page-container">
    <div id="downloadContent">
        <h1 style="text-align: center"><?= $info['resume_title']?></h1>
        <!--        <h3>编号：000001</h3>-->
        <table border="1" cellpadding="3" cellspacing="0" >
            <tr >
                <td width="93" valign="center" colspan="2" >姓名</td>
                <td width="160" valign="center" colspan="2" ><?= $info['name']?></td>
                <td width="93" valign="center" colspan="2" >性别</td>
                <td width="72" valign="center" colspan="2" ><?= $info['sex']?></td>
                <td width="88" valign="center" colspan="2" >年龄</td>
                <td width="89" valign="center" colspan="3" ><?= $info['age']?></td>

                <!--               <td width="125" colspan="2" rowspan="4" align="center" valign="middle" ></td>-->
            </tr>
            <tr >
                <td width="88" valign="center" colspan="2" >民族</td>
                <td width="89" valign="center" colspan="2" ><?= $info['nation']?></td>
                <td width="68" valign="center" colspan="2" >学历</td>
                <td width="87" valign="center" colspan="2" ><?= $info['degree']?></td>
                <td width="89" valign="center" colspan="2" >毕业院校</td>
                <td width="156" valign="center" colspan="3" ><?= $info['school']?></td>
            </tr>
            <tr >
                <td width="93" valign="center" colspan="2" >婚姻</td>
                <td width="72" valign="center" colspan="2" ><?= $info['marriage']?></td>

                <td width="68" valign="center" colspan="2">婚姻状况</td>
                <td width="87" valign="center" colspan="2" >xxx</td>
                <td width="88" valign="center" colspan="2" ></td>
                <td width="89" valign="center" colspan="3" ></td>
            </tr>
            <tr >
                <td width="93" valign="center" colspan="2" >手机</td>
                <td width="160" valign="center" colspan="2" ><?= $info['tel']?></td>
                <td width="89" valign="center" colspan="2" >Email</td>
                <td width="156" valign="center" colspan="2" ><?= $info['email']?></td>
                <td width="89" valign="center" colspan="2" >微信</td>
                <td width="156" valign="center" colspan="3" ><?= $info['wechat']?></td>
            </tr>

            <tr >
                <td width="93" valign="center" colspan="2"  style="width:93px;">籍贯</td>
                <td width="530" valign="center" colspan="11" ><?= $info['province']?></td>
            </tr>

            <tr >
                <td width="93" valign="center" colspan="2"  style="width:93px;">家庭住址</td>
                <td width="530" valign="center" colspan="11" ><?= $info['address']?></td>
            </tr>
            <tr >
                <td width="93" valign="center" colspan="2" rowspan="3">求职意向</td>
                <td width="93" valign="center" colspan="2">工作年限</td>
                <td width="200" valign="center" colspan="9"><?= $info['working_duration']?></td>

            </tr>
            <tr>
                <td width="93" valign="center" colspan="2">希望薪资</td>
                <td width="200" valign="center" colspan="9"><?= $info['expected_salary']?>元/月</td>

            </tr>
            <tr>
                <td width="93" valign="center" colspan="2" >目前状况</td>
                <td width="200" valign="center" colspan="9" ><?= $info['working_status']?></td>
            </tr>

            <?php if(isset($education) && !empty($education)) : ?>
                <tr >
                    <td width="93" valign="center" colspan="2" rowspan="<?= count($education) + 1?>">教育经历</td>
                    <td width="93" valign="center" colspan="3">毕业时间</td>
                    <td width="93" valign="center" colspan="4">学校名称</td>
                    <td width="93" valign="center" colspan="4">专业</td>
                </tr>
                <?php foreach ($education as $key => $value) : ?>
                    <tr >
                        <td width="93" valign="center" colspan="3"><?= $value['final']?></td>
                        <td width="93" valign="center" colspan="4"><?= $value['school_name']?></td>
                        <td width="93" valign="center" colspan="4"><?= $value['major']?></td>
                    </tr>
                <?php endforeach;?>
            <?php else:?>
                <tr >
                    <td width="93" valign="center" colspan="2" rowspan="1">教育经历</td>
                    <td width="93" valign="center" colspan="11">未填写</td>
                </tr>
            <?php endif;?>

            <?php if(isset($working) && !empty($working)) : ?>
                <tr >
                    <td width="93" valign="center" colspan="2" rowspan="<?= count($working) + 1?>">工作经历</td>
                    <td width="93" valign="center" colspan="3">工作时间</td>
                    <td width="93" valign="center" colspan="4">公司名称</td>
                    <td width="93" valign="center" colspan="4">职位</td>
                </tr>
                <?php foreach ($working as $key => $value) : ?>
                    <tr >
                        <td width="93" valign="center" colspan="3"><?= $value['start_time'] .'-'. $value['end_time']?></td>
                        <td width="93" valign="center" colspan="4"><?= $value['company_name']?></td>
                        <td width="93" valign="center" colspan="4"><?= $value['post_name']?></td>
                    </tr>
                <?php endforeach;?>
            <?php else:?>
                <tr >
                    <td width="93" valign="center" colspan="2" rowspan="1">工作经历</td>
                    <td width="93" valign="center" colspan="11">未填写</td>
                </tr>
            <?php endif;?>


            <tr>
                <td width="93" valign="center" >自我评价</td>
                <td width="570" valign="center" colspan="12" ><?= $info['self_assessment']?></td>
            </tr>
        </table>
    </div>


    <div class="col-xs-12 col-sm-12 cl text-center margin-top-10 margin-bottom-10">
<!--        --><?php //if($info['is_download'] == \common\models\Status::RESUME_DOWNLOAD_YES) : ?>
<!--            <button type="button" onclick="already()" class='btn btn-default btn-md margin-right-30 disabled'>已下载</button>-->
<!--        --><?php //else:?>
            <button type="button" onclick="save(this,<?= $info['send_resume_id']?>)" class='btn btn-hot btn-md margin-right-30'>下载</button>
<!--        --><?php //endif;?>
    </div>
</article>


<script  language="javascript" charset=GB2312" type="text/javascript">
    function save(obj,id)
    {
        var $obj = $(obj);
        layer.confirm('是否下载该该简历？',function(index){
            $.ajax({
                url : '<?= \yii\helpers\Url::to(['recruit/do-download'])?>',
                type : 'POST',
                async: false,
                data : {id: id,content : $('#downloadContent').html(),title : '<?= $info['resume_title']?>'},
                dataType : 'JSON',
                success: function(data)
                {
                    var callBackFunction = '';
                    if(data.code == 1000)
                    {
                        var url = data.message;
                        layer.close(layer.index);
                        window.location.href = '<?= \yii\helpers\Url::to(['recruit/done-download'])?>?url='+data.message;
//                        callBackFunction = function () {
//                            $obj.attr('onclick','already()');
//                            $obj.removeClass('btn-hot');
//                            $obj.addClass('btn-default disabled');
//                            $obj.html('已下载');
//                        }
                    } else {
                        layer.msg('下载失败。',{icon:5,time:2000});
                    }
                },
                error: function()
                {
                    layer.msg('网络错误',{icon:5,time:2000});
                }
            });
//            ajaxSubmit('<?//= \yii\helpers\Url::to(['recruit/do-download'])?>//', {id: id,content : $('#downloadContent').html(),title : '<?//= $info['resume_title']?>//'}, function () {
//                $obj.attr('onclick','already()');
//                $obj.removeClass('btn-hot');
//                $obj.addClass('btn-default disabled');
//                $obj.html('已下载');
//            });
        });
    }
</script>