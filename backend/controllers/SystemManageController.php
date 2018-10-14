<?php
namespace backend\controllers;

use backend\models\ABCommon;
use backend\models\ParameterSetting;
use Yii;

/**
 * Manage controller
 * 各种参数设置
 */
class SystemManageController extends CommonController
{
    private $psModel;
    public function __construct($id, $module, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->psModel = new ParameterSetting();
    }

    //==========================   总部参数设置   =================================

    public function actionIndex()
    {
        $systemSiteList = $this->psModel->handelList();
        return $this->render("system_headquarters",['list' => $systemSiteList]);
    }

    /**
     *  执行编辑操作
     */
    public function actionDoSave()
    {
       $this->returnJson();
        return $this->psModel->doSave();
    }

    //==========================   加盟商参数设置   =================================
    public function actionABIndex()
    {
        $abCommonModel = new ABCommon();
        $list = $abCommonModel->handelList();
        return $this->render("system_franchisee",['list' => $list]);
    }

    /**
     *  执行编辑操作
     */
    public function actionDoSaveAB()
    {
        $model = new ABCommon();
        $this->returnJson();
        return $model->doSave();
    }

    //保存拍摄时间段
    public function actionDoSaveABShootDate()
    {
        $model = new ABCommon();
        $this->returnJson();
        return $model->doSaveShootDate();
    }

}
