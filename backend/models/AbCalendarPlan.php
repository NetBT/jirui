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
class AbCalendarPlan extends Common
{
    public static function tableName()
    {
        return '{{%ab_calendar_plan}}';
    }

    public function rules()
    {
        return [
            #添加供应商
            [['start', 'cameraman', 'assistant', 'dresser'], 'required','message' => '不能为空','on' => ['add', 'edit']],
        ];
    }

    /**
     * 设置属性名称
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'title' => '拍摄备注',
            'AB_order_number' => '筛选条件',
            'start' => '排项日期',
            'end' => '结束时间',
            'cameraman' => '摄影师',
            'assistant' => '助理',
            'dresser' => '化妆师',
            'status' => '拍摄状态',
            'mark' => '备注',
        ];
    }


    /**
     * 设置场景
     * @return array
     */
    public function scenarios()
    {
        $newScenarios =  [
            'add' => [
                'title',
                'AB_order_number',
                'AB_principal',
                'start',
                'cameraman',
                'assistant',
                'dresser',
                'mark',
            ],
            'edit' => [
                'title',
                'AB_order_number',
                'AB_principal',
                'start',
                'cameraman',
                'assistant',
                'dresser',
                'status',
                'mark'
            ],
        ];
        return array_merge(parent::scenarios(), $newScenarios);
    }
    /**
     * 获取供应商列表
     * @return array
     */
    public function getListData () {
        //搜索条件
        $start = Yii::$app->request->post('start');
        $end = Yii::$app->request->post('end');
        $orderNumber = Yii::$app->request->post('orderNumber');

        $start = date('Y-m-d 00:00:00', strtotime($start));
        $end = date('Y-m-d 23:59:59', strtotime($end));

        //判断是否是摄影师
        $whereStr = "`AB_id` = ".self::getBusinessId()." AND (`start` between '{$start}' AND '{$end}' OR `end` between '{$start}' AND '{$end}')";

//        if (!empty($orderNumber)) {
//            $whereStr .= " AND `AB_order_number` = '". $orderNumber."'";
//        }
        $list = static::getByWhere($whereStr, [
            'id', 'title', 'start', 'end', 'AB_order_number', 'cameraman'
        ]);
        $result = [];
        $isCameraMan = false;
        $whereEmployee['alliance_business_id'] = static::getBusinessId();
        $employeeList = Employee::getByWhere($whereEmployee, ['id', 'photographer_color']);
        $emMap = Functions::extractKey($employeeList, 'id', 'photographer_color');
        foreach ($list as $k => $v) {
            if(!empty($orderNumber) && $orderNumber == $v['AB_order_number']) {
                $list[$k]['color'] = "red";
                $list[$k]['textColor'] = "white";
            } else {
                $list[$k]['textColor'] = empty($emMap[$v['cameraman']]) ? 'black' : $emMap[$v['cameraman']];
                $list[$k]['color'] = 'white';
            }
            if ($v['cameraman'] == Yii::$app->user->getId()) {
                $isCameraMan = true;
            }
        }
        if ($isCameraMan) {
            foreach ($list as $k => $v) {
                if ($v['cameraman'] == Yii::$app->user->getId()) {
                    $result[] = $v;
                }
            }
        } else {
            $result = $list;
        }

        //获取数据
        return $result;
    }

    /**
     * 更新和添加操作
     * @return array
     */
    public function saveData() {
        $trans = Yii::$app->db->beginTransaction();
        try {
            if (!$this->validate()) {
                throw new Exception('校验失败');
            }
            $post = \Yii::$app->request->post('AbCalendarPlan');
            $timeSlot = \Yii::$app->request->post('timeSlot');
            if (empty($timeSlot)) {
                throw new Exception('请选择时间段');
            }
            $times = explode('~', $timeSlot);
            $starTime = $times[0]. ':00';
            $endTime = $times[1]. ':00';

            $orderInfo = [];
            //判断订单信息是否存在
            if (!empty($post['AB_order_number'])) {
                $orderInfo = MemberOrderCombo::getOneByWhere(['combo_order_number' => $post['AB_order_number']]);
                if (empty($orderInfo) || $orderInfo['business_id'] != static::getBusinessId()) {
                    throw new Exception('订单信息错误');
                }
            }

            $totalOrderInfo = MemberOrder::getOneByWhere(['order_number' => $orderInfo['order_number']]);
            $memberId = intval($totalOrderInfo['member_id']);
            $memberName = Member::getInfoByField($memberId,'name');

            $data['AB_id'] = self::getBusinessId();
            //修改信息
            if (isset($post['id']) && !empty($post['id'])) {
                $data = array_merge($data, $this->getSaveData('edit', $post));

                $data['end'] = $data['start'] . ' ' . $endTime;
                $data['start'] = $data['start'] . ' ' . $starTime;
                if($data['end'] <= $data['start']){
                    throw new Exception('结束时间不能小于开始时间');
                }
                if (date('Y-m-d H:i:s') > $data['end']) {
                    throw new Exception('排项时间不能小于当前时间');
                }

                $data['update_time'] = date("Y-m-d H:i:s");

                $data['mark'] = $post['mark'];

                //日历排项标题需要组装
//                $data['title'] = date('Y-m-d',strtotime($data['start'])).'-'.$memberName.'-'.$orderInfo['combo_name'];
                $data['title'] = $memberName.'-'.$orderInfo['combo_name'];
                //检查撞项信息
                $this->checkConflict($data, $post['id']);
                $res = static::updateDataWithLog($data, ['id' => $post['id']]);
                if ($res === false) {
                    throw new Exception('系统错误，请联系技术支持');
                }
            //添加信息
            } else {
                $data = array_merge($data, $this->getSaveData('add', $post));
                //根据时间段   确定出开始时间和结束时间
                $data['end'] = $data['start'] . ' ' . $endTime;
                $data['start'] = $data['start'] . ' ' . $starTime;
                if (date('Y-m-d H:i:s') > $data['end']) {
                    throw new Exception('排项时间不能小于当前时间');
                }
                if($data['end'] <= $data['start']){
                    throw new Exception('结束时间不能小于开始时间');
                }
                $data['create_time'] = date("Y-m-d H:i:s");
                $data['mark'] = $post['mark'];

                //日历排项标题需要组装
//                $data['title'] = date("Y-m-d").'-'.$memberName.'-'.$orderInfo['combo_name'];
                $data['title'] = $memberName.'-'.$orderInfo['combo_name'];
                //检查撞项信息
                $this->checkConflict($data);
                $res = static::insertDataWithLog($data);
                if ($res === false) {
                    throw new Exception('系统错误，请联系技术支持');
                }
                //更新order表
                $orderData['plan_status'] = Status::MEMBER_ORDER_PLAN_STATUS_YES;
                $orderData['update_time'] = date("Y-m-d H:i:s");

                $orderWhere['combo_order_number'] = $post['AB_order_number'];
                $MOCModel = new MemberOrderCombo();
                $res = $MOCModel->updateDataWithLog($orderData, $orderWhere);
                if ($res === false) {
                    throw new Exception('更新订单信息失败');
                }
                $data['id'] = $res;
            }
            if ($res === false) {
                throw new Exception(false);
            }
            $trans->commit();
            return Functions::formatJson(1000, '操作成功', $data);
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(2000, $e->getMessage());
        }
    }

    /**
     * 如果开启了撞项
     * @param array $data
     * @param int $id
     * @throws Exception
     */
    public function checkConflict($data = [], $id = 0) {
        $isConflict = Functions::getABCommonByKey('rush_on_off');
        //获取时间段 如果开启了撞项
        if ($isConflict == 1) {
            //检查当前摄影师，设置时间段内  有没有订单
            if ($data['cameraman']) {
                //获取该时间段内改摄影师拍摄信息
                $where = [];
                $where['cameraman'] = $data['cameraman'];
                $where['start'] = $data['start'];
                $where['end'] = $data['end'];
                $info = static::getOneByWhere($where);

                //添加时
                if (empty($id)) {
                    if (!empty($info)) {
                        throw new Exception('该摄影师在' . $data['start'] . '~' . $data['end'] . '已有排项');
                    }
                    $orderWhere['AB_order_number'] = $data['AB_order_number'];
                    $orderWhere['start'] = $data['start'];
                    $orderWhere['end'] = $data['end'];
                    $count = static::getCountByWhere($orderWhere);
                    if ($count > 0) {
                        throw new Exception('此订单在此时间段已派给其他摄影师');
                    }
                }
                //修改时
                if (!empty($id)) {
                    if ($info['id'] != $id) {
                        throw new Exception('该摄影师在' . $data['start'] . '~' .$data['end'] . '已有排项');
                    }
                    $orderWhere['AB_order_number'] = $data['AB_order_number'];
                    $orderWhere['start'] = $data['start'];
                    $orderWhere['end'] = $data['end'];
                    $andWhere[] = ['<>', 'id', $id];
                    $count = static::getCountByWhere($orderWhere);
                    if ($count > 1) {
                        throw new Exception('此订单在此时间段已派给其他摄影师');
                    }

                }
            }
        } else {
            //检查 订单是否指派了给了其他人  A摄影师这个时间段内可以共存多个订单
            //获取此时间段的此订单信息
            $where['AB_order_number'] = $data['AB_order_number'];
            $where['start'] = $data['start'];
            $where['end'] = $data['end'];
            $info = static::getOneByWhere($where);
            //获取该订单所有的排项，查询是否有指派给他人
            //判断是否撞xiang  同一订单号，排除改期在同一天的情况，在同一日期只能添加一次。
            if (empty($id)) {
                if (!empty($info)) {
                    //如果此时间段  此订单已经存在  则不允许重复添加
                    if ($info['cameraman'] == $data['cameraman']) {
                        throw new Exception('时间段此摄影师已有此订单的排项');
                    } else {
                        throw new Exception('此订单已经指派给其他摄影师');
                    }
                }
            } else {
                if(!empty($info)) {
                    if ($info['cameraman'] == $data['cameraman'] && $id != $info['id']) {
                        throw new Exception('此摄影师在' . $data['start'] . '已有此订单的排项');
                    }
                    if ($info['cameraman'] != $data['cameraman'] && $id != $info['id']) {
                        throw new Exception('此订单已经指派给其他摄影师');
                    }
                }
            }
        }
    }

    public function checkNoConfilct($data = [], $id = 0) {

    }

    /**
     * 月视图拖拽进行更改
     * @return array
     */
    public function saveDrop() {
        $id = intval(Yii::$app->request->post('id'));
        $startTime = Yii::$app->request->post('start');
        $endTime = Yii::$app->request->post('end');

        $oldInfo = self::getOneByWhere(['id' => $id]);

        //变化的只是日期，时间段不变化
        $oldStart = date('H:i:s',strtotime($oldInfo['start']));
        $oldEnd = date('H:i:s',strtotime($oldInfo['end']));

        $newDate = date('Y:m:d',strtotime($startTime));
        try {
            if (empty($startTime)) {
                throw new Exception('时间格式错误');
            }
            if(empty($id)) {
                throw new Exception('目标对象错误');
            }

            if (date('Y-m-d H:i:s') > $endTime) {
                throw new Exception('排项时间不能小于当前时间');
            }

            $data['start'] = $newDate.' '.$oldStart;
            $data['end'] = $newDate.' '.$oldEnd;
            $data['update_time'] = date('Y-m-d H:i:s');
            $res = static::updateDataWithLog($data, ['id' => $id]);
            if ($res === false) {
                throw new Exception('存储错误');
            }
            return Functions::formatJson(1000, '');
        } catch (Exception $e) {
            return Functions::formatJson(2000, $e->getMessage());
        }
    }
}
