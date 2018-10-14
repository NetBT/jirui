<article class="page-container">
    <div class="col-xs-12 advert-box">
        <div class="advert-body text-center col-xs-12">
            <?php if (empty($info)) {?>
            <h1>无广告</h1>
            <?php } else {?>
            <img style="width: 100%;" src="/uploads/<?= $info['advert_matter']?>" alt="<?= $info['advert_name']?>" />
            <?php }?>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 cl text-center margin-top-20 margin-bottom-10">
        <button type="button" onclick="layer.closeAll();" class="btn btn-default btn-md">关闭</button>
    </div>
</article>
