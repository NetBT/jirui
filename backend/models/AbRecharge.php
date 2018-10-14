<?php
namespace backend\models;

use common\models\Status;
use Yii;
use yii\base\Exception;
use common\models\Functions;

/**
 * 供应商信息表
 * Class AB
 * @package backend\models
 */
class AbRecharge extends Common
{
    public static function tableName()
    {
        return '{{%ab_recharge}}';
    }


    /**
     * 获取供应商列表
     * @return array
     */
    public function getListData () {
        $returnData = [
            "draw" => intval(Yii::$app->request->post('draw')),
            "recordsTotal" => 0,
            "recordsFiltered" => 0,
            "data" => null
        ];
        //搜索条件
        $post = Yii::$app->request->post('extra_search');
        $where = [];
        isset($post['receiptUser']) && !empty($post['receiptUser']) ? ($where['recharge_user'] = $post['receiptUser']) : null;
        isset($post['AbName']) && !empty($post['AbName']) ? ($where['AB_name'] = $post['AbName']) : null;
        isset($post['AbNumber']) && !empty($post['AbNumber']) ? ($where['AB_number'] = $post['AbNumber']) : null;
        //计算总数
        $whereStr = '';
        if (!empty($contractOrPhone)) {
            $whereStr = "AB_tel = '{$contractOrPhone}' OR AB_number = '{$contractOrPhone}'";
        }
        $count = static::getCountByWhere($whereStr);
        $returnData["recordsTotal"] = $returnData['recordsFiltered'] = intval($count);

        //设置分页
        $this->setPagination();
        //获取数据
        $returnData['data'] = static::getByWhere($where, [
            'id', 'AB_number', 'AB_name', 'recharge_time', 'recharge_user', 'recharge_money'
        ], 'recharge_time desc', $this->_Pagination['offset'], $this->_Pagination['limit']);
        if (!empty($returnData['data'])) {
            foreach ($returnData['data'] as $k => $v) {
                $opearteInfo = Employee::getOneByWhere(['id' => $v['recharge_user']], 'employee_name');
                $returnData['data'][$k]['recharge_user'] = $opearteInfo['employee_name'];
            }
        }
        return $returnData;
    }

    /**
     * 进行加盟商充值
     * @return array
     */
    public function doRecharge() {
        $trans = Yii::$app->db->beginTransaction();
        try {
            $post = \Yii::$app->request->post();
            $rechargeMoney = floatval($post['recharge_money']);
            $id = intval($post['id']);
            if (empty($post['id']) || empty($post['recharge_money'])) {
                throw new Exception('数据格式错误');
            }
            $info = AB::getOneByWhere(['id' => $id], ['id', 'AB_number', 'AB_name']);
            $recharge['AB_name'] = $info['AB_name'];
            $recharge['AB_number'] = $info['AB_number'];
            $recharge['recharge_time'] = date('Y-m-d H:i:s');
            $recharge['recharge_user'] = Yii::$app->user->getId();
            $recharge['recharge_money'] = $rechargeMoney;
            $res = $this->insertDataWithLog($recharge);

            if ($res === false) {
                throw new Exception('充值失败：记录日志失败');
            }
            $res = ABCoinChange::recordCoinChange($id, $rechargeMoney, Status::AB_CC_TYPE_RECHARGE, '总部充值');
            if ($res !== true) {
                throw new Exception($res['message']);
            }
            $trans->commit();
            return Functions::formatJson(1000, '充值成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(2000, $e->getMessage());
        }
    }

    public function echartsRecharge () {
        $post = Yii::$app->request->post();
        $startTime = !empty($post["start"]) ? date("Y-m-d 00:00:00", strtotime($post["start"])) : date("Y-m-d 00:00:00");
        $endTime = !empty($post["end"]) ? date("Y-m-d 23:59:59", strtotime($post["end"])) : date("Y-m-d 23:59:59");

        //获取下单量
        $andWhere[] = ['between', 'recharge_time', $startTime, $endTime];
        $list = static::getByAndWheres([], $andWhere, ['id','recharge_time', 'recharge_money']);
        $timeMap = Functions::getTimeForEchart($startTime, $endTime);
        $result = ['xAxis' => ['data' => []], 'series' => ['data' => []]];
        foreach ($timeMap as $v) {
            $num = 0;
            foreach ($list as $kk => $vv) {
                if ($vv['recharge_time'] >= $v['start'] && $vv['recharge_time'] <= $v['end']) {
                    $num += $vv['recharge_money'];
                }
            }
            $tmp = [$v['xAxis'], $num];
            $result['series']['data'][] = $tmp;
        }
        return $result;
    }
}
