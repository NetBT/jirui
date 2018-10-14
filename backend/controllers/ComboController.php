<?php
namespace backend\controllers;

use backend\models\AB;
use backend\models\Combo;
use backend\models\Employee;
use backend\models\Member;
use common\models\Status;
use Yii;
use yii\bootstrap\ActiveForm;

/**
 * Combo controller
 * 套系管理
 */
class  ComboController extends CommonController
{
    private $comboModel;
    public function __construct($id, $module, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->comboModel = new Combo();
    }

    //==========================   普通套系   =================================

    public function actionIndexGeneral()
    {
        return $this->render("list_general");
    }

    /**
     *  显示列表
     */
    public function actionList()
    {
        $type = Yii::$app->request->get('type');
        $this->returnJson();
        return $this->comboModel->handelInit($this->comboModel->getListData($type));
    }

    /**
     *  编辑新建普通套系
     */
    public function actionGeneralAddEdit()
    {
        $model = new Combo(['scenario' => 'generalCombo']);
        if($model->load(Yii::$app->request->post())){
            $this->returnJson();
            return $model->generalComboAddEdit();
        } else {
            $id = Yii::$app->request->post('id');

            if($id){
                $model = $model->findOne(['id' => Yii::$app->request->post('id')]);
            }
            $goodsList = $model->getGoodsList($model);
            return $this->render("general_add_edit",['model' => $model,'goodsList' => $goodsList]);
        }
    }

    /**
     * 该方法是异步校验字段，输入框失去焦点之后自动会自动请求改地址
     * @return array
     */
    public function actionValidateForm()
    {
        $type = Yii::$app->request->get('type');
        $model = new Combo(['scenario' => $type]);
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
        {
            $this->returnJson();
            return ActiveForm::validate($model);
        }
    }

    //==========================   成长套系   =================================

    public function actionIndexGrow()
    {
        return $this->render("list_grow");
    }

    /**
     *  编辑新建普通套系
     */
    public function actionGrowAddEdit()
    {
        $model = new Combo(['scenario' => 'growCombo']);
        if($model->load(Yii::$app->request->post())){
            $this->returnJson();
            return $model->growComboAddEdit();
        } else {
            $id = Yii::$app->request->post('id');

            if($id){
                $model = $model->findOne(['id' => Yii::$app->request->post('id')]);
            }
            $comboList = $model->getComboList($model);
            return $this->render("grow_add_edit",['model' => $model,'comboList' => $comboList]);
        }
    }

    /**
     * 选择商品
     */
    public function actionGoodsDetail()
    {
//        return $this->renderAjax('goods_detail');
        return $this->render('goods_detail');
    }

    /**
     * 选择套系
     */
    public function actionComboDetail()
    {
        return $this->renderAjax('combo_detail');
    }

    /**
     *  删除套系
     */
    public function actionDelete()
    {
        $this->returnJson();
        return $this->comboModel->doDelete();
    }

    /**
     * 显示内容
     */
    public function actionShowContent()
    {
        return $this->renderAjax('show_content');
    }

    /**
     * 获取指定的套系
     */
    public function actionGetInfoByWhere()
    {
        $model = new Combo();
        $this->returnJson();
        return $model->getComboOneInfoByWhere();
    }

    /**
     * 获取指定的套系
     */
    public function actionGetShopByWhere()
    {
        $model = new Combo();
        $this->returnJson();
        return $model->getGoodsByOrder();
    }


    /**
     * 获取指定的套系sahngp
     */
    public function actionGetGoodsInfo()
    {
        $model = new Combo();
        $this->returnJson();
        return $model->getGoodsInfo();
    }

}
