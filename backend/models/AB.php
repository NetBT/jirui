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
class AB extends Common
{
    public static function tableName()
    {
        return '{{%ab_info}}';
    }
    /**
     * 验证规则
     */
    public function rules()
    {
        return [
            #添加供应商
            [[
                'AB_number', 'AB_name', 'AB_principal', 'AB_start_time', 'AB_end_time',
                'AB_store_status', 'AB_store_type', 'AB_collection', 'AB_collection_user', 'AB_code'
            ], 'required','message' => '不能为空','on' => ['add', 'edit']],

            //验证合同编号
            ['AB_number', 'unique', 'message' => '合同编号已存在', 'on' => 'add'],
            //验证数字
            [['AB_collection', 'AB_alliance_fee'], 'number', 'message' => '必须为数字','on' => ['add', 'edit']],
            //验证负责人
            ['AB_principal', 'validatePrincipal','on' => ['add', 'edit']],//验证负责人

            ['AB_code', 'validateABCode','on' => ['add', 'edit']],
            //验证收款人
            ['AB_collection_user', 'validateCollectionUser','on' => ['add', 'edit']],
            //验证邮编
            ['AB_store_code', 'validateStoreCode','on' => ['add', 'edit']],
            //验证类型和状态
            ['AB_store_type', 'in', 'range' => [Status::AB_STORE_TYPE_COMMON, Status::AB_STORE_TYPE_ADVANCED],'on' => ['add', 'edit']],

            ['AB_store_status', 'in', 'range' => [Status::AB_STORE_STATUS_LOCK, Status::AB_STORE_STATUS_UNLOCK],'on' => ['add', 'edit']],

            ['AB_create_time', 'default', 'value' => date("Y-m-d H:i:s"),'on' => 'add'],
            ['AB_update_time', 'default', 'value' => date("Y-m-d H:i:s"),'on' => 'edit'],

            ['AB_operate_id', 'default', 'value' => Yii::$app->user->getId() ,'on' => 'add'],

            #加盟商自己修改信息
            [['AB_name','AB_principal','AB_tel','AB_address'], 'required', 'message' => '不能为空', 'on' => 'editBySelf'],
        ];
    }

    /**
     * 设置属性名称
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'AB_number' => '合同编号',
            'AB_name' => '店铺名称',
            'AB_principal' => '负责人',
            'AB_tel' => '联系电话',
            'AB_address' => '联系地址',
            'AB_start_time' => '开通时间',
            'AB_end_time' => '到期时间',
            'AB_store_status' => '店铺锁定',
            'AB_store_type' => '店铺类型',
            'AB_permission' => '权限',
            'AB_collection' => '收款',
            'AB_collection_user' => '收款人',
            'AB_balance' => '余额',
            'AB_store_mark' => '备注',
            'AB_alliance_fee' => '加盟费',
            'AB_store_code' => '邮编',
            'AB_code' => '店铺编码',
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
                'AB_number',
                'AB_name',
                'AB_principal',
                'AB_tel',
                'AB_address',
                'AB_start_time',
                'AB_end_time',
                'AB_store_status',
                'AB_store_type',
                'AB_store_mark',
                'AB_permission',
                'AB_collection',
                'AB_balance',
                'AB_collection_user',
                'AB_alliance_fee',
                'AB_store_code',
                'AB_create_time',
                'AB_operate_id',
                'AB_code',
            ],
            'edit' => [
                'AB_name',
                'AB_principal',
                'AB_tel',
                'AB_address',
                'AB_store_status',
                'AB_permission',
                'AB_store_type',
                'AB_store_mark',
                'AB_collection',
                'AB_balance',
                'AB_collection_user',
                'AB_alliance_fee',
                'AB_store_code',
                'AB_code',
            ],
            'editBySelf' => [
                'AB_name',
                'AB_principal',
                'AB_tel',
                'AB_address',
                'AB_store_mark',
                'AB_store_code',
            ],
        ];
        return array_merge(parent::scenarios(), $newScenarios);
    }

    /**
     * 验证负责人信息
     * @param $attribute
     * @param $params
     */
    public function validatePrincipal ($attribute, $params) {
        if (!$this->hasErrors()) {
            $count = Employee::getOneByWhere(['id' => $this->AB_principal]);
            if ($count == 0) {
                $this->addError($attribute, '负责人不存在');
            }
        }
    }
    /**
     * 验证负责人信息
     * @param $attribute
     * @param $params
     */
    public function validateABCode ($attribute, $params) {
        if (!$this->hasErrors()) {
            if (strlen($this->AB_code) > 5) {
                $this->addError($attribute, '编码必须小于5位');
            }

            if(!preg_match('/^[A-Za-z]{1,5}$/', $this->AB_code)) {
                $this->addError($attribute, '编码格式不正确');
            }
//            $info = AB::getOneByWhere(['AB_code' => $this->AB_code]);
//            if (empty($this->id) && !empty($info)) {
//                $this->addError($attribute, '编码已存在');
//            }
//            if(!empty($this->id) && $info['id'] != $this->id) {
//                $this->addError($attribute, '编码冲突');
//            }
        }
    }

    /**
     * 验证收款人信息
     * @param $attribute
     * @param $params
     */
    public function validateCollectionUser ($attribute, $params) {
        if (!$this->hasErrors()) {
            $count = Employee::getOneByWhere(['id' => $this->AB_collection_user]);
            if ($count == 0) {
                $this->addError($attribute, '收款人不存在');
            }
        }
    }
    /**
     * 验证邮编信息
     * @param $attribute
     * @param $params
     */
    public function validateStoreCode ($attribute, $params) {
        if (!$this->hasErrors()) {
            if ($this->AB_store_code < 100000 || $this->AB_store_code > 999999) {
                $this->addError($attribute, '邮编格式不正确');
            }
        }
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
        $whereStr = "`AB_delete` = " . Status::AB_ID_NOT_DELETE;
        if (!empty($contractOrPhone)) {
            $whereStr = " AND AB_tel = '{$contractOrPhone}' OR AB_number = '{$contractOrPhone}'";
        }
        $count = static::getCountByWhere($whereStr);
        $returnData["recordsTotal"] = $returnData['recordsFiltered'] = intval($count);

        //设置分页
        $this->setPagination();
        //获取数据
        $returnData['data'] = static::getByWhere($whereStr, [
            'id', 'AB_number', 'AB_name', 'AB_principal', 'AB_tel', 'AB_address', 'AB_alliance_fee',
            'AB_balance','AB_start_time', 'AB_end_time', 'AB_operate_id', 'AB_store_status'
        ], 'AB_create_time desc', $this->_Pagination['offset'], $this->_Pagination['limit']);
        if (!empty($returnData['data'])) {
            foreach ($returnData['data'] as $k => $v) {
                $opearteInfo = Employee::getOneByWhere(['id' => $v['AB_operate_id']], 'employee_name');
                $returnData['data'][$k]['AB_operate_name'] = $opearteInfo['employee_name'];
                $principalInfo = Employee::getOneByWhere(['id' => $v['AB_principal']], 'employee_name');
                $returnData['data'][$k]['AB_principal'] = $principalInfo['employee_name'];

                $endTime = strtotime($v['AB_end_time']);
                $startTime = strtotime($v['AB_start_time']);
                $currentTime = time();
                $returnData['data'][$k]['notice_expire'] = '';
                $expire = ($endTime - $currentTime)/3600/24;
                if($endTime < $currentTime) {
                    $returnData['data'][$k]['notice_expire'] = '已到期';
                } else {
                    if($expire <= Functions::getCommonByKey('notice_ab_expire')) {
                        $returnData['data'][$k]['notice_expire'] = '还有'.floor($expire).'天到期';
                    }
                }
            }
        }
        return $returnData;
    }

    public function saveData() {
        $trans = Yii::$app->db->beginTransaction();
        try {
            if (!$this->validate()) {
                throw new Exception('数据有误');
            }
            $employee = new Employee();
            $post = \Yii::$app->request->post('AB');
            $data['AB_operate_id'] = \Yii::$app->user->getId();
            if (isset($post['id']) && !empty($post['id'])) {
                $data = array_merge($data, $this->getSaveData('edit', $post));
                $data['AB_balance'] = $post['AB_collection'] - $post['AB_alliance_fee'];
                $data['AB_update_time'] = date("Y-m-d H:i:s");
                $data['AB_code'] = strtoupper($data['AB_code']);
                $emData['alliance_business_id'] = $post['id'];
                $emData['update_time'] = date('Y-m-d H:i:s');
                $emWhere['id'] = $data['AB_principal'];
                $abInfo = AB::getOneByWhere(['id' => $post['id']]);
                //判断是否修改了负责人
                if ($abInfo['AB_principal'] != $data['AB_principal']) {
                    if (! empty($data['AB_principal'])) {
                        $employeeInfo = Employee::getOneByWhere(['id' => $abInfo['AB_principal']]);
                        //将以前的负责人去掉权限
                        $oldPrincipal['alliance_business_id'] = 1;
                        $oldPrincipal['post_id'] = null;
                        $oldPrincipal['update_time'] = date('Y-m-d H:i:s');
                        $oldWhere['id'] = $abInfo['AB_principal'];
                        $res = $employee->updateDataWithLog($oldPrincipal, $oldWhere);

                        if ($res === false) {
                            throw new Exception('撤销原始负责人失败');
                        }

                        //将现在负责人赋予权限
                        $emData['alliance_business_id'] = $post['id'];
                        $emData['update_time'] = date('Y-m-d H:i:s');
                        $emData['post_id'] = $employeeInfo['post_id'];
                        $emWhere['id'] = $data['AB_principal'];
                        $res = $employee->updateDataWithLog($emData, $emWhere);
                        if ($res === false) {
                            throw new Exception('新负责人授权失败');
                        }
                    }

                }
                $res = static::updateDataWithLog($data, ['id' => $post['id']]);
                if ($res === false) {
                    throw new Exception('更新数据失败');
                }
            } else {
                $rechargeData['recharge_money'] = $post['AB_collection'];
                $rechargeData['recharge_user'] = $post['AB_collection_user'];
                $rechargeData['AB_name'] = $post['AB_name'];
                $rechargeData['AB_number'] = $post['AB_number'];
                $rechargeData['recharge_time'] = date("Y-m-d H:i:s");
                $data = array_merge($data, $this->getSaveData('add', $post));
                $data['AB_balance'] = $post['AB_collection'] - $post['AB_alliance_fee'];
                $data['AB_create_time'] = date("Y-m-d H:i:s");
                $data['AB_code'] = strtoupper($data['AB_code']);
                $abId = static::insertDataWithLog($data);
                //生成权限
                $emPost = new EmployeePost();
                $abPostInfo = ABPost::getOneByWhere(['id' => $data['AB_store_type']]);
                $emPostInfo['business_id'] = $abId;
                $emPostInfo['post_name'] = '加盟商负责人';
                $emPostInfo['module_content'] = $abPostInfo['module_content'];
                $emPostInfo['create_time'] = date('Y-m-d H:i:s');
                $emPostInfo['status'] = Status::EMPLOYEE_POST_SUCCESS;
                $postId = $emPost->insertDataWithLog($emPostInfo);
                if ($postId === false) {
                    throw new Exception('权限生成失败');
                }
                //将负责人作为加盟商账号
                $emData['alliance_business_id'] = $abId;
                $emData['post_id'] = $postId;
                $emData['update_time'] = date('Y-m-d H:i:s');
                $emWhere['id'] = $data['AB_principal'];
                $res = $employee->updateDataWithLog($emData, $emWhere);
                if ($res === false) {
                    throw new Exception('负责人修改失败');
                }
                //复制common_info
                $ABCommon = new ABCommon();
                $status = $ABCommon->createCommon($abId);
                if ($status['code'] != 1000) {
                    throw new Exception($status['message']);
                }
                $rechargeM = new AbRecharge();
                $resRecharge = $rechargeM->insertDataWithLog($rechargeData);
                if ($resRecharge === false) {
                    throw new Exception('充值日志记录失败');
                }
            }
            $trans->commit();
            return Functions::formatJson(1000,'添加成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(2000,$e->getMessage());
        }
    }

    /**
     * 加盟商自己修改资料
     * @return bool
     */
    public function saveDataBySelf() {
        $trans = Yii::$app->db->beginTransaction();
        try {
            if (!$this->validate()) {
                throw new Exception(false);
            }
            $post = Yii::$app->request->post('AB');
            $data = [];
            if (isset($post['id']) && !empty($post['id'])) {
                $data = array_merge($data, $this->getSaveData('editBySelf', $post));
                $data['AB_update_time'] = date("Y-m-d H:i:s");
                $res = static::updateDataWithLog($data, ['id' => $post['id']]);
                if ($res === false) {
                    throw new Exception(false);
                }
            } else {
                throw new Exception(false);
            }
            $trans->commit();
            return true;
        } catch (Exception $e) {
            $trans->rollBack();
            return false;
        }
    }

    /**
     * 删除数据
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
            $data['AB_delete'] = Status::AB_IS_DELETE;
            $data['AB_update_time'] = date('Y-m-d H:i:s');
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

            $data['AB_update_time'] = date('Y-m-d H:i:s');
            if ($info['AB_store_status'] == Status::AB_STORE_STATUS_LOCK) {
                $data['AB_store_status'] = Status::AB_STORE_STATUS_UNLOCK;
            }else {
                $data['AB_store_status'] = Status::AB_STORE_STATUS_LOCK;
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
     * 获取合同编号
     * @return string
     */
    public static function makeABNumber() {
        //获取当日新增加盟商数量
        do {
            $num = '';
            $curr = date('Ymd');
            $amount = static::getCountByAndWhere([], ['>=', 'AB_create_time', $curr]);
            $amount ++;
            $amount = Functions::zeroFill($amount,2,0);
            $num = 'JR-ZL-'.$curr.$amount;
            $res = static::getOneByWhere(['AB_number' => $num]);
        } while(!empty($res));
        return $num;
    }

    /**
     * 检查店铺信息
     * @param string $login_name
     *
     * @return array
     */
    public static function checkABInfo($login_name = '') {
        $ABId = null;
        if (!empty($login_name)) {
            $where['login_name'] = $login_name;
            $employeeInfo = Employee::getOneByWhere($where, 'alliance_business_id');
            $ABId = $employeeInfo['alliance_business_id'];
        } else {
            $ABId = static::getBusinessId();
        }
        $result = [
            'isLock' => ['status' => true, 'message' => ''],
            'isStart' => ['status' => false, 'message' => ''],
            'isEnd' => ['status' => true, 'message' => ''],

        ];
        if (!empty($ABId) && $ABId != 1) {
            $abWhere['id'] = $ABId;
            $abInfo = AB::getOneByWhere($abWhere, ['AB_store_status', 'AB_end_time','AB_start_time']);

            //判断是否锁定
            $result['isLock']['status'] = $abInfo['AB_store_status'] == Status::AB_STORE_STATUS_LOCK ? true : false;
            $result['isLock']['message'] = $abInfo['AB_store_status'] == Status::AB_STORE_STATUS_LOCK ? '店铺已锁定' : '';
            //判断是否在有效时间内
            $currDate = date('Y-m-d H:i:s');

            $result['isStart']['status'] = $abInfo['AB_start_time'] > $currDate ? false : true;
            $result['isStart']['message'] = $abInfo['AB_start_time'] > $currDate ? '店铺未到开通时间' : '';

            $result['isEnd']['status'] = $abInfo['AB_end_time'] < $currDate ? true : false;
            $result['isEnd']['message'] = $abInfo['AB_end_time'] < $currDate ? '店铺有效时间已过' : '';

        }else {
            $result = [
                'isLock' => ['status' => false, 'message' => ''],
                'isStart' => ['status' => true, 'message' => ''],
                'isEnd' => ['status' => false, 'message' => ''],
            ];
        }
        return $result;
    }


    public function echartsOpenNum () {
        $post = Yii::$app->request->post();
        $startTime = !empty($post["start"]) ? date("Y-m-d 00:00:00", strtotime($post["start"])) : date("Y-m-d 00:00:00");
        $endTime = !empty($post["end"]) ? date("Y-m-d 23:59:59", strtotime($post["end"])) : date("Y-m-d 23:59:59");

        //获取下单量
        $andWhere[] = ['between', 'AB_create_time', $startTime, $endTime];
        $list = static::getByAndWheres([], $andWhere, ['id','AB_create_time']);
        $timeMap = Functions::getTimeForEchart($startTime, $endTime);
        $result = ['xAxis' => ['data' => []], 'series' => ['data' => []]];
        foreach ($timeMap as $v) {
            $num = 0;
            foreach ($list as $kk => $vv) {
                if ($vv['AB_create_time'] >= $v['start'] && $vv['AB_create_time'] <= $v['end']) {
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
        //获取加盟商总数
        $where['AB_delete'] = [Status::AB_ID_NOT_DELETE];
        $andWhere = ['between', 'AB_create_time', $startTime, $endTime];
        $data['totalNum'] = static::getCountByAndWhere([], $andWhere);
        //获取加盟商总金额
        $andWhere = ['between', 'AB_create_time', $startTime, $endTime];
        $data['totalMoney'] = static::getSumByWhereAndWhere($where, $andWhere, 'AB_balance');
        return Functions::formatJson(1000, '成功', $data);
    }

    /**
     * 导出excel
     */
    public function exportExcel()
    {
        $where['AB_delete'] = Status::AB_ID_NOT_DELETE;
        $list = self::find()->where($where)->asArray()->all();
        $employeeInfo = Employee::getFormArray('','id','employee_name');
        foreach ($list as $key => $value) {
            $list[$key]['AB_principal'] = $value['AB_principal'] ? $employeeInfo[$value['AB_principal']] : '--';
            $list[$key]['AB_store_type'] = $value['AB_store_type'] ? Status::ABInfoTypeMap()[$value['AB_store_type']] : '--';
            $list[$key]['AB_collection_user'] = $value['AB_collection_user'] ? $employeeInfo[$value['AB_collection_user']] : '--';
        }
        Excel::export([
            'models' => $list,
            'fileName' => date('Ymd').'导出加盟商信息',
            'columns' => [
                'AB_number',
                'AB_name',
                'AB_code',
                'AB_principal',
                'AB_tel',
                'AB_address',
                'AB_start_time',
                'AB_end_time',
                'AB_store_type',
                'AB_collection',
                'AB_collection_user',
                'AB_balance',
                'AB_alliance_fee',
                'AB_store_code',
                'AB_store_mark',
            ], //没有头工作,因为头会得到标签的属性标签
            'headers' => [
                'AB_number' => '合同编号',
                'AB_name' => '店铺名称',
                'AB_code' => '店铺编码',
                'AB_principal' => '负责人',
                'AB_tel' => '联系电话',
                'AB_address' => '联系地址',
                'AB_start_time' => '开通时间',
                'AB_end_time' => '到期时间',
                'AB_store_type' => '店铺类型',
                'AB_collection' => '收款',
                'AB_collection_user' => '收款人',
                'AB_balance' => '余额',
                'AB_alliance_fee' => '加盟费',
                'AB_store_code' => '邮编',
                'AB_store_mark' => '备注',
            ],
        ]);
    }
}
