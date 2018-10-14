<?php
namespace backend\controllers;

use backend\models\AB;
use backend\models\Employee;
use backend\models\Message;
use common\models\Functions;
use common\models\Status;
use Yii;
use yii\bootstrap\ActiveForm;

/**
 * Manage controller
 * 各种参数设置
 */
class MessageController extends CommonController
{

    private $messageModel;
    public function __construct($id, $module, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->messageModel = new Message();
    }

    //==========================   总部参数设置   =================================

    public function actionIndex()
    {
        $abList = AB::getFormArray('','id','AB_name');
        return $this->render("list",['abList' => $abList]);
    }

    /**
     *  显示列表
     */
    public function actionList()
    {

        $this->returnJson();
        return $this->messageModel->handelInit($this->messageModel->getListData());
    }

    /**
     *  回复
     */
    public function actionReply()
    {
        $model = new Message(['scenario' => 'replyMessage']);
        if ($model->load(Yii::$app->request->post()))
        {
            $this->returnJson();
            return $model->reply();
        }
        else
        {
            $id = Yii::$app->request->post('id');
            $model = Message::findOne(['id' => $id]);
            $model->setScenario('replyMessage');
            $abList = AB::getFormArray(['AB_delete' => Status::AB_ID_NOT_DELETE],'id','AB_name');
            $model->business_name = $abList[$model->business_id];
            return $this->render("reply",['model' => $model]);
        }
    }


    /**
     *  回复
     */
    public function actionSend()
    {
        $model = new Message(['scenario' => 'sendMessage']);
        return $this->render("send",['model' => $model]);
    }
    /**
     *  回复
     */
    public function actionDoSend()
    {
        $model = new Message(['scenario' => 'sendMessage']);
        $this->returnJson();
        if ($model->load(Yii::$app->request->post()))
        {
            return $model->send();
        }
        return Functions::formatJson(2000, '数据错误');
    }
    public function actionAbIndex()
    {
        return $this->render("ab_list");
    }

    /**
     *  显示列表
     */
    public function actionAbList()
    {
        $this->returnJson();
        return $this->messageModel->getAbListData();
    }

    /**
     * 该方法是异步校验字段，输入框失去焦点之后自动会自动请求改地址
     * @return array
     */
    public function actionValidateForm()
    {
        $type = \Yii::$app->request->get('type');
        $editAdminModel = new Message(['scenario' => $type]);
        if (\Yii::$app->request->isAjax && $editAdminModel->load(\Yii::$app->request->post()))
        {
            $this->returnJson();
            return ActiveForm::validate($editAdminModel);
        }
    }
}
