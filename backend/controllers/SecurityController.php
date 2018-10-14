<?php
namespace backend\controllers;

use backend\models\ABGoodsImages;
use backend\models\ABStatement;
use backend\models\Module;
use Yii;
/**
 * Site controller
 */
class SecurityController extends CommonController
{
    public function actionDataBackup() {
        return $this->render('backup');
    }

    public function actionDataImport() {
        return $this->render('import');
    }

    // =================  总部数据备份  ===================
    public function actionIndexHeadquarters()
    {
        return $this->render('list_headquarters');
    }

    public function actionList()
    {
        $type = Yii::$app->request->get('type');
        $this->returnJson();
        $model = new Module();
        return $model->handelInit($model->getListData($type));
    }

    // =================  加盟商数据备份  ===================
    public function actionIndexFranchisee()
    {
        return $this->render('list_franchisee');
    }
}
