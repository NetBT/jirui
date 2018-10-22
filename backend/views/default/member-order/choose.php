<?
/**
 * @var $this \yii\web\View
 * @var $order_number
 * @var $combo_order_number
 * @var $goods_id
 * @var $comboOrder \backend\models\MemberOrderCombo
 * @var $images \backend\models\MemberOrderImage[]
 */
\backend\assets\FancyBoxAsset::register($this);
$i = 0;
?>
<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-12"><b>开始选片</b>List of Member Order</div>
    </div>
    <div class="main-body col-xs-12">
        <div class="fancybox-thumbs__list">
            <form method="post" action="<?= \yii\helpers\Url::to(['member-order/accept']) ?>">
                <button type="submit" class="btn btn-sm btn-primary">确定</button>
                <input type="hidden" name="order_number" value="<?= $order_number ?>">
                <input type="hidden" name="combo_order_number" value="<?= $combo_order_number ?>">
                <input type="hidden" name="goods_id" value="<?= $goods_id ?>">
                <div id="imagelist">
                    <? foreach ($images as $image): ?>
                        <a href="<?= $image->getImageUrl() ?>" data-fancybox="images">
                            <img src="<?= $image->getImageUrl() ?>" width="100" height="75"/>
                            <input class="hide" type="checkbox" name="images[]" checked value="<?= $image->id ?>">
                        </a>
                    <? endforeach; ?>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(function () {
        $(document).on('afterClose.fb', function (e, instance, slide) {
            $.fancybox.destroy();
            $('#imagelist a').fancybox();
        });
    })
</script>

