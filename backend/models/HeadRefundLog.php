<?php
namespace backend\models;

use common\models\Status;
use Yii;
use yii\base\Exception;
use common\models\Functions;

/**
 * 记录总部退货日志
 * Class HeadRefundGoodsLog
 * @package backend\models
 */
class HeadRefundLog extends Common
{
    public static function tableName()
    {
        return '{{%head_refund_log}}';
    }

    /**
     * 记录退货日志
     * @param int    $detailId
     * @param int    $refundMoney
     * @param int    $refundNum
     * @param string $mark
     *
     * @return array|bool
     */
    public static function recordRefundGoodsLog($detailId = 0, $refundMoney = 0, $refundNum = 0 ,$mark = '') {
        try {

            $detailId = intval($detailId);
            $refundMoney = floatval($refundMoney);
            $refundNum = intval($refundNum);
            $mark = strval($mark);
            if (empty($detailId) || $refundMoney == 0 || $refundNum == 0) {
                throw new Exception('信息错误');
            }

            $detailInfo = GoodsOrderDetail::getOneByWhere(['id' => $detailId]);

            $orderInfo = GoodsOrder::getOneByWhere(['order_number' => $detailInfo['order_number']]);

            $ABInfo = AB::getOneByWhere(['id' => $orderInfo['AB_id']], 'AB_name');

            $data['AB_id'] = $orderInfo['AB_id'];
            $data['AB_name'] = $ABInfo['AB_name'];
            $data['order_number'] = $detailInfo['order_number'];
            $data['refund_num'] = $refundNum;
            $data['refund_money'] = $refundMoney;
            $data['goods_id'] = $detailInfo['goods_id'];
            $data['mark'] = $mark;
            $data['operate_user'] = Yii::$app->user->getId();
            $data['refund_time'] = date('Y-m-d H:i:s');
            $data['log_type'] = Status::HEAD_REFUND_LOG_TYPE_GOODS;
            $res = static::insertData($data);
            if ($res === false) {
                throw new Exception('退款记录失败');
            }

            return true;
        } catch (Exception $e) {
            return ['status' => false, 'msg' => $e->getMessage()];
        }
    }

    /**
     * 记录退款日志
     * @param null   $ABId
     * @param int    $refundMoney
     * @param int    $orderNumber
     * @param null   $refundType
     * @param string $mark
     *
     * @return array|bool
     */
    public static function recordRefundMoneyLog($ABId = null, $refundMoney = 0, $orderNumber = 0, $refundType = null, $mark = '') {
        try {
            $ABId = intval($ABId);
            $refundType = intval($refundType);
            $refundMoney = floatval($refundMoney);
            $mark = strval($mark);
            if (empty($ABId) || empty($refundType) || $refundMoney == 0) {
                throw new Exception('信息错误');
            }

            $ABInfo = AB::getOneByWhere(['id' => $ABId], 'AB_name');
            $data['AB_id'] = $ABId;
            $data['AB_name'] = $ABInfo['AB_name'];
            $data['refund_money'] = $refundMoney;
            $data['refund_type'] = $refundType;
            $data['order_number'] = $orderNumber;
            $data['mark'] = $mark;
            $data['operate_user'] = Yii::$app->user->getId();
            $data['refund_time'] = date('Y-m-d H:i:s');
            $data['log_type'] = Status::HEAD_REFUND_LOG_TYPE_MONEY;
            $res = static::insertData($data);
            if ($res === false) {
                throw new Exception('退款记录失败');
            }
            return true;
        } catch (Exception $e) {
            return ['status' => false, 'msg' => $e->getMessage()];
        }
    }

    /**
     * 退款金额统计
     * return格式: [
     *      'xAxis' => [
     *          'data' => ['x1', 'x2' ...]
     *      ],
     *      'series' => [
     *          'data' => [100, 200, 300, ...]
     *      ]
     * ]
     * @return array
     */
    public function echartsRefundAmount() {
        $post = Yii::$app->request->post();
        $startTime = !empty($post["start"]) ? date("Y-m-d 00:00:00", strtotime($post["start"])) : date("Y-m-d 00:00:00");
        $endTime = !empty($post["end"]) ? date("Y-m-d 23:59:59", strtotime($post["end"])) : date("Y-m-d 23:59:59");

        //获取下单量
        $andWhere[] = ['between', 'refund_time', $startTime, $endTime];
        $list = static::getByAndWheres([], $andWhere, ['id','refund_time', 'refund_money']);
        $timeMap = Functions::getTimeForEchart($startTime, $endTime);
        $result = ['xAxis' => ['data' => []], 'series' => ['data' => []]];
        foreach ($timeMap as $v) {
            $money = 0;
            foreach ($list as $kk => $vv) {
                if ($vv['refund_time'] >= $v['start'] && $vv['refund_time'] <= $v['end']) {
                    $money += $vv['refund_money'];
                }
            }
            $tmp = [$v['xAxis'], $money];
            $result['series']['data'][] = $tmp;
        }
        return $result;
    }
}
