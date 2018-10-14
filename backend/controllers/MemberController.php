<?php
namespace backend\controllers;

use backend\models\AB;
use backend\models\Employee;
use backend\models\Member;
use common\models\Status;
use Yii;
use yii\bootstrap\ActiveForm;

/**
 * Manage controller
 * 各种参数设置
 */
class  MemberController extends CommonController
{

    private $memberModel;
    public function __construct($id, $module, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->memberModel = new Member();
    }

    //==========================   会员列表   =================================

    public function actionIndex()
    {
        return $this->render("list");
    }

    /**
     *  显示列表
     */
    public function actionList()
    {

        $this->returnJson();
        return $this->memberModel->handelInit($this->memberModel->getListData());
    }

    /**
     *  添加会员
     */
    public function actionAdd()
    {
        $this->layout = 'layer_main';
        $model = new Member(['scenario' => 'add']);
        if($model->load(Yii::$app->request->post())){
            $this->returnJson();
            return $model->addEdit();
        } else {
            return $this->render("add",['model' => $model]);
        }
    }

    /**
     *  添加会员
     */
    public function actionOrderAdd()
    {
        $this->layout = 'layer_main';
        $model = new Member(['scenario' => 'add']);
        if($model->load(Yii::$app->request->post())){
            $this->returnJson();
            return $model->addEdit();
        } else {
            return $this->render("order_add",['model' => $model]);
        }
    }

    /**
     *  编辑会员
     */
    public function actionEdit()
    {
        $this->layout = 'layer_main';
        $model = new Member(['scenario' => 'edit']);
        if($model->load(Yii::$app->request->post())){
            $this->returnJson();
            return $model->addEdit();
        } else {
            $model = Member::findOne(['id' => Yii::$app->request->get('id')]);
            $model->setScenario('edit');
            return $this->render("edit",['model' => $model]);
        }
    }

    /**
     * 该方法是异步校验字段，输入框失去焦点之后自动会自动请求改地址
     * @return array
     */
    public function actionValidateForm()
    {
        $type = Yii::$app->request->get('type');
        $model = new Member(['scenario' => $type]);
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
        {
            $this->returnJson();
//            \yii\base\Model::beforV
            return ActiveForm::validate($model);
        }
    }

    /**
     *  删除会员
     */
    public function actionDelete()
    {
        $this->returnJson();
        return $this->memberModel->doDelete();
    }

    /**
     *  会员充值
     */
    public function actionRecharge()
    {
        if(Yii::$app->request->post('submit')){
            $this->returnJson();
            return $this->memberModel->recharge();
        } else {
            $model = Member::findOne(['id' => Yii::$app->request->post('id')]);
            return $this->render("recharge",['model' => $model]);
        }

    }

    /**
     *  会员推荐信息
     */
    public function actionReferrer()
    {
        $id = intval(Yii::$app->request->post('id'));
        $model = Member::findOne(['id' => $id]);
        $model->sex = Status::sexyMap()[$model->sex];
        $referrerInfo = $this->memberModel->getReferrerInfo($id);
        return $this->render("referrer",['model' => $model,'referrerInfo' => $referrerInfo]);
    }

    /**
     *  删除会员
     */
    public function actionIntegral()
    {
        if(Yii::$app->request->post('submit')){
            $this->returnJson();
            return $this->memberModel->doIntegral();
        } else {
            $model = Member::findOne(['id' => Yii::$app->request->post('id')]);
            return $this->render("integral",['model' => $model]);
        }
    }

    //导出会员信息
    public function actionExportExcel()
    {
        $model = new Member();
        $model->exportExcel();
    }

    //获取用户信息
    public function actionGetMemberInfoByWhere()
    {
        $tel = trim(Yii::$app->request->post('memberName'));
        $where['tel'] = $tel;
        $this->returnJson();
        return $this->memberModel->getMemberInfoByWhere($where);
    }

    //==========================   个人信息   =================================

    public function actionIndexPersonalData()
    {
        return $this->render('personal_data');
    }
}
