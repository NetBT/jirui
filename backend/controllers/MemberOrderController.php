<?php

namespace backend\controllers;

use backend\models\Member;
use backend\models\MemberOrder;
use backend\models\MemberOrderCombo;
use backend\models\MemberOrderDetail;
use backend\models\MemberOrderGoodsImages;
use backend\models\MemberOrderImage;
use common\models\Functions;
use Yii;
use yii\base\Exception;
use yii\bootstrap\ActiveForm;
use backend\models\AbGoods;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * MemberOrder controller
 * 会员订单
 */
class  MemberOrderController extends CommonController
{

    private $memberOrderModel;
    private $memberOrderComboModel;
    private $memberOrderDetailModel;

    public function __construct($id, $module, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->memberOrderModel = new MemberOrder();
        $this->memberOrderComboModel = new MemberOrderCombo();
        $this->memberOrderDetailModel = new MemberOrderDetail();
    }

    //==========================   order列表   =================================

    public function actionIndex()
    {
        return $this->render("list");
    }

    public function actionUploadImage()
    {
        $memberOrder = MemberOrder::findOne(Yii::$app->request->post('id'));
        if ($memberOrder) {
            return $this->render('upload_image', ['memberOrder' => $memberOrder]);
        } else {
            throw new NotFoundHttpException('页面没有找到！');
        }
    }

    public function actionReceiveImage()
    {
        $model = new MemberOrderImage();
        $this->returnJson();
        return $model->uploadImage();
    }

    /**
     * 方法描述：
     * @return string
     * @throws Exception
     * @throws NotFoundHttpException
     * 注意：
     */
    public function actionSelect()
    {
        $get = Yii::$app->request->get();
        $combo_order_number = $get['combo_order_number'];
        $comboOrder = MemberOrderCombo::findOne(['combo_order_number' => $combo_order_number]);
        if (empty($comboOrder)) {
            throw new Exception('这个订单没有找到');
        }
        if (empty($comboOrder->orderDetails)) {
            throw new Exception('这个订单没有选择商品');
        }
        return $this->render('select',
            ['comboOrder' => $comboOrder, 'errors' => Yii::$app->session->getFlash('select_errors')]);
    }

    public function actionChoose()
    {
        $get = Yii::$app->request->get();
        $images_id = Yii::$app->cache->get($get['images_key']);
        $images = MemberOrderImage::findAll(explode(',', $images_id));
        $errors = Yii::$app->session->getFlash('combo_select_errors', '');
        $params = array_merge($get, [
            'errors' => $errors,
            'images' => $images,
            'images_key' => $get['images_key'],
            'comboOrder' => MemberOrderCombo::findOne(['combo_order_number' => $get['combo_order_number']])
        ]);
        return $this->render('choose', $params);
    }

    /**
     * @return string
     * @throws \yii\db\Exception
     * 注意：
     */
    public function actionAccept()
    {
        $post = Yii::$app->request->post();
        $images_id = $post['images'];
        $combo_order_number = $post['combo_order_number'];
        $order_number = $post['order_number'];
        $goods_code = $post['goods_code'];
        $images_key = $post['images_key'];
        $rows = [];
        if (empty($combo_order_number) || empty($goods_code) || empty($images_id)) {
            return $this->render('end', ['msg' => '套系ID，商品Code，图片ID不能为空！']);
        }
        $orderDetail = MemberOrderDetail::findOne([
            'combo_order_number' => $combo_order_number,
            'goods_code' => $goods_code
        ]);
        if (empty($orderDetail)) {
            return $this->render('end', ['msg' => '该订单商品不存在！']);
        }
        $comboOrder = MemberOrderCombo::findOne(['combo_order_number' => $combo_order_number]);
        if ($orderDetail->goods_category == MemberOrderDetail::CATEGORY_THUMBS) {
            if (count($images_id) > $comboOrder->combo->register_count) {
                Yii::$app->session->addFlash('combo_select_errors',
                    '您选择的相册最多只能选' . $comboOrder->combo->register_count . '张图片！');
                return $this->redirect(Url::to([
                    'member-order/choose',
                    'combo_order_number' => $combo_order_number,
                    'images_key' => $images_key,
                    'goods_code' => $goods_code
                ]));
            }
        } else {
            if (count($images_id) > 1) {
                Yii::$app->session->addFlash('combo_select_errors', '您选择的商品最多只能选1张图片！');
                return $this->redirect(Url::to([
                    'member-order/choose',
                    'combo_order_number' => $combo_order_number,
                    'images_key' => $images_key,
                    'goods_code' => $goods_code
                ]));
            }
        }
        foreach ($images_id as $item) {
            $rows[] = [
                'combo_order_number' => $combo_order_number,
                'order_number' => $order_number,
                'goods_code' => $goods_code,
                'image_id' => $item,
                'created_at' => time()
            ];
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $res = MemberOrderGoodsImages::deleteAll([
                'combo_order_number' => $combo_order_number,
                'goods_code' => $goods_code
            ]);
            if ($rows) {
                Yii::$app->db->createCommand()->batchInsert(MemberOrderGoodsImages::tableName(), [
                    "combo_order_number",
                    "order_number",
                    "goods_code",
                    "image_id",
                    "created_at"
                ], $rows)->execute();
            }
        } catch (\Exception  $exception) {
            $transaction->rollBack();
            return $this->render('end', ['msg' => '选片失败' . $exception->getMessage()]);
        }
        $transaction->commit();
        return $this->render('end', ['msg' => '选片成功', 'comboOrder' => $comboOrder]);
    }

    /**
     * 方法描述：
     * @param $combo_order_number
     * @return string
     * @throws NotFoundHttpException
     * 注意：
     */
    public function actionGoodsSelect($combo_order_number)
    {
        $post = Yii::$app->request->post();
        $comboOrder = MemberOrderCombo::findOne(['combo_order_number' => $combo_order_number]);
        $images_id = $post['images'];
        if (!is_array($images_id) || empty($images_id)) {
            Yii::$app->session->addFlash('select_errors', '请选择至少一张图片！');
            return $this->redirect(Url::to([
                'member-order/select',
                'combo_order_number' => $comboOrder->combo_order_number
            ]));
        }
        if (empty($comboOrder)) {
            Yii::$app->session->addFlash('select_errors', '这个订单没有找到');
            return $this->redirect(Url::to(['member-order/select', 'combo_order_number' => $combo_order_number]));
        }
        if (empty($comboOrder->abGoods)) {
            Yii::$app->session->addFlash('select_errors', '这个套系订单没有关联商品');
            return $this->redirect(Url::to(['member-order/select', 'combo_order_number' => $combo_order_number]));
        }
        $key = md5(Yii::$app->user->identity->getId() . $combo_order_number);
        Yii::$app->cache->set($key, implode(',', $images_id), 3600);
        return $this->render('goods_select', ['comboOrder' => $comboOrder, 'images_key' => $key]);
    }

    /**
     * 方法描述：获得下载链接
     * @param $combo_order_number
     * @return array
     * @throws Exception
     * 注意:
     */
    public function actionDownloadImages()
    {
        $this->returnJson();
        $combo_order_number = Yii::$app->request->post('combo_order_number');
        if(empty($combo_order_number)){
            throw new Exception('订单号不能为空');
        }
        $comboOrder = MemberOrderCombo::findOne([
            'combo_order_number' => $combo_order_number
        ]);
        if (empty($comboOrder)) {
            throw new Exception('此订单不存在！');
        }

        $details = $comboOrder->orderDetails;
        if (empty(MemberOrderGoodsImages::findOne(['combo_order_number' => $combo_order_number]))) {
            throw new Exception('此订单没有图片');
        }
        $path = Yii::$app->params['download_path'] . date('Y-m-d') . "/$combo_order_number.zip";
        $filename = Yii::getAlias('@backend-web/' . $path);
        Functions::mkdirs(dirname($filename));
        $zip = new \ZipArchive();
        if ($zip->open($filename, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            throw new Exception('压缩包不存在');
        }
        foreach ($details as $detail) {
            $images = $detail->images;
            if ($images) {
                $_dir = $detail->goods_code . '_' . $detail->getCategoryEnName() . '_' . $detail->goods_size;
                $zip->addEmptyDir($_dir);
                foreach ($images as $image) {
                    $attachfile = $image->image->getFileSavePath();
                    if (file_exists($attachfile)) {
                        $zip->addFile($attachfile, $_dir . '/' . basename($attachfile));
                    }
                }
            }
        }
        if (!file_exists($filename)) {
            throw new Exception('压缩包创建失败');
        }
        return Functions::formatJson(1000, '', ['uri' => Url::to('/' . $path)]);
    }

    public function actionGuadan()
    {
        return $this->render("guadan");
    }

    /**
     *  显示列表
     */
    public function actionList()
    {
        $this->returnJson();
        return $this->memberOrderModel->handelInit($this->memberOrderModel->getListData());
    }

    /**
     *  显示列表
     */
    public function actionGuadanList()
    {
        $this->returnJson();
        return $this->memberOrderModel->handelInit($this->memberOrderModel->getListData());
    }

    /**
     *  添加会员订单
     */
    public function actionAdd()
    {
        $this->layout = 'layer_main';
        $model = new MemberOrder(['scenario' => 'add']);
        if ($model->load(Yii::$app->request->post())) {
            $this->returnJson();
            return $model->addEdit();
        } else {
            $memberId = intval(Yii::$app->request->get('memberId'));
            $memberInfo = Member::getOneInfoById($memberId);
            return $this->render("add", ['model' => $model, 'memberInfo' => $memberInfo]);
        }
    }

    /**
     *  添加订单商品
     */
    public function actionAddShop()
    {
        $this->returnJson();
        return $this->memberOrderModel->addShop();
    }

    /**
     *  删除订单商品
     */
    public function actionDeleteShop()
    {
        $this->returnJson();
        return $this->memberOrderModel->deleteShop();
    }

    /**
     *  编辑订单商品
     */
    public function actionSaveEditShop()
    {
        $this->returnJson();
        return $this->memberOrderModel->saveEditShop();
    }


    /**
     *  编辑会员订单
     */
    public function actionEdit()
    {
        $this->layout = 'layer_main';
        $model = new MemberOrder(['scenario' => 'edit']);
        if ($model->load(Yii::$app->request->post())) {
            $this->returnJson();
            return $model->addEdit();
        } else {
            $get = Yii::$app->request->get();
            $orderstate = isset($get['orderstate']) ? $get['orderstate'] : '';
            $model = MemberOrder::findOne(['id' => Yii::$app->request->get('id')]);
            $model->setScenario('edit');

            $memberModel = new Member();
            $name = $memberModel::getInfoByField($model->member_id, 'name');
            $where['name'] = $name;
            $memberInfo = $memberModel->getMemberInfoByWhere($where);
            return $this->render("edit",
                ['model' => $model, 'member' => $memberInfo['data'], 'orderstate' => $orderstate]);
        }
    }

    /**
     * 该方法是异步校验字段，输入框失去焦点之后自动会自动请求改地址
     * @return array
     */
    public function actionValidateForm()
    {
        $type = Yii::$app->request->get('type');
        $model = new MemberOrder(['scenario' => $type]);
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            $this->returnJson();
            return ActiveForm::validate($model);
        }
    }

    /**
     *  删除订单
     */
    public function actionDelete()
    {

        $this->returnJson();
        return $this->memberOrderModel->doDelete();

    }

    /**
     *  删除订单
     */
    public function actionComDelete()
    {

        $this->returnJson();
        return $this->memberOrderModel->comDelete();

    }


    /**
     *  订单退款
     */
    public function actionRefund()
    {
        $submit = Yii::$app->request->post('submit');
        if (!$submit) {
            $info = $this->memberOrderModel->getInfo(Yii::$app->request->post('id'));
            return $this->render('refund', ['info' => $info]);
        } else {
            $this->returnJson();
            return $this->memberOrderModel->refund();
        }
    }

//    /**
//     *  二销收款
//     */
//    public function actionSecond()
//    {
//        $submit = Yii::$app->request->post('submit');
//        if(!$submit){
//            $info = $this->memberOrderModel->getInfo(Yii::$app->request->post('order_number'));
//            return $this->render('second',['info' => $info]);
//        } else {
//            $this->returnJson();
//            return $this->memberOrderModel->secondGathering();
//        }
//    }

    /**
     *  二销收款
     */
    public function actionSecond()
    {
        $submit = Yii::$app->request->post('submit');
        if (!$submit) {
            $info = $this->memberOrderModel->getInfoByorderID(Yii::$app->request->post('order_number'));
            $weikuan = Yii::$app->request->post('weikuan');
            return $this->render('second', ['info' => $info, 'weikuan' => $weikuan]);
        } else {
            $this->returnJson();
            return $this->memberOrderModel->secondGathering();
        }
    }

    public function actionQuoteOrderInfo()
    {
        $model = new MemberOrder();
        $this->returnJson();
        return $model->quoteOrderInfo();
    }

    //导出会员订单信息
    public function actionExportExcel()
    {
        $model = new MemberOrder();
        $model->exportExcel();
    }

    //==========================   order_combo列表   =================================
    public function actionIndexOrderCombo()
    {
        return $this->render("list_combo");
    }

    public function actionListOrderCombo()
    {
        $this->returnJson();
        return $this->memberOrderComboModel->handelInit($this->memberOrderComboModel->getListData(''));
    }

    //==========================   order_combo列表   =================================
    public function actionIndexOrderDetail()
    {
        return $this->render("list_detail");
    }

    public function actionListOrderDetail()
    {
        $this->returnJson();
        return $this->memberOrderDetailModel->handelInit($this->memberOrderDetailModel->getListData());
    }

    public function actionChangeOrderDetailStatus()
    {
        $this->returnJson();
        return $this->memberOrderDetailModel->changeDetailOrderStatus();
    }

    //查看order_combo列表
    public function actionShowOrderCombo()
    {
        $comboOrderNumber = trim(Yii::$app->request->get('comboOrderNumber'));
        $where['combo_order_number'] = $comboOrderNumber;
        $info = $this->memberOrderComboModel->showOrderComboInfo($where);
        $this->layout = 'layer_main';
        return $this->render("show_order_combo",
            ['member' => $info['member'], 'orderCombo' => $info['orderCombo'], 'order' => $info['order']]);
    }

    //查看order_combo列表
    public function actionEditShop()
    {
        $comboOrderNumber = trim(Yii::$app->request->get('comboOrderNumber'));
        $where['combo_order_number'] = $comboOrderNumber;
        $info = $this->memberOrderComboModel->showOrderComboInfo($where);

        //获取商品列表
        $goods = new AbGoods();
        $goodArr = $goods->getListData();
        $this->layout = 'layer_main';
        return $this->render("edit_shop", [
            'member' => $info['member'],
            'orderCombo' => $info['orderCombo'],
            'order' => $info['order'],
            'goodArr' => $goodArr['data']
        ]);
    }
}
