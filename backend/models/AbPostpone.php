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
class AbPostpone extends Common
{
    public static function tableName()
    {
        return '{{%ab_postpone}}';
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
        $contractOrPhone = Yii::$app->request->post('extra_search')['number'];
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
        $returnData['data'] = static::getByWhere($whereStr, [
            'id', 'AB_number', 'AB_name', 'AB_principal', 'AB_tel', 'AB_address', 'AB_alliance_fee',
            'AB_balance','AB_start_time', 'AB_end_time', 'AB_operate_id'
        ], 'AB_create_time desc', $this->_Pagination['offset'], $this->_Pagination['limit']);
        if (!empty($returnData['data'])) {
            foreach ($returnData['data'] as $k => $v) {
                $opearteInfo = Employee::getOneByWhere(['id' => $v['AB_operate_id']], 'employee_name');
                $returnData['data'][$k]['AB_operate_name'] = $opearteInfo['employee_name'];
                $principalInfo = Employee::getOneByWhere(['id' => $v['AB_principal']], 'employee_name');
                $returnData['data'][$k]['AB_principal'] = $principalInfo['employee_name'];
            }
        }
        return $returnData;
    }

    /**
     * 进行加盟商充值
     * @return array
     */
    public function doPostpone() {
        $trans = Yii::$app->db->beginTransaction();
        try {
            $post = \Yii::$app->request->post();
            //格式化数据
            $id = intval($post['id']);
            $payWay = intval($post['payWay']);
            $postponeNum = intval($post['postponeNum']);
            $timeUnit = intval($post['timeUnit']);
            if (empty($id) || empty($payWay) || empty($postponeNum) || empty($timeUnit)) {
                throw new Exception('数据格式错误');
            }

            if( !array_key_exists($payWay, Status::AbPostponePayWayMap())) {
                throw new Exception('未知支付类型');
            }

            if (!array_key_exists($timeUnit, Status::AbPostponeTimeUnitMap())) {
                throw new Exception('未知时间单位');
            }


            $info = AB::getOneByWhere(['id' => $id], ['id', 'AB_balance', 'AB_number', 'AB_end_time']);

            $data['AB_update_time'] = date('Y-m-d H:i:s');
            $data['AB_end_time'] = $this->getPostponeEndTime($postponeNum, $timeUnit, $info['AB_end_time']);
            if ($payWay == Status::AB_POSTPONE_PAY_WAY_BALANCE) {
//                $data['AB_balance'] = bcadd($info['AB_balance'], $rechargeMoney);
            }
            $AB = new AB();
            $abRes = $AB->updateDataWithLog($data, ['id' => $id]);
            if ($abRes === false) {
                throw new Exception('延期失败');
            }
            $recharge['AB_number'] = $info['AB_number'];
            $recharge['postpone_time'] = $postponeNum;  #折算天数
            $recharge['time_unit'] = $timeUnit;  #折算天数
            $recharge['pay_way'] = $payWay;
            $recharge['create_time'] = date('Y-m-d H:i:s');;
            $recharge['operate_user'] = Yii::$app->user->getId();
            $res = $this->insertDataWithLog($recharge);

            if ($res === false) {
                throw new Exception('延期失败：记录日志失败');
            }
            $trans->commit();
            return Functions::formatJson(1000, '延期成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(2000, $e->getMessage());
        }
    }

    public function getPostponeEndTime($postponeNum = 0, $timeUnit = 1, $currEndTime = null) {
        if (empty($postponeNum) || empty($currEndTime)) {
            return false;
        }
        $currEndTime = strtotime($currEndTime);
        switch ($timeUnit) {
            case Status::AB_POSTPONE_TIME_UNIT_DAY :
                $endTime = date('Y-m-d H:i:s', strtotime("{$postponeNum} days", $currEndTime));
                break;
            case Status::AB_POSTPONE_TIME_UNIT_WEEK:
                $days = $postponeNum * 7;
                $endTime = date('Y-m-d H:i:s', strtotime("{$postponeNum} week", $currEndTime));
                break;
            case Status::AB_POSTPONE_TIME_UNIT_MONTH:
                $endTime = date('Y-m-d H:i:s', strtotime("{$postponeNum} month", $currEndTime));
                break;
            case Status::AB_POSTPONE_TIME_UNIT_YEAR:
                $endTime = date('Y-m-d H:i:s', strtotime("{$postponeNum} years", $currEndTime));
                break;
            default:
                $endTime = $currEndTime;
        }
        return $endTime;
    }
}
