<?
/**
 * @var $this \yii\web\View
 * @var $order_number
 * @var $combo_order_number
 * @var $goods_code
 * @var $comboOrder \backend\models\MemberOrderCombo
 * @var $images \backend\models\MemberOrderImage[]
 */
\backend\assets\FancyBoxAsset::register($this);
$i = 0;
?>
<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-12"><b>图片精选（点击图片进入精选）</b></div>
        <div class="col-xs-12"><h4 class="col-xs-12">订单#<?= $comboOrder->order_number ?></h4></div>
        <div class="col-xs-12">
            <div class="col-xs-2">拍摄时间：</div>
            <div class="col-xs-3 text-info"><?= $comboOrder->viewShootFinishTime() ?></div>
            <div class="col-xs-2">顾客称呼：</div>
            <div class="col-xs-2 text-info"><?= $comboOrder->member->name ?></div>
        </div>
        <div class="col-xs-12">
            <div class="col-xs-2">订单状态：</div>
            <div class="col-xs-2 text-info">正在选片中</div>
            <div class="col-xs-2 col-xs-offset-10">
                <button id="save" type="submit" class="btn btn-sm btn-primary">确定</button>
            </div>
        </div>
    </div>
    <div class="main-body col-xs-12">
        <? if ($errors): ?>
            <div class="col-sm-9 text-danger">
                <ul>
                    <? foreach ($errors as $error): ?>
                        <li><?= $error ?></li>
                    <? endforeach; ?>
                </ul>
            </div>
        <? endif; ?>
        <div class="fancybox-thumbs__list">
            <form id="main-form" method="post" action="<?= \yii\helpers\Url::to(['member-order/accept']) ?>">
                <input type="hidden" name="order_number" value="<?= $comboOrder->order_number ?>">
                <input type="hidden" name="combo_order_number" value="<?= $comboOrder->combo_order_number ?>">
                <input type="hidden" name="goods_code" value="<?= $goods_code ?>">
                <input type="hidden" name="images_key" value="<?= $images_key ?>">
                <div id="imagelist">
                    <? foreach ($images as $image): ?>
                        <a href="<?= $image->getImageUrl() ?>" data-fancybox="images"
                           style="width:240px;height:160px;max-width: 240px;max-height: 160px;">
                            <img src="<?= $image->getImageUrl() ?>"
                                 style="margin:auto;max-width: 100%;max-height: 100%;"/>
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
        $('#save').click(function () {
            $('#main-form').submit();
        });
    })
</script>

