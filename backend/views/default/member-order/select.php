<?
/**
 * @var $this \yii\web\View
 * @var $comboOrder \backend\models\MemberOrderCombo
 */
\backend\assets\FancyBoxAsset::register($this);
$i = 0;
?>
<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-12"><b>开始粗选</b></div>
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
                <button id="save" type="submit" class="btn btn-sm btn-primary">粗选完成</button>
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
            <form id="main-form" method="post" action="<?= \yii\helpers\Url::to([
                'member-order/goods-select',
                'combo_order_number' => $comboOrder->combo_order_number
            ]) ?>">
                <div id="imagelist">
                    <? foreach ($comboOrder->memberOrder->images as $image): ?>
                        <div class="form-group col-sm-3" style="max-width:280px">
                            <div class="col-sm-12 clear-padding">
                                <a href="<?= $image->getImageUrl() ?>"
                                   data-fancybox style="width:240px;height:160px;max-width: 100%;">
                                    <img src="<?= $image->getImageUrl() ?>"
                                         style="margin:auto;max-width: 100%;max-height:100%"/>
                                </a>
                            </div>
                            <span class="combo-select btn btn-sm btn-primary col-sm-12 clear-padding"
                                  style="width:100%;margin-left: 2px;max-width: 240px;" title="点击+添加，点击-移除">
                                <i class="fa fa-plus" style="padding:5px"></i>
                        <input title="" name="images[]" type="checkbox" value="<?= $image->id ?>"
                               style="z-index:-1;position: absolute;top:2px;left:2px"/>
                            </span>
                        </div>
                    <? endforeach; ?>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(function () {
        $('.combo-select').click(function () {
            let $combo = $(this);
            $combo.find('i').toggleClass('fa-plus fa-minus');
            $combo.toggleClass('btn-primary btn-danger');
            $combo.find('input').prop('checked', $combo.hasClass('btn-danger'));
        });
        $('#save').click(function () {
            $('#main-form').submit();
        });
    })
</script>