<?php

namespace backend\models;

use common\models\Functions;
use common\models\Status;
use Yii;
use yii\base\Exception;

/**
 * 加盟商流水
 * Class ABStatement
 * @package backend\models
 */
class ABStatement extends Common
{

    public static function tableName() {
        return '{{%ab_statement}}';
    }

    /**
     * 记录加盟商店铺流水
     * @param float  $money
     * @param int    $type
     * @param string $mark
     *
     * @return array|bool
     */
    public static function recordStatement($type = 0, $money = 0.0, $mark = '') {
        try {
            $money = floatval($money);
            $type = intval($type);
            $typeMap = Status::getABStatementTypeMap();
            if (!array_key_exists($type, $typeMap)) {
                throw new Exception('类型不存在');
            }

            $mark = strval($mark);
            $data['AB_id'] = static::getBusinessId();
            $data['type'] = $type;
            $data['money'] = $money;
            $data['mark'] = $mark;
            $data['operate_user'] = Yii::$app->user->getId();
            $data['create_time'] = date('Y-m-d H:i:s');

            $res = static::insertData($data);
            if (!$res) {
                throw new Exception("流水记录失败");
            }
            return true;
        } catch (Exception $e) {
            return ["code"=> 2000, "message" => $e->getMessage()];
        }
    }


    public static function getIncome($start = null, $end = null) {
        if (empty($start)) {
            $start = date("Y-m-d 00:00:00");
        }
        if (empty($end)) {
            $end = date("Y-m-d 23:59:59");
        }
        $where['AB_id'] = static::getBusinessId();
        $andWhere = ['between', 'create_time', $start, $end];
        $income = static::getSumByWhereAndWhere($where, $andWhere, 'money');
        return floatval($income);
    }

    /**
     * return [
    'xAxis' => ['data' => [
            '2018-01',
            '2018-02',
            '2018-03',
            '2018-04',
            '2018-05',
    ]],
    'series' => ['data' => [900, 100, 200, 300, 400]]
    ];
     */
    public function getHomeEchartsData() {
        $xData = $series = [];
        for($i = 11; $i >= 0; $i--) {
            $month = Functions::getMonthStartEnd(date('Y-m', strtotime('-'. $i .' month')));
            $xData[] = $month['month'];
            $where['AB_id'] = static::getBusinessId();
            $andWhere = ['between', 'create_time', $month['startDate'], $month['endDate']];
            $income = static::getSumByWhereAndWhere($where, $andWhere, 'money');
            $series[] = floatval($income);
        }
        return [
            'xAxis' => ['data' => $xData],
            'series' => ['data' => $series],
        ];
    }
    /**
     * return [
     *      'legend' => [
     *          'data' => ['1','2'..]
     *      ]
     *      'series' => [
     *          'data' => [['value' => 900, 'name'=> '西单大悦城店']]]
     * ];
     */
    public function getHomeStoreIncomePieData() {
        $legend = $series = [];
        $abList = AB::getByWhere([], ['id', 'AB_name']);
        $andWhere = ['between', 'create_time', date('Y-m-d 00:00:00', strtotime('-15 days')), date('Y-m-d 23:59:59')];
        foreach ($abList as $v) {
            $where['AB_id'] = $v['id'];
            $tmp['name'] = $v['AB_name'];
            $tmp['value'] = floatval(static::getSumByWhereAndWhere($where, $andWhere, 'money'));
            if ($tmp['value'] > 0) {
                $series[] = $tmp;
                $legend[] = $v['AB_name'];
            }
        }
        return [
            'legend' => ['data' => $legend],
            'series' => ['data' => $series],
        ];
    }
}
