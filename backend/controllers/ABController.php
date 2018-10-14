<?php
namespace backend\controllers;

use backend\models\AB;
use backend\models\ABCoinChange;
use backend\models\ABPost;
use backend\models\AbPostpone;
use backend\models\AbRecharge;
use backend\models\Common;
use backend\models\Employee;
use backend\models\Message;
use backend\models\Module;
use common\models\Functions;
use common\models\Status;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use yii\bootstrap\ActiveForm;
use Yii;
/**
 * Site controller
 */
class ABController extends CommonController
{
    //============================   加盟商列表   =====================================
    public function actionIndex()
    {
        return $this->render('list');
    }

    //供应商列表信息显示
    public function actionList()
    {
        $this->returnJson();
        $AB = new AB();
        return $AB->getListData();
    }

    public function actionAdd() {
        $addModel = new AB(['scenario' => 'add']);
        return $this->renderAjax('add', [
            'addJMSmodel' => $addModel,
        ]);
    }

    public function actionDoAdd() {
        $addModel = new AB(['scenario' => 'add']);
        $this->returnJson();
        if ($addModel->load(\Yii::$app->request->post())){
            return $addModel->saveData();
        }
        return  Functions::formatJson(2000, '数据获取失败');
    }

    /**
     * 总部修改加盟商资料
     * @return string
     */
    public function actionEdit() {
        $id = \Yii::$app->request->post('id');
        $addModel = AB::findOne(['id' => $id]);
        $addModel->setScenario('edit');
        $addModel->AB_permission = explode(',', $addModel->AB_permission);
        //获取负责人列表
        $whereStr = '`alliance_business_id`=1 AND `status`='. Status::EMPLOYEE_STATUS_ACTIVE . ' OR `id`=' . $addModel->AB_principal;
        $principalList = Employee::getByWhere($whereStr, ['id', 'employee_name']);
        $principalList = Functions::extractKey($principalList, 'id', 'employee_name');
        return $this->renderAjax('edit', [
            'editJMSmodel' => $addModel,
            'principalList' => $principalList
        ]);
    }

    public function actionDoEdit () {
        $addModel = new AB(['scenario' => 'edit']);
        $this->returnJson();
        if ($addModel->load(\Yii::$app->request->post())){
            return $addModel->saveData();
        }
        return Functions::formatJson(2000, '数据获取失败');
    }

    /**
     * 加盟商自己修改店铺资料
     * @return array|string
     */
    public function actionEditBySelf() {
        $model = new AB(['scenario' => 'editBySelf']);
        if ($model->load(Yii::$app->request->post()) && $model->saveDataBySelf()){
            $this->returnJson();
            return Functions::formatJson(1000, '修改成功');
        } else {
            $id = Common::getBusinessId();
            $model = AB::findOne(['id' => $id]);
            $model->setScenario('editBySelf');
            return $this->render('edit_by_self', [
                'model' => $model,
            ]);
        }
    }

    /**
     * 该方法是异步校验字段，输入框失去焦点之后自动会自动请求改地址
     * @return array
     */
    public function actionValidateForm()
    {
        $type = \Yii::$app->request->get('type');
        $editAdminModel = new AB(['scenario' => $type]);
        if (\Yii::$app->request->isAjax && $editAdminModel->load(\Yii::$app->request->post()))
        {
            $this->returnJson();
            return ActiveForm::validate($editAdminModel);
        }
    }

    public function actionDoDelete() {
        $id = \Yii::$app->request->post('id');
        $AB = new AB();
        $this->returnJson();

        return $AB->deleteData($id);
    }

    //导出加盟商信息
    public function actionExportExcel()
    {
        $AB = new AB();
        $AB->exportExcel();
    }

    //============================   加盟商权限   =====================================
    public function actionIndexPost()
    {
        return $this->render('post_list');
    }

    //列表信息显示
    public function actionListPost()
    {
        $ABPostModel = new ABPost();
        $this->returnJson();
        return $ABPostModel->handelInit($ABPostModel->getListData());
    }

    //权限修改添加操作
    public function actionPostAddEdit()
    {
        $postModel = new ABPost();
        $id = Yii::$app->request->post('id');
        if(Yii::$app->request->post('submit')){
            $this->returnJson();
            return $postModel->addEdit();
        } else {
            $moduleModel = new Module();
            $moduleInfo = $moduleModel->getOrderModule(Status::MODULE_TYPE_FRANCHISEE);
            if($id){
                $info = $postModel->getEditInfo();
                return $this->render('post_add_edit',['role' => $info,'module' => $moduleInfo]);
            }
            return $this->render('post_add_edit',['module' => $moduleInfo]);
        }
    }

    /**
     * 加盟商充值页面
     * @return string
     */
    public function actionRecharge() {
        $id = Yii::$app->request->post('id');
        $info = AB::getOneByWhere(['id' => $id], ['AB_number', 'AB_name', 'id']);
        return $this->renderAjax('recharge', [
            'info' => $info,
        ]);
    }

    /**
     * 执行加盟商充值
     * @return array
     */
    public function actionDoRecharge() {
        $this->returnJson();
        $abR = new AbRecharge();
        return $abR->doRecharge();
    }

    /**
     * 加盟商充值页面
     * @return string
     */
    public function actionPostpone() {
        $id = Yii::$app->request->post('id');
        $info = AB::getOneByWhere(['id' => $id], ['AB_number', 'AB_name', 'id']);
        return $this->renderAjax('postpone', [
            'info' => $info,
        ]);
    }

    /**
     * 执行加盟商充值
     * @return array
     */
    public function actionDoPostpone() {
        $this->returnJson();
        $abR = new AbPostpone();
        return $abR->doPostpone();
    }
    //切换启用禁用状态
    public function actionPostUpdateStatus()
    {
        $postModel = new ABPost();
        $id = intval(Yii::$app->request->post('id'));
        $status = trim(Yii::$app->request->post('status'));
        $this->returnJson();
        return $postModel->tabStatus($id, $status);
    }

    /**
     * 切换加盟商状态  启用  禁用状态
     * @return array
     */
    public function actionToggleStatus () {
        $id = Yii::$app->request->post('id');
        $AB = new AB();
        $this->returnJson();
        return $AB->toggleStatus($id);
    }

    /**
     * 收款日志
     * @return string
     */
    public function actionReceiptLog() {
        return $this->render('receipt_log');
    }

    /**
     * 获取收款日志数据
     * @return array
     */
    public function actionReceiptLogData() {
        $this->returnJson();
        $AB = new AbRecharge();
        return $AB->getListData();
    }

    public function actionStatistic() {
        return $this->render('statistic');
    }

    public function actionCoinChange() {
        return $this->render('coin_change');
    }

    public function actionCoinChangeData() {
        $this->returnJson();
        $model = new ABCoinChange();
        return $model->getListData();
    }

    public function actionEchartsOpenNum() {
        $model = new AB();
        $this->returnJson();
        return $model->echartsOpenNum();
    }
    public function actionEchartsRecharge() {
        $model = new AbRecharge();
        $this->returnJson();
        return $model->echartsRecharge();
    }


    public function actionGetTotal() {
        $model = new AB();
        $this->returnJson();
        return $model->getTotal();
    }

    //=============================  加盟商自己提交充值与延期申请  ===============================

    //充值
    public function actionRechargeBySelf()
    {
        $model = new Message();
        if(Yii::$app->request->post('submit')){
            $this->returnJson();
            return $model->rechargeBySelf();
        } else {
            return $this->render('recharge_by_self');
        }
    }

    //延期申请
    public function actionPostponeBySelf()
    {
        $model = new Message();
        if(Yii::$app->request->post('submit')){
            $this->returnJson();
            return $model->postponeBySelf();
        } else {
            return $this->render('postpone_by_self');
        }
    }
}
