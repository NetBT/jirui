<div class="main-page col-xs-12">
    <div class="main-header col-xs-12">
        <div class="header-title col-xs-4"><b>下载简历列表</b>List of Download</div>
        <div class="header-search col-xs-7">

            <div class="col-xs-3 pull-right text-r">
                <button class="btn btn-success" id="refresh">刷 新</button>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="main-body col-xs-12">
        <table class="tab" width="100%" border="0" cellspacing="0" cellpadding="0" id="resumeInvitationList"></table>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function()
    {
        DataTable.init('initResumeInvitationList', '#resumeInvitationList', '<?= \yii\helpers\Url::to(['recruit/list-resume','type' => 'download']);?>', {});

        //刷新
        $("#refresh").bind('click',function(){
            DataTable.drawTable();
        });
    });

    var resumeInvitation = {
        showResumeUrl : '<?= \yii\helpers\Url::to(['recruit/show-resume'])?>',
        showRecruitUrl : '<?= \yii\helpers\Url::to(['resume/show-recruit-post'])?>',
        showDownloadResumeUrl : '<?= \yii\helpers\Url::to(['recruit/resume-download'])?>',
        showResumeModal  : function(id)
        {
            layer_show({id : id},'简历详情', this.showResumeUrl,1000);
        },

        showRecruitModal  : function(id)
        {
            layer_show({id : id},'职位详情', this.showRecruitUrl,1000);
        },

        downModal  : function(id)
        {
            layer_show({id : id},'简历详情', this.showDownloadResumeUrl,1000);
        },

    };

</script>
