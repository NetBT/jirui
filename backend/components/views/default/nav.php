<aside class="Hui-aside">
    <div class="menu_dropdown bk_2">
        <?php if(isset($navList) && (!empty($navList))) : ?>
            <?php foreach ($navList as $first_k => $first_v) : ?>
                <dl id="menu-article">
                    <dt><img src="<?= \yii\helpers\Url::to('@web/theme/default/images/tb8.png')?>" /> <?= $first_v['module_title']?></dt>
                    <?php if((isset($first_v['children'])) && (!empty($first_v['children']))) : ?>

                        <dd>
                            <ul>
                                <?php foreach ($first_v['children'] as $second_k => $second_v) :?>
                                    <li><a class="menu-item" data-href="<?= \yii\helpers\Url::to([$second_v['module_url']])?>" data-title="<?= $second_v['module_title']?>" href="javascript:void(0)"><?= $second_v['module_title']?></a></li>
                                <?php endforeach;?>
                            </ul>
                        </dd>
                    <?php endif;?>

                </dl>
            <?php endforeach;?>
        <?php endif;?>
    </div>
</aside>
<script>
    $(function () {
       $('.menu-item ', ".Hui-aside").on('click', function () {
           $('.menu-item ', ".Hui-aside").removeClass('active');
           $(this).addClass('active');
       });
    });
</script>

