<?php
namespace backend\controllers;

use backend\models\Employee;
use backend\models\EmployeePost;
use backend\models\EmployeeRate;
use backend\models\OperateLog;
use common\models\Functions;
use Yii;
use yii\bootstrap\ActiveForm;

/**
 * Site controller
 */
class EmployeeController extends CommonController
{
    public $employeeModel;
    public $postModel;
    public $operateLogModel;
    public $employeeRateModel;
    public function __construct($id, $module, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->employeeModel = new Employee();
        $this->postModel = new EmployeePost();
        $this->operateLogModel = new OperateLog();
        $this->employeeRateModel = new EmployeeRate();
    }

    //==========================   员工信息   =================================

    public function actionIndex()
    {
        return $this->render('employee_list');
    }

    //加盟商员工信息
    public function actionIndexAB()
    {
        return $this->render('ab_employee_list');
    }


    //列表信息显示
    public function actionListEmployee()
    {
        $this->returnJson();
        return $this->employeeModel->handelList($this->employeeModel->getListData());
    }

    //切换启用禁用状态
    public function actionUpdateStatus()
    {
        $id = intval(Yii::$app->request->post('id'));
        $status = trim(Yii::$app->request->post('status'));
        $this->returnJson();
        return $this->employeeModel->tabStatus($id, $status);
    }

    //修改密码页面
    public function actionUpdatePassword()
    {
        $editPwdEmployeeModel = new Employee(['scenario' => 'editPwd']);
        $id = intval(Yii::$app->request->post('id'));
        return $this->renderAjax('edit_password',['model' => $editPwdEmployeeModel,'id' => $id]);
    }

    /**
     * 该方法是异步校验字段，输入框失去焦点之后自动会自动请求改地址
     * @return array
     */
    public function actionValidateForm()
    {
        $type = Yii::$app->request->get('type');
        $model = new Employee(['scenario' => $type]);
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
        {
            $this->returnJson();
            return ActiveForm::validate($model);
        }
    }

    //执行密码修改操作
    public function actionDoUpdatePassword()
    {
        $this->returnJson();
        return $this->employeeModel->updatePassword();
    }


    //添加员工信息
    public function actionAddEmployee()
    {
        $addEmployeeModel = new Employee(['scenario' => 'addEmployee']);
        if ($addEmployeeModel->load(Yii::$app->request->post()))
        {
            $this->returnJson();
            return $addEmployeeModel->addEditEmployee();
        }
        else
        {
            return $this->render('add_employee', [
                'model' => $addEmployeeModel,
            ]);
        }
    }

    //添加员工信息
    public function actionAddABEmployee()
    {
        $addEmployeeModel = new Employee(['scenario' => 'addEmployee']);
        if ($addEmployeeModel->load(Yii::$app->request->post()))
        {
            $this->returnJson();
            return $addEmployeeModel->addABEmployee();
        }
        else
        {
            $this->layout = 'layer_main';
            return $this->render('ab_add_employee', [
                'model' => $addEmployeeModel,
            ]);
        }
    }

    //编辑员工信息
    public function actionEditEmployee()
    {
        $employeeModel = new Employee(['scenario' => 'editEmployee']);
        if ($employeeModel->load(Yii::$app->request->post()))
        {
            $this->returnJson();
            return $employeeModel->addEditEmployee();
        }
        else
        {
            $id = Yii::$app->request->post('id');
            $employeeModel = Employee::findOne(['id' => $id]);
            $employeeModel->setScenario('editEmployee');
            return $this->render('edit_employee', [
                'model' => $employeeModel,
            ]);
        }
    }

    //导出员工信息
    public function actionExportExcel()
    {
         $this->employeeModel->exportExcel();
//        return $this->employeeModel->exportExcel();
    }

    //获取单个员工信息
    public function actionGetEmployeeInfoByWhere()
    {
        $employeeName = trim(Yii::$app->request->post('name'));
        $where = ['employee_name' => $employeeName];
        $model = new Employee();
        $this->returnJson();
        return $model->getEmployeeOneInfo($where);
    }


    //员工离职操作
    public function actionWorkingStatus()
    {
        $model = new Employee();
        $this->returnJson();
        return $model->WorkingStatus();
    }


    //==========================   员工职位   =================================

    public function actionIndexEmployeePost()
    {
        return $this->render('employee_post_list');
    }

    public function actionIndexEmployeePostAB()
    {
        return $this->render('ab_employee_post_list');
    }

    //列表信息显示
    public function actionListEmployeePost()
    {
        $this->returnJson();
        return $this->postModel->handelInit($this->postModel->getListData());
    }

    //角色修改添加操作 - 总部
    public function actionPostAddEdit()
    {
        $id = Yii::$app->request->post('id');
        if(Yii::$app->request->post('submit')){
            $this->returnJson();
            return $this->postModel->addEdit();
        } else {
            //添加职位需要在本加盟商下面的权限里面查找
            $abPostInfo = $this->postModel->getPostByAb();
            if($id){
                $info = $this->postModel->getEditInfo();
                return $this->render('post_add_edit',['role' => $info,'module' => $abPostInfo]);
            }
            return $this->render('post_add_edit',['module' => $abPostInfo]);
        }
    }

    //角色修改添加操作 - 加盟商
    public function actionPostAddEditAB()
    {
        $id = Yii::$app->request->post('id');
        if(Yii::$app->request->post('submit')){
            $this->returnJson();
            return $this->postModel->addEdit();
        } else {
            //添加职位需要在本加盟商下面的权限里面查找
            $abPostInfo = $this->postModel->getPostByAb();
            if($id){
                $info = $this->postModel->getEditInfo();
                return $this->render('ab_post_add_edit',['role' => $info,'module' => $abPostInfo]);
            }
            return $this->render('ab_post_add_edit',['module' => $abPostInfo]);
        }
    }

    //切换启用禁用状态
    public function actionPostUpdateStatus()
    {
        $id = intval(Yii::$app->request->post('id'));
        $status = trim(Yii::$app->request->post('status'));
        $this->returnJson();
        return $this->postModel->tabStatus($id, $status);
    }

    //员工职位变化的情况
    public function actionChangeABPost()
    {
        $model = new EmployeePost();
        $this->returnJson();
        return $model->changePost();

    }

    //==========================   员工操作日志   =================================
    public function actionEmployeeOperateLog()
    {
        return $this->render('operate_log');
    }

    //列表信息显示
    public function actionListOperateLog()
    {
        $this->returnJson();
        return $this->operateLogModel->handelInit($this->operateLogModel->getListData());
    }

    //==========================   员工提成列表   =================================
    public function actionIndexRate()
    {
        return $this->render('list_rate');
    }

    /**
     *  显示列表
     */
    public function actionListRate()
    {

        $this->returnJson();
        return $this->employeeRateModel->handelInit($this->employeeRateModel->getListData());
    }

    /**
     *  添加员工提成
     */
    public function actionAddRate()
    {
        $model = new EmployeeRate(['scenario' => 'add']);
        if($model->load(Yii::$app->request->post())){
            $this->returnJson();
            return $model->addEdit();
        } else {
            return $this->render("add_rate",['model' => $model]);
        }
    }

    /**
     *  编辑员工提成
     */
    public function actionEditRate()
    {
        $model = new EmployeeRate(['scenario' => 'edit']);
        if($model->load(Yii::$app->request->post())){
            $this->returnJson();
            return $model->addEdit();
        } else {
            $model = $model->findOne(['id' => Yii::$app->request->post('id')]);
            return $this->render("edit_rate",['model' => $model]);
        }
    }
}
