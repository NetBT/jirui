<?php
namespace backend\controllers;

use backend\models\Employee;
use backend\models\Member;
use backend\models\MemberOrder;
use backend\models\RecruitPost;
use backend\models\ResumeEducationExperience;
use backend\models\ResumeWorkingExperience;
use backend\models\SendResume;
use common\models\Status;
use Yii;
use yii\base\Exception;
use yii\bootstrap\ActiveForm;
use backend\models\Resume;

/**
 * Resume controller
 * 简历控制器
 */
class  ResumeController extends CommonController
{
    private $employeeModel;
    private $resumeModel;
    private $resumeEducationModel;
    private $resumeWorkingModel;
    public function __construct($id, $module, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->resumeModel = new Resume();
        $this->employeeModel = new Employee();
        $this->resumeEducationModel = new ResumeEducationExperience();
        $this->resumeWorkingModel = new ResumeWorkingExperience();
    }


    //==========================   个人信息   =================================

    public function actionIndexPersonalData()
    {
        $model = new Employee(['scenario' => 'editBySelf']);
        if($model->load(Yii::$app->request->post())){
            $this->returnJson();
            return $this->employeeModel->editBySelf();
        } else {
            $model = Employee::findOne(['id' => Yii::$app->user->getId()]);
            $model->setScenario('editBySelf');
            return $this->render("//resume/personal_data",['model' => $model]);
        }
    }


    //==========================  个人简历   =================================

    public function actionIndex()
    {
        //获取发布简历个数
        $resumeNum = $this->resumeModel->getResumeNumByEmployee();
        return $this->render("list",['num' => $resumeNum]);
    }

    /**
     *  显示列表
     */
    public function actionList()
    {

        $this->returnJson();
        return $this->resumeModel->handelInit($this->resumeModel->getListData());
    }

    public function actionGetResumeNum()
    {
        $this->returnJson();
        return $this->resumeModel->getResumeNumByEmployee();
    }

    /**
     *  添加简历
     */
    public function actionAddEdit()
    {
        $id = Yii::$app->request->get('id');
        $educationInfo = [];
        $workingInfo = [];
        $model = new Resume(['scenario' => 'addEdit']);
        if($model->load(Yii::$app->request->post())){
            $this->returnJson();
            return $model->addEdit();
        } else {
            if($id){
                $model = Resume::findOne(['id' => Yii::$app->request->get('id')]);
                $model->setScenario('edit');
                //educationInfo
                $educationInfo = $this->resumeEducationModel->quoteInfo(Yii::$app->request->get('id'));
                $workingInfo = $this->resumeWorkingModel->quoteInfo(Yii::$app->request->get('id'));
            } else {
                $employeeInfo = Employee::getOneInfoById(Yii::$app->user->getId());
                $model->name = $employeeInfo['employee_name'];
                $model->tel = $employeeInfo['tel'];
                $model->wechat = $employeeInfo['wechat'];
                $model->sex = $employeeInfo['sex'];
                $model->marriage = $employeeInfo['marriage'];
                $model->birthday = $employeeInfo['birthday'];
                $model->age = $employeeInfo['age'];
                $model->QQ = $employeeInfo['QQ'];
                $model->nation = $employeeInfo['nation'];
                $model->email = $employeeInfo['email'];
                $model->degree = $employeeInfo['degree'];
                $model->province = $employeeInfo['province'];
                $model->school = $employeeInfo['school'];
                $model->address = $employeeInfo['address'];
                $model->working_duration = $employeeInfo['working_duration'];
                $model->working_status = $employeeInfo['working_status'];
                $model->expected_salary = $employeeInfo['expected_salary'];
            }

            return $this->render("add-edit",['model' => $model,'education' => $educationInfo,'working' => $workingInfo]);
        }
    }


    /**
     *  添加教育经历
     */
    public function actionAddEditEducation()
    {
        $resumeId = intval(Yii::$app->request->post('resumeId'));
        $id = intval(Yii::$app->request->post('id'));
        $model = new ResumeEducationExperience(['scenario' => 'addEdit']);
        if($model->load(Yii::$app->request->post())){
            $this->returnJson();
            return $model->addEdit();
        } else {
            if($id){
                $model = ResumeEducationExperience::findOne(['id' => Yii::$app->request->post('id')]);
                $model->setScenario('addEdit');
            }
            $model->resume_id = $resumeId;
            return $this->render("education",['model' => $model]);
        }
    }

    /**
     *  删除
     */
    public function actionDeleteEducation()
    {
        $this->returnJson();
        return $this->resumeEducationModel->doDelete();
    }

    /**
     *  添加工作经验
     */
    public function actionAddEditWorking()
    {
        $resumeId = intval(Yii::$app->request->post('resumeId'));
        $id = intval(Yii::$app->request->post('id'));
        $model = new ResumeWorkingExperience(['scenario' => 'addEdit']);
        if($model->load(Yii::$app->request->post())){
            $this->returnJson();
            return $model->addEdit();
        } else {
            if($id){
                $model = ResumeWorkingExperience::findOne(['id' => Yii::$app->request->post('id')]);
                $model->setScenario('addEdit');
            }
            $model->resume_id = $resumeId;
            return $this->render("working",['model' => $model]);
        }
    }

    /**
     *  删除
     */
    public function actionDeleteWorking()
    {
        $this->returnJson();
        return $this->resumeWorkingModel->doDelete();
    }

    /**
     *  保存自我评价
     */
    public function actionSaveSelfAssessment()
    {
        $this->returnJson();
        return $this->resumeModel->saveAssessment();
    }

    /**
     *  删除会员
     */
    public function actionDelete()
    {
        $this->returnJson();
        return $this->resumeModel->doDelete();
    }

    /**
     *  默认/取消默认
     */
    public function actionUpdateDefault()
    {
        $this->returnJson();
        return $this->resumeModel->updateDefault();
    }

    /**
     * 该方法是异步校验字段，输入框失去焦点之后自动会自动请求改地址
     * @return array
     */
    public function actionValidateForm()
    {
        $type = Yii::$app->request->get('type');
        $model = new Resume(['scenario' => $type]);
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
        {
            $this->returnJson();
            return ActiveForm::validate($model);
        }
    }

    //==========================  游客职位列表   =================================
    /**
     * 游客的职位列表
     */
    public function actionIndexGuestPost()
    {
        return $this->render('list_guest_post');
    }

    /**
     *  显示列表
     */
    public function actionListGuestPost()
    {
        $recruitPostModel = new RecruitPost();
        $this->returnJson();
        return $recruitPostModel->handelInit($recruitPostModel->getListData());
    }

    /**
     *  申请职位
     */
    public function actionApply()
    {
        $sendResumeModel = new SendResume();
        $this->returnJson();
        return $sendResumeModel->apply();
    }

    /**
     *  显示职位详情
     */
    public function actionShowRecruitPost()
    {
        $recruitPostModel = new RecruitPost();
        $info = $recruitPostModel->quoteInfo();
        return $this->render('show_recruit_post',['info' => $info]);
    }


    //简历审核列表
    public function actionIndexCheckResume()
    {
        return $this->render('list_check_resume');
    }

    /**
     * 简历详情
     */
    public function actionShowResume()
    {
        $resumeModel = new Resume();
        $id = intval(Yii::$app->request->post('id'));
        $info = $resumeModel->quoteInfo($id);
        $educationModel = new ResumeEducationExperience();
        $workingModel = new ResumeWorkingExperience();
        $educationInfo = $educationModel->quoteInfo($id);
        $workingInfo = $workingModel->quoteInfo($id);
        return $this->render('check_resume',['info' => $info,'education' => $educationInfo,'working' => $workingInfo]);
    }

    public function actionCheckResume()
    {
        $this->returnJson();
        return  $this->resumeModel->checkResume();
    }
}
