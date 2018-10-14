<article class="page-container col-xs-12">
    <button class="btn btn-success size-MINI" id="refresh">刷新</button>
    <div class="main-body col-xs-12">
        <table class="tab" width="100%" border="0" cellspacing="0" cellpadding="0" id="resumeForRecruitPostList"></table>
    </div>
</article>
<script>
    $(function(){
        DataTable.init('initResumeForRecruitPostList','#resumeForRecruitPostList','<?= \yii\helpers\Url::to(['recruit/list-resume'])?>',getResumeParams());
        //刷新
        $("#refresh").bind('click',function(){
            DataTable.drawTable();
        });
    });
    function getResumeParams (){
        return {recruitPostId : $('#hiddenRecruitPostId').val()};
    }

    var resumeForRecruitPost = {
        inviteUrl : '<?= \yii\helpers\Url::to(['recruit/invite-resume'])?>',
        showUrl : '<?= \yii\helpers\Url::to(['recruit/show-resume'])?>',

        showModal  : function(id)
        {
            layer_show({id : id},'简历详情', this.showUrl,1050);
        },

        inviteModal  : function(obj,id)
        {
            var _this = this;
            var $obj = $(obj);
            layer.confirm('是否发出面试邀请？',function(index){
                ajaxSubmit(_this.inviteUrl, {id: id}, function () {
                    $obj.attr('title','已面试');
                    $obj.attr('onclick','already()');
                    $obj.children().toggleClass('fa fa-heart-o');
                    $obj.children().toggleClass('fa fa-heart');
                });
            });
        },
    };

    function already()
    {
        layer.msg('已发出面试邀请',{icon:5,time:2000});
    }
</script>