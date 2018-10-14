<?php
namespace backend\controllers;

use backend\models\AB;
use backend\models\ABGoodsImages;
use backend\models\ABStatement;
use backend\models\Common;
use backend\models\Recruit;
use backend\models\RecruitPost;
use backend\models\Resume;
use backend\models\ResumeEducationExperience;
use backend\models\ResumeWorkingExperience;
use backend\models\SendResume;
use common\models\Functions;
use Yii;
use yii\bootstrap\ActiveForm;

/**
 * Site controller
 */
class RecruitController extends CommonController
{

    //========================  总部招聘套餐  =================================
    public function actionComboList() {
        return $this->render('list');
    }
    /**
     * 该方法是异步校验字段，输入框失去焦点之后自动会自动请求改地址
     * @return array
     */
    public function actionValidateForm()
    {
        $type = Yii::$app->request->get('type');
        $editAdminModel = new Recruit(['scenario' => $type]);
        if (Yii::$app->request->isAjax && $editAdminModel->load(Yii::$app->request->post()))
        {

            $this->returnJson();
            return ActiveForm::validate($editAdminModel);
        }
    }
    /**
     * 返回列表信息
     */
    public function actionComboListData()
    {
        $this->returnJson();
        $model = new Recruit();
        return  $model->getListData();
    }

    public function actionAdd() {
        $addModel = new Recruit(['scenario' => 'add']);
        return $this->renderAjax('add', [
            'model' => $addModel,
        ]);
    }

    public function actionDoAdd() {
        $addModel = new Recruit(['scenario' => 'add']);
        $this->returnJson();
        if (!$addModel->load(\Yii::$app->request->post())){
            return Functions::formatJson(2000, '获取失败');
        }
        return $addModel->saveData();
    }

    public function actionEdit() {
        $id = \Yii::$app->request->post('id');
        $model = Recruit::findOne(['id' => $id]);
        $model->setScenario('edit');
        $this->layout = 'layer_main';
        $where['goods_id'] = $id;
        return $this->render('edit', [
            'model' => $model,
        ]);
    }

    public function actionDoEdit () {
        $addModel = new Recruit(['scenario' => 'edit']);
        $this->returnJson();
        if ($addModel->load(\Yii::$app->request->post()) && $addModel->saveData()){
            return Functions::formatJson(1000, '修改成功');
        }
        return Functions::formatJson(2000, '修改失败');
    }


    //========================  加盟商招聘职位  =================================

    public function actionIndexRecruitPost()
    {
        return $this->render('list_post');
    }

    public function actionListPost() {
        $recruitPostModel = new RecruitPost();
        $this->returnJson();
        return $recruitPostModel->handelInit($recruitPostModel->getListData());
    }

    public function actionAddEditPost()
    {
        $id = Yii::$app->request->post('id');
        $model = new RecruitPost(['scenario' => 'addEdit']);
        if($model->load(Yii::$app->request->post())){
            $this->returnJson();
            return $model->addEdit();
        } else {
            if($id){
                $model = RecruitPost::findOne(['id' => $id]);
                $model->setScenario('addEdit');
            } else {
                $abInfo = AB::getOneInfoById(Common::getBusinessId(),'AB_name');
                $model->business_name = $abInfo['AB_name'];
            }

            return $this->render("add_edit_post",['model' => $model]);
        }
    }

    /**
     * 该方法是异步校验字段，输入框失去焦点之后自动会自动请求改地址
     * @return array
     */
    public function actionValidatePostForm()
    {
        $type = Yii::$app->request->get('type');
        $model = new RecruitPost(['scenario' => $type]);
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
        {

            $this->returnJson();
            return ActiveForm::validate($model);
        }
    }
    /**
     * 结束招聘
     */
    public function actionEndRecruitPost()
    {
        $recruitPostModel = new RecruitPost();
        $this->returnJson();
        return $recruitPostModel->endRecruitPost();
    }

    //========================  职位对应简历的操作  =================================

    /**
     * 获取对应职位的简历
     */
    public function actionResumeForRecruitPost()
    {
        return $this->render('list_resume_for_recruit_post');
    }

    public function actionListResume()
    {
        $sendResumeModel = new SendResume();
        $type = Yii::$app->request->get('type');
        $this->returnJson();
        return  $sendResumeModel->handelInit($sendResumeModel->getListData($type));
    }

    /**
     * 简历详情
     */
    public function actionShowResume()
    {
        $sendModel = new SendResume();
        $info = $sendModel->quoteInfo();
        $educationModel = new ResumeEducationExperience();
        $workingModel = new ResumeWorkingExperience();
        $sendResumeInfo = SendResume::getOneByWhere(['id' => Yii::$app->request->post('id')]);
        $resumeId = $sendResumeInfo['resume_id'];
        $educationInfo = $educationModel->quoteInfo($resumeId);
        $workingInfo = $workingModel->quoteInfo($resumeId);
        return $this->render('show_resume',['info' => $info,'education' => $educationInfo,'working' => $workingInfo]);
    }

    /**
     * 面试邀请
     */
    public function actionInviteResume()
    {
        $sendResumeModel = new SendResume();
        $this->returnJson();
        return $sendResumeModel->inviteResume();
    }

    //========================  面试邀请列表  =================================
    public function actionIndexResumeInvitation()
    {
        return $this->render('list_resume_invitation');
    }

    //========================  已下载简历列表  =================================
    public function actionIndexResumeDownload()
    {
        return $this->render('list_resume_download');
    }

    /**
     * 下载简历
     */
    public function actionResumeDownload()
    {
        $this->layout = false;
//        $resumeModel = new Resume();
        $sendResumeModel = new SendResume();
        $info = $sendResumeModel->quoteInfo();
        $educationModel = new ResumeEducationExperience();
        $workingModel = new ResumeWorkingExperience();
        $sendResumeInfo = SendResume::getOneByWhere(['id' => Yii::$app->request->post('id')]);
        $resumeId = $sendResumeInfo['resume_id'];
        $educationInfo = $educationModel->quoteInfo($resumeId);
        $workingInfo = $workingModel->quoteInfo($resumeId);
        return $this->render('download_resume',['info' => $info,'education' => $educationInfo,'working' => $workingInfo]);
    }

    public function actionDoDownload()
    {
        $resumeModel = new Resume();
        $this->returnJson();
        return $resumeModel->download();
    }

    public function actionDoneDownload()
    {
        $url = Yii::$app->request->get('url');
        $this->redirect('/uploads/download/'.$url);
    }

    //招聘职位审核列表
    public function actionIndexCheckRecruit()
    {
        return $this->render('list_check_recruit');
    }

    public function actionShowCheckRecruitPost()
    {
        $recruitPostModel = new RecruitPost();
        $info = $recruitPostModel->quoteInfo();
        return $this->render('check_recruit_post',['info' => $info]);
    }

    public function actionCheckRecruit()
    {
        $model = new RecruitPost();
        $this->returnJson();
        return  $model->checkRecruit();
    }
}
