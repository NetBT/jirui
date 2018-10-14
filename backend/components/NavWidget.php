<?php
namespace backend\components;

use backend\models\Common;
use backend\models\Employee;
use backend\models\EmployeePost;
use backend\models\Module;
use common\models\Functions;
use common\models\Status;

class NavWidget extends CommonWidget
{
    private $moduleModel;
    public function run()
    {
        //菜单列表
        $this->moduleModel = new Module();
        $where['module_type'] = Common::checkEmployeeType();
        $where['status'] = Status::MODULE_LIST_SUCCESS;
        //获取当前用户权限列表
        if(!\Yii::$app->user->isGuest && $where['module_type'] != Status::MODULE_TYPE_GUEST) {
            $moduleContent = EmployeePost::getOneByWhere(['id' => \Yii::$app->user->identity->post_id], ['module_content']);
            $moduleContent = explode(',', $moduleContent['module_content']);
            $where['id'] = $moduleContent;
        }
        //获取当前用户权限
        $moduleList = Module::getByWhere($where, '*', '`order` asc');
        $navList = $this->moduleModel->module($moduleList);
        return $this->render('nav',['navList' => $navList]);
    }
}