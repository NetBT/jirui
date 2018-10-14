<?php
namespace backend\controllers;

use backend\models\AB;
use backend\models\ABStatement;
use backend\models\Advert;
use backend\models\Common;
use backend\models\Employee;
use backend\models\GoodsOrder;
use backend\models\Member;
use backend\models\MemberOrder;
use backend\models\Message;
use backend\models\Notice;
use common\models\Functions;
use common\models\Status;

/**
 * Site controller
 */
class IndexController extends CommonController
{
    public $layout = 'base';
    public function __construct($id, $module, array $config = [])
    {
        parent::__construct($id, $module, $config);
    }

    /**
     * @inheritdoc
     */
    public function actionError()
    {
       return $this->render('error');
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionHome()
    {
        $this->layout = 'main';
        $businessId = Common::getBusinessId();
        if(!empty($businessId) && $businessId != 1) {
            //获取今日销售额
            $data['todayIncome'] = ABStatement::getIncome();
            $monthDate = Functions::getMonthStartEnd();
            $data['monthIncome'] = ABStatement::getIncome($monthDate['startDate'], $monthDate['endDate']);
            $data['totalMember'] = Member::getTotalMember();
            //获取公告列表
            $where['status'] = Status::NOTICE_STATUS_RELEASING;
            $data['noticeList'] = Notice::getByWhere($where, ['id', 'title'], 'create_time desc', 0, 3);
            //获取广告
            $data['advertList'] = Advert::getAdvertRand(2);
            $data['modalAdvertList'] = Advert::getAdvertRand(1, Status::ADVERT_POSITION_MODAL);
            return $this->render('home_franchisee', $data);
        } else if($businessId === 1) {
            $where['status'] = Status::NOTICE_STATUS_RELEASING;
            $monthDate = Functions::getMonthStartEnd();

            $data['todayIncome'] = GoodsOrder::getIncome();
            $data['monthIncome'] = GoodsOrder::getIncome($monthDate['startDate'], $monthDate['endDate']);
            $data['totalMember'] = Member::getCountByWhere();
            $data['noticeList'] = Notice::getByWhere($where, ['id', 'title'], 'create_time desc', 0, 3);
            //获取未回复产品投诉消息
            $where['status'] = Status::MESSAGE_STATUS_WHF;
            $where['type'] = Status::MESSAGE_TYPE_CPTS;
            $data['cpts'] = Message::getByWhere($where, ['id', 'content'], 'create_time asc', 0, 5);
            //获取未回复产品投诉消息
            $where['type'] = Status::MESSAGE_TYPE_XTJY;
            $data['xtjy'] = Message::getByWhere($where, ['id', 'content'], 'create_time asc', 0, 5);
            return $this->render('home_headquarters', $data);
        } else {
            $model = Employee::findOne(['id' => \Yii::$app->user->getId()]);
            $model->setScenario('editBySelf');
            return $this->render('home_guest',['model' => $model]);
        }
    }

}
