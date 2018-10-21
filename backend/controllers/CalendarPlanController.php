<?php

namespace backend\controllers;

use backend\models\AbCalendarPlan;
use backend\models\ABGoodsImages;
use backend\models\MemberOrderCombo;
use common\models\Functions;
use Yii;
use yii\widgets\ActiveForm;

/**
 * Site controller
 */
class CalendarPlanController extends CommonController
{
    public $orderComboModel;

    public function __construct($id, $module, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->orderComboModel = new MemberOrderCombo();
    }

    public function actionList()
    {
        $orderNumber = Yii::$app->request->get('orderNumber');
        $data = ['orderNumber' => ''];
        if (!empty($orderNumber)) {
            $data['orderNumber'] = $orderNumber;
        }
        return $this->render('list', $data);
    }

    public function actionListData()
    {
        $this->returnJson();
        $model = new AbCalendarPlan();
        return $model->getListData();
    }

    public function actionAdd()
    {
        $data['model'] = new AbCalendarPlan(['scenario' => 'add']);
        $data['date'] = Yii::$app->request->post('date');
        $data['orderNumber'] = Yii::$app->request->get('orderNumber');
        return $this->renderAjax('add', $data);
    }

    public function actionDoAdd()
    {
        $model = new AbCalendarPlan(['scenario' => 'add']);
        $this->returnJson();
        if ($model->load(\Yii::$app->request->post())) {
            return $model->saveData();
        }
        return Functions::formatJson(2000, '获取失败');
    }

    public function actionEdit()
    {
        $id = \Yii::$app->request->post('id');
        $data['orderNumber'] = Yii::$app->request->get('orderNumber');
        $data['model'] = AbCalendarPlan::findOne(['id' => $id]);
        $data['model']->start = date("Y-m-d", strtotime($data['model']->start));
        $data['model']->setScenario('edit');
        return $this->renderAjax('edit', $data);
    }

    public function actionDoEdit()
    {
        $model = new AbCalendarPlan(['scenario' => 'edit']);
        $this->returnJson();
        if ($model->load(\Yii::$app->request->post())) {
            return $model->saveData();
        }
        return Functions::formatJson(2000, '获取失败');
    }

    public function actionDoDrop()
    {
        $model = new AbCalendarPlan();
        $this->returnJson();
        return $model->saveDrop();
    }

    public function actionDoDelete()
    {
        $this->returnJson();
        $id = \Yii::$app->request->post('id');
        $res = AbCalendarPlan::deleteAll(['id' => $id]);
        if ($res === false) {
            return Functions::formatJson(2000, '删除失败');
        }
        return Functions::formatJson(1000, '删除成功');
    }

    /**
     * 该方法是异步校验字段，输入框失去焦点之后自动会自动请求改地址
     * @return array
     */
    public function actionValidateForm()
    {
        $type = \Yii::$app->request->get('type');
        $editAdminModel = new AbCalendarPlan(['scenario' => $type]);
        if (\Yii::$app->request->isAjax && $editAdminModel->load(\Yii::$app->request->post())) {

            $this->returnJson();
            return ActiveForm::validate($editAdminModel);
        }
    }

    //==========================   获取combo列表   =================================
    public function actionGetComboOrder()
    {
        $orderNum = trim(Yii::$app->request->post('orderNum'));
        $model = new MemberOrderCombo();
        $this->returnJson();
        return $model->getComboOrderByWhere(['order_number' => $orderNum, 'is_delete' => '1']);
    }

    //==========================   全部列表   =================================
    public function actionIndexAllCombo()
    {
        return $this->render('order_combo_all');
    }

    //==========================   未拍摄列表   =================================
    public function actionIndexNotShoot()
    {
        return $this->render('order_combo_not_shoot');
    }

    //==========================   已拍摄列表   =================================
    public function actionIndexShoot()
    {
        return $this->render('order_combo_shoot');
    }

    public function actionListOrder()
    {
        $type = Yii::$app->request->get('type');
        $this->returnJson();
        return $this->orderComboModel->handelInit($this->orderComboModel->getListData($type));
    }

    //==========================   未选片列表   =================================
    public function actionIndexNotSelect()
    {
        return $this->render('order_combo_not_select');
    }

    //==========================   后期列表   =================================
    public function actionIndexNotComposite()
    {
        return $this->render('order_combo_not_composite');
    }

    //==========================   成品理件列表   =================================
    public function actionIndexNotDeal()
    {
        return $this->render('order_combo_not_deal');
    }


    public function actionReplanOrder()
    {
        return $this->render('replan_order');
    }

    public function actionReplanOrderData()
    {
        $type = Yii::$app->request->get('type');
        $this->returnJson();
        return $this->orderComboModel->handelInit($this->orderComboModel->getListData($type));
    }

    public function actionDoReplan()
    {
        $orderNumber = Yii::$app->request->post('orderNumber');
        $this->returnJson();
        $model = new MemberOrderCombo();
        return $model->doReplan($orderNumber);
    }

    /**
     * 更改状态
     */
    public function actionChangeComboOrderStatus()
    {
        $this->returnJson();
        return $this->orderComboModel->changeComboOrderStatus();
    }
}
