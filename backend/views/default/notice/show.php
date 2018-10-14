<article class="page-container">
    <div class="col-xs-12 article-box">
        <div class="article-header">
            <p class="article-title"><?= $title?></p>
            <p class="article-time">发布时间：<?= $create_time?></p>
        </div>
        <div class="article-body col-xs-12">
            <?= $content?>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 cl text-center margin-top-20 margin-bottom-10">
        <button type="button" onclick="layer.closeAll();" class="btn btn-default btn-md">关闭</button>
    </div>
</article>
