<?php
namespace backend\controllers;

use common\models\Functions;
use Yii;
use backend\models\Notice;
use yii\bootstrap\ActiveForm;

/**
 * Notice controller
 */
class NoticeController extends CommonController
{
    /**
     * 编辑器的设置
     * @return array
     */
    public function actions()
    {
        //        $view->params['img_url'] = trim(Functions::getCommonByKey('img_url'), '/') . '/';//图片地址

        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imagePathFormat" => "/uploads/image/{yyyy}{mm}{dd}/{time}{rand:6}", //上传保存路径
                ],
            ]
        ];
    }
    /**
     * 加载列表页面
     */
    public function actionIndex()
    {
        return $this->render("list");
    }

    /**
     * 返回列表信息
     */
    public function actionList()
    {
        $this->returnJson();
        $model = new Notice();
        return  $model->getListData();
    }

    /**
     * 加载添加页面
     * @return string
     */
    public function actionAdd() {
        $model = new Notice(['scenario' => 'add']);
        $this->layout = 'layer_main';
        return $this->render('add', [
            'model' => $model
        ]);
    }

    /**
     *
     * 切换启用禁用状态
     */
    public function actionToggleStatus()
    {
        $model = new Notice();
        $this->returnJson();
        return $model->toggleStatus();
    }
    /**
     * 删除公告
     */
    public function actionDoDelete()
    {
        $this->returnJson();
        $model = new Notice();
        return $model->doDelete();
    }

    /**
     * 添加公告
     */
    public function actionDoAdd()
    {
        $model = new Notice(['scenario' => 'add']);
        $this->returnJson();
        if ($model->load(\Yii::$app->request->post())) {
            return $model->doSave();
        }
        return Functions::formatJson(2000, '数据获取失败');
    }

    public function actionEdit() {
        $id = \Yii::$app->request->get('id');
        $model = Notice::findOne(['id' => $id]);
        $model->setScenario('edit');
        $this->layout = 'layer_main';
        $where['goods_id'] = $id;
        return $this->render('edit', [
            'model' => $model,
        ]);
    }
    /**
     * 修改公告
     */
    public function actionDoEdit()
    {
        $model = new Notice(['scenario' => 'edit']);
        $this->returnJson();
        if ($model->load(\Yii::$app->request->post())) {
            return $model->doSave();
        }
        return Functions::formatJson(2000, '数据获取失败');
    }

    public function actionShowNotice() {
        $id = Yii::$app->request->post();
        $info = Notice::getOneByWhere(['id' => $id], ['id', 'title', 'content', 'create_time']);
        return $this->renderAjax('show', $info);
    }
    /**
     * 该方法是异步校验字段，输入框失去焦点之后自动会自动请求改地址
     * @return array
     */
    public function actionValidateForm()
    {
        $type = \Yii::$app->request->get('type');
        $editAdminModel = new Notice(['scenario' => $type]);
        if (\Yii::$app->request->isAjax && $editAdminModel->load(\Yii::$app->request->post()))
        {
            $this->returnJson();
            return ActiveForm::validate($editAdminModel);
        }
    }

}
