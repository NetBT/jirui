<?php

namespace backend\models;

use common\models\Functions;
use common\models\Status;
use Yii;
use yii\base\Exception;

/**
 * ContactForm is the model behind the contact form.
 */
class ABCoinChange extends Common
{

    public static function tableName() {
        return '{{%ab_coin_change}}';
    }

    /**
     * 记录加盟商帐变记录
     * @param int $ABId
     * @param float $changeMoney
     * @param int $changeType
     * @param string $mark
     * @return array | boolean
     */
    public static function recordCoinChange($ABId = 0, $changeMoney = 0.0, $changeType = 0, $mark = '') {
        $db = Yii::$app->db;
        $trans = $db->beginTransaction();
        try {

            $ABId = intval($ABId);
            $changeMoney = floatval($changeMoney);
            $changeType = intval($changeType);
            //检查用户
            $ABInfo = AB::find()->select([
                'id',
                'AB_balance',
            ])->where(["id" => $ABId])->asArray()->one();

            if (empty($ABInfo)) {
                throw new Exception("用户不存在", 10001);
            }
            $allType = Status::getABCoinChangeAllType();
            if (!array_key_exists($changeType, $allType)) {
                throw new Exception("帐变类型不存在", 10003);
            }
            //成功
            $insertData["change_type"] = $changeType;
            $insertData["create_time"] = date("Y-m-d H:i:s");
            $insertData["AB_id"] = $ABId;
            $insertData["handler"] = Yii::$app->user->getId();
            $insertData["before_change"] = $ABInfo["AB_balance"];
            $insertData["mark"] = $mark;
            if (in_array($changeType, Status::getABCoinChangeIncomeType())) {
                $insertData["change_money"] = abs($changeMoney);
                $insertData["after_change"] = bcadd($ABInfo["AB_balance"], $insertData["change_money"], 5);

            } else if(in_array($changeType, Status::getABCoinChangeOutcomeType())) {
                $insertData["change_money"] = abs($changeMoney) * -1;
                $insertData["after_change"] = bcadd($ABInfo["AB_balance"], $insertData["change_money"], 5);
            } else {
                $insertData["change_money"] = $changeMoney;
                $insertData["after_change"] = bcadd($ABInfo["AB_balance"], $insertData["change_money"], 5);
            }

            //更新用户信息
            $ABData["AB_balance"] = $insertData["after_change"];

            $ABData["AB_update_time"] = date("Y-m-d H:i:s");

            $ABWhere['id'] = $ABId;

            $res = AB::updateData($ABData, $ABWhere);
            if (!$res) {
                throw new Exception("加盟商金额更新失败", 10005);
            }

            $insertData["AB_id"] = $ABId;

            $res = static::insertData($insertData);
            if (!$res) {
                throw new Exception("帐变记录失败", 10004);
            }
            $trans->commit();
            return true;
        } catch (Exception $e) {
            $trans->rollBack();
            return ["code"=> $e->getCode(), "message" => $e->getMessage()];
        }
    }

    /**
     * DataTable 数据加载
     * @return mixed
     */
    public function getListData() {
        $returnData = [
            "draw" => intval(Yii::$app->request->post('draw')),
            "recordsTotal" => 0,
            "recordsFiltered" => 0,
            "data" => null
        ];
        //自定义搜索条件，组装where条件
        $where = [];
        $params = Yii::$app->request->post('extra_search');
        $startTime = isset($params['startTime']) ? date('Y-m-d 00:00:00',strtotime($params['startTime'])) : date("Y-m-d 00:00:00");
        $endTime = isset($params['endTime']) ? date('Y-m-d 23:59:59',strtotime($params['endTime'])) : date("Y-m-d 23:59:59");
        //获取ABId
        $whereStr = '';
        if (!empty($params['ABName'])) {
            $whereStr .= "`AB_name` LIKE '%{$params['ABName']}%' ";
        }
        if (!empty($params['ABNumber'])) {
            $whereStr .= "AND `AB_number` = '{$params['ABNumber']}' ";
        }
        $whereStr = trim($whereStr, 'AND');

        $ABList = AB::getByWhere($whereStr, ['id', 'AB_name', 'AB_number']);
        $ABIds = Functions::extractKey($ABList, 'id');
        $where['AB_id'] = array_keys($ABIds);
        $andWhere = ['between', 'create_time', $startTime, $endTime];
        //得到文章的总数（但是还没有从数据库取数据）
        $count = static::getCountByAndWhere($where, $andWhere);
        $returnData["recordsTotal"] = $returnData['recordsFiltered'] = intval($count);

        $this->setPagination();

        //最终返回的data数据
        $list = static::getByAndWhere($where, $andWhere, '*', 'id desc', $this->_Pagination['offset'], $this->_Pagination['limit']);
        $handler = Employee::getByWhere([], 'id, employee_name');
        $handler = Functions::extractKey($handler, 'id', 'employee_name');

        $typeMap = Status::getABCoinChangeAllType();
        //拼装数据
        $returnData['data'] = [];
        foreach ($list as $k => $v) {
            $list[$k]['operate_user'] = isset($handler[$v['handler']]) ? $handler[$v['handler']] : '--';
            $list[$k]['AB_name'] = isset($ABIds[$v['AB_id']]['AB_name']) ? $ABIds[$v['AB_id']]['AB_name'] : '--';
            $list[$k]['AB_number'] = isset($ABIds[$v['AB_id']]['AB_number']) ? $ABIds[$v['AB_id']]['AB_number'] : '--';
            $list[$k]['change_type'] = isset($typeMap[$v['change_type']]) ? $typeMap[$v['change_type']] : '--';
        }
        $returnData['data'] = $list;
        return $returnData;
    }
}
