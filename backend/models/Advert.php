<?php
namespace backend\models;

use common\models\Status;
use moonland\phpexcel\Excel;
use Yii;
use yii\base\Exception;
use common\models\Functions;

/**
 * 供应商信息表
 * Class AB
 * @package backend\models
 */
class Advert extends Common
{
    public static function tableName()
    {
        return '{{%advert}}';
    }

    /**
     * 验证规则
     */
    public function rules()
    {
        return [
            #不能为空
            [[
                'advert_name', 'advert_position', 'advert_commission', 'advert_tel',
                'start_time', 'end_time', 'advert_pay_money', 'advert_principal'
            ], 'required','message' => '不能为空','on' => ['add', 'edit']],
            //验证数字
            [['advert_pay_money', 'advert_commission'], 'number', 'message' => '必须为数字','on' => ['add', 'edit']],
        ];
    }

    /**
     * 设置属性名称
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'advert_name' => '广告名称',
            'advert_matter' => '广告素材',
            'advert_position' => '广告位置',
            'advert_commission' => '产品佣金',
            'advert_tel' => '联系电话',
            'advert_principal' => '负责人',
            'advert_payee' => '收款人',
            'start_time' => '投放时间',
            'end_time' => '结束时间',
            'advert_pay_money' => '收款',
            'advert_balance' => '余额',
            'advert_pay_type' => '支付方式',
            'mark' => '备注',
            'status' => '状态',
            'advert_order' => '广告排序',
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
                'advert_name',
                'advert_matter',
                'advert_position',
                'advert_commission',
                'advert_principal',
                'advert_tel',
                'start_time',
                'end_time',
                'advert_pay_money',
                'advert_pay_type',
                'goods_texture',
                'goods_style',
                'mark',
            ],
            'edit' => [
                'advert_name',
                'advert_matter',
                'advert_position',
                'advert_commission',
                'advert_principal',
                'advert_tel',
                'start_time',
                'end_time',
                'advert_pay_money',
                'advert_pay_type',
                'goods_texture',
                'goods_style',
                'mark',
            ],
        ];
        return array_merge(parent::scenarios(), $newScenarios);
    }
    /**
     * 获取商品列表
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
        //计算总数
        $where = [
            'status' => [Status::ADVERT_STATUS_NORMAL, Status::ADVERT_STATUS_STOP],
        ];
        $andWhere = [];
        if(isset($post['advert_name']) && !empty($post['advert_name'])){
            $andWhere = ['like', 'advert_name', $post['advert_name']];
        }
        $count = static::getCountByAndWhere($where, $andWhere);

        $returnData["recordsTotal"] = $returnData['recordsFiltered'] = intval($count);

        //设置分页
        $this->setPagination();
        //获取数据
        $returnData['data'] = static::getByAndWhere($where, $andWhere, ['*'], 'create_time desc', $this->_Pagination['offset'], $this->_Pagination['limit']);
        $positionMap = Status::getPositionMap();
        if (!empty($returnData['data'])) {
            foreach ($returnData['data'] as $k => $v) {
                $info = Employee::getEmployeeNameById([$v['advert_payee'], $v['advert_handler_id'], $v['advert_principal']], 'employee_name');
                $returnData['data'][$k]['advert_payee'] = !empty($info[$v['advert_payee']]) ? $info[$v['advert_payee']] : '';
                $returnData['data'][$k]['advert_handler_user'] = !empty($info[$v['advert_handler_id']]) ? $info[$v['advert_handler_id']] : '';
                $returnData['data'][$k]['advert_principal'] = !empty($info[$v['advert_principal']]) ? $info[$v['advert_principal']] : '';
                $returnData['data'][$k]['advert_position'] = $positionMap[$v['advert_position']];
            }
        }
        return $returnData;
    }

    /**
     * 保存商品信息
     * @return array
     */
    public function saveData() {
        $trans = Yii::$app->db->beginTransaction();
        try {
            if (!$this->validate()) {
                throw new Exception('校验失败');
            }
            $post = \Yii::$app->request->post('Advert');
            $id = isset($post['id']) && !empty($post['id']) ? intval($post['id']) : null;
            $data = [];
            if (!empty($id)) {
                $data = array_merge($data, $this->getSaveData('edit', $post));
                $data['advert_handler_id'] = \Yii::$app->user->getId();
                $data['advert_payee'] = \Yii::$app->user->getId();
                $data['update_time'] = date("Y-m-d H:i:s");
                $uploadFile = Functions::uploadFile('advert_matter');

                if ($uploadFile !== false) {
                    $data['advert_matter'] = $uploadFile;
                }
                $res = static::updateDataWithLog($data, ['id' => $post['id']]);
                if ($res === false) {
                    throw new Exception('数据库更新失败');
                }
            } else {
                $data = array_merge($data, $this->getSaveData('add', $post));
                $data['advert_payee'] = \Yii::$app->user->getId();
                $data['create_time'] = date("Y-m-d H:i:s");
                $data['advert_balance'] = $data['advert_pay_money'] - $data['advert_commission'];
                $data['advert_handler_id'] = \Yii::$app->user->getId();
                //更新图片
                $data['advert_matter'] = Functions::uploadFile('advert_matter');
                $res = static::insertDataWithLog($data);
                if ($res === false) {
                    Functions::deleteUploadFile($data['advert_matter']);
                    throw new Exception('数据插入失败');
                }
            }
            $trans->commit();
            return Functions::formatJson(1000, '操作成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(2000, $e->getMessage());
        }
    }

    public function toggleStatus($id = 0) {
        $trans = Yii::$app->db->beginTransaction();
        try {
            $id = intval($id);
            if (empty($id)) {
                throw new Exception('信息错误');
            }
            $info = static::getOneByWhere(['id' => $id]);
            if (empty($info)) {
                throw new Exception('未找到加盟商信息');
            }
            if ($info['status'] === Status::ADVERT_STATUS_DELETE) {
                throw new Exception('广告已经被删除');
            }
            $data['update_time'] = date('Y-m-d H:i:s');
            if ($info['status'] == Status::ADVERT_STATUS_NORMAL) {
                $data['status'] = Status::ADVERT_STATUS_STOP;
            }else {
                $data['status'] = Status::ADVERT_STATUS_NORMAL;
            }
            $res = $this->updateDataWithLog($data, ['id' => $id]);
            if ($res === false) {
                throw new Exception('数据库操作有误');
            }
            $trans->commit();
            return Functions::formatJson(1000, '操作成功');
        } catch ( Exception $e ) {
            $trans->rollBack();
            return Functions::formatJson(2000, $e->getMessage());
        }
    }

    /**
     * 删除数据  --逻辑删除
     * @param null $id
     *
     * @return bool|string
     */
    public function deleteData($id = null) {
        $trans = Yii::$app->db->beginTransaction();
        try {
            if (empty($id)) {
                throw new Exception('数据格式错误');
            }
            $data['status'] = Status::ADVERT_STATUS_DELETE;
            $data['update_time'] = date('Y-m-d H:i:s');
            $where['id'] = intval($id);
            $res = static::updateDataWithLog($data, $where);
            if ($res === false) {
                throw new Exception('系统错误');
            }
            $trans->commit();
            return  Functions::formatJson(1000, '删除成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return  Functions::formatJson(2000, $e->getMessage());
        }
    }

    /**
     * 随机去n条广告
     * @param int $num
     * @param int $position
     *
     * @return array|mixed|\yii\db\ActiveRecord[]
     */
    public static function getAdvertRand($num = 2, $position = Status::ADVERT_POSITION_RIGHT) {
        $adVert['advert_position'] = $position;
        $adVert['status'] = Status::ADVERT_STATUS_NORMAL;
        $date = date("Y-m-d H:i:s");
        $andWhere[] = ['<=', 'start_time', $date];
        $andWhere[] = ['>=', 'end_time', $date];
        $list = static::getByAndWheres($adVert, $andWhere, ['id', 'advert_matter', 'advert_name']);
        $count = count($list);
        $result = [];
        if ($count > $num) {
            $tmp = array_rand($list, $num);
            if (is_array($tmp)) {
                foreach ($tmp as $v) {
                    $result[] = $list[$v];
                }
            } else {
                $result[] = $list[$tmp];
            }
        } else {
            $result = $list;
        }
        //更新显示次数
        return $result;
    }

    public function echartsAdvertNum () {
        $post = Yii::$app->request->post();
        $startTime = !empty($post["start"]) ? date("Y-m-d 00:00:00", strtotime($post["start"])) : date("Y-m-d 00:00:00");
        $endTime = !empty($post["end"]) ? date("Y-m-d 23:59:59", strtotime($post["end"])) : date("Y-m-d 23:59:59");

        //获取下单量
        $andWhere[] = ['between', 'create_time', $startTime, $endTime];
        $list = static::getByAndWheres([], $andWhere, ['id','create_time']);
        $timeMap = Functions::getTimeForEchart($startTime, $endTime);
        $result = ['xAxis' => ['data' => []], 'series' => ['data' => []]];
        foreach ($timeMap as $v) {
            $num = 0;
            foreach ($list as $kk => $vv) {
                if ($vv['create_time'] >= $v['start'] && $vv['create_time'] <= $v['end']) {
                    $num ++;
                }
            }
            $tmp = [$v['xAxis'], $num];
            $result['series']['data'][] = $tmp;
        }
        return $result;
    }


    public function getTotal() {
        $post = Yii::$app->request->post();
        $startTime = !empty($post["start"]) ? date("Y-m-d 00:00:00", strtotime($post["start"])) : date("Y-m-d 00:00:00");
        $endTime = !empty($post["end"]) ? date("Y-m-d 23:59:59", strtotime($post["end"])) : date("Y-m-d 23:59:59");
        $data = [
            'totalNum' => 0,
            'totalMoney' => 0,
        ];
        //获取广告总数
        $where['status'] = [Status::ADVERT_STATUS_NORMAL, Status::ADVERT_STATUS_STOP];
        $andWhere = ['between', 'create_time', $startTime, $endTime];
        $data['totalNum'] = static::getCountByAndWhere([], $andWhere);
        //获取广告总金额
        $where['status'] = [Status::ADVERT_STATUS_NORMAL, Status::ADVERT_STATUS_STOP];
        $andWhere = ['between', 'create_time', $startTime, $endTime];
        $data['totalMoney'] = static::getSumByWhereAndWhere($where, $andWhere, 'advert_pay_money');
        return Functions::formatJson(1000, '成功', $data);
    }

    /**
     * 导出excel
     */
    public function exportExcel()
    {
        $where['status'] = [Status::ADVERT_STATUS_NORMAL, Status::ADVERT_STATUS_STOP];
        $list = self::find()->where($where)->asArray()->all();
        $employeeInfo = Employee::getFormArray('','id','employee_name');

        foreach ($list as $key => $value) {
            $list[$key]['advert_payee'] = $value['advert_payee'] ? $employeeInfo[$value['advert_payee']] : '--';
            $list[$key]['advert_principal'] = $value['advert_principal'] ? $employeeInfo[$value['advert_principal']] : '--';
            $list[$key]['advert_position'] = $value['advert_position'] ? Status::getPositionMap()[$value['advert_position']] : '--';
            $list[$key]['status'] = $value['status'] ? Status::advertStatusMap()[$value['status']] : '--';
        }
        Excel::export([
            'models' => $list,
            'fileName' => date('Ymd').'导出广告信息',
            'columns' => [
                'advert_name',
                'advert_position',
                'advert_commission',
                'advert_tel',
                'advert_principal',
                'advert_payee',
                'start_time',
                'end_time',
                'advert_pay_money',
                'advert_balance',
                'advert_pay_type',
                'mark',
                'status',
            ], //没有头工作,因为头会得到标签的属性标签
            'headers' => [
                'advert_name' => '广告名称',
                'advert_position' => '广告位置',
                'advert_commission' => '产品佣金',
                'advert_tel' => '联系电话',
                'advert_principal' => '负责人',
                'advert_payee' => '收款人',
                'start_time' => '投放时间',
                'end_time' => '结束时间',
                'advert_pay_money' => '收款',
                'advert_balance' => '余额',
                'advert_pay_type' => '支付方式',
                'mark' => '备注',
                'status' => '状态',
            ],
        ]);
    }
}
