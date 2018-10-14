<header class="navbar-wrapper">
    <div class="navbar navbar-fixed-top">
        <div class="container-fluid cl">
            <a class="logo navbar-logo f-l mr-10 hidden-xs" href="/">
                <img src="/uploads/<?= \common\models\Functions::getCommonByKey('web_logo') ?>" />
            </a>
            <a class="logo navbar-logo f-l mr-10 visible-xs" href="/">集瑞</a>


            <a aria-hidden="false" class="nav-toggle Hui-iconfont visible-xs" href="javascript:;"><i class="fa fa-bars"></i></a>
            <nav id="Hui-userbar" class="nav navbar-nav navbar-userbar hidden-xs">
                <ul class="cl">
                    <li><img src="<?= \yii\helpers\Url::to('@web/theme/default/images/tx.jpg')?>"></li>
                    <?php if((isset($this->params['employeeInfo'])) && (!empty($this->params['employeeInfo']))) :?>
                    <li><span class="welcome"> <?= $this->params['employeeInfo']['employee_name'] ?>, 欢迎你 </span> </li>
                    <?php endif;?>

                    <li id="Hui-msg">
                        <?php if(\backend\models\Common::getBusinessId()) : ?>
                        <a href="javascript:void(0)" title="设置" id="site"><i class="fa fa-cog"></i></a>
<!--                        <a href="#" title="消息"><span class="badge badge-danger">1</span><i class="fa fa-bell"></i></a>-->
                        <a href="javascript:void(0)" title="消息" id="message"><i class="fa fa-bell"></i></a>
                        <?php endif;?>
                        <a href="<?= \yii\helpers\Url::to(['login/logout'])?>" title="登出"><i class="fa fa-power-off"></i></a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</header>

<script>
    $(function(){
        $('#site').on('click',function(){
            $('.menu_dropdown').find('dt').removeClass('selected');
            $('.menu_dropdown').find('dd').hide();
            $('.menu_dropdown').find("a[data-title='店铺管理']",'li').parents('#menu-article').find('dt').addClass('selected');
            $('.menu_dropdown').find("a[data-title='店铺管理']",'li').parents('#menu-article').find('dd').show();
            $('.menu_dropdown').find("a[data-title='店铺管理']",'li').click();
        });

        $('#message').on('click',function(){
            $('.menu_dropdown').find('dt').removeClass('selected');
            $('.menu_dropdown').find('dd').hide();
            $('.menu_dropdown').find("a[data-title='消息管理']",'li').parents('#menu-article').find('dt').addClass('selected');
            $('.menu_dropdown').find("a[data-title='消息管理']",'li').parents('#menu-article').find('dd').show();
            $('.menu_dropdown').find("a[data-title='消息管理']",'li').click();
        });

    });
</script>