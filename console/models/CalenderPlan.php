<?php
namespace console\models;
use common\models\Common;
use common\models\Status;

/**
 * Created by PhpStorm.
 * User: eycuit
 * Date: 2018/7/8
 * Time: 18:11
 */

class CalenderPlan extends Common {

    public static function tableName()
    {
        return "{{%ab_calendar_plan}}";
    }

    public function checkPlan() {

        $where['status'] = [Status::CALENDAR_PLAN_STATUS_WAIT];

        $list = static::getByWhere($where, ['id', 'AB_id', 'AB_order_number', 'start', 'end']);

        $currDate = date('Y-m-d H:i:s');

        $startOrder = [];
        $endOrder = [];
        foreach ($list as $k => $v) {
            if ($v['start'] >= $currDate && $v['end'] >= $currDate) {
                $startOrder[] = [
                    'id' => $v['id'],
                    'AB_id' => $v['AB_id'],
                    'order_number' => $v['AB_order_number']
                ];

            }

            if ($v['end'] < $currDate) {
                $endOrder[] = [
                    'id' => $v['id'],
                    'AB_id' => $v['AB_id'],
                    'order_number' => $v['AB_order_number']
                ];;
            }
        }


        if (! empty($startOrder)) {
            $data['shoot_status'] = Status::MEMBER_ORDER_SHOOT_STATUS_ING;
            $data['update_time'] = date('Y-m-d H:i:s');
            $updatedAB = [];
            foreach ($startOrder as $k => $v) {
                if (in_array($v['AB_id'], $updatedAB)) {
                    continue;
                }
                $updatedAB[] = $v['AB_id'];
                $trans = \Yii::$app->db->beginTransaction();
                //逐个加盟商更新 -- 更新订单
                $where = [];
                $where['business_id'] = $v['AB_id'];
                $where['combo_order_number'] = [];
                foreach ($startOrder as $kk => $vv) {
                    if ($vv['AB_id'] == $v['AB_id']) {
                        $where['combo_order_number'][] = $vv['order_number'];
                    }
                }
                if (! empty($where['combo_order_number'])) {
                    $res = MemberOrderCombo::updateData($data, $where);
                    if ($res === false) {
                        $trans ->rollBack();
                        continue;
                    }
                }
                //更新排项
                $res = static::updateData(['status' => Status::CALENDAR_PLAN_STATUS_DOING], ['AB_id' => $v['AB_id'], 'AB_order_number' => $where['combo_order_number']]);
                if ($res === false) {
                    $trans->rollBack();
                    continue;
                }
                $trans->commit();
            }
        }

        //更改预期
//        if (! empty($endOrder)) {
//            $data['shoot_status'] = Status::MEMBER_ORDER_SHOOT_STATUS_ING;
//            $data['update_time'] = date('Y-m-d H:i:s');
//            $updatedAB = [];
//            foreach ($endOrder as $k => $v) {
//                if (in_array($v['AB_id'], $updatedAB)) {
//                    continue;
//                }
//                $updatedAB[] = $v['AB_id'];
//
//                $trans = \Yii::$app->db->beginTransaction();
//                $where = [];
//                $where['AB_id'] = $v['AB_id'];
//                $where['AB_order_number'] = [];
//                foreach ($endOrder as $kk => $vv) {
//                    if ($vv['AB_id'] == $vv['AB_id']) {
//                        $where['AB_order_number'][] = $vv['order_number'];
//                    }
//                }
//
//                //更新排项
//                if (!empty($where['AB_order_number'])) {
//                    $res = static::updateData(['status' => Status::CALENDAR_PLAN_STATUS_END], $where);
//                    if ($res === false) {
//                        $trans->rollBack();
//                        continue;
//                    }
//                    $trans->commit();
//                }
//            }
//        }

        echo "排项进度检查程序-完成\n";
    }
}