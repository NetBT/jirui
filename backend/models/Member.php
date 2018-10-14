<?php
namespace backend\models;

use common\models\Status;
use moonland\phpexcel\Excel;
use Yii;
use yii\base\Exception;
use common\models\Functions;

class Member extends Common
{

    private $fieldArray = [
        "id",           //主键
        "business_id",  //商户ID
        "name",         //姓名
        "sex",          //性别
        "wechat",       //微信
        "tel",          //联系电话
        "valid_money",  //余额
        "integral",     //积分
        "total_consume",//总计消费
        "operate_id",   //操作者
        "create_time",  //创建时间
        "source",       //来源
        "age",          //年龄
        "referrer_id",  //推荐人
        "spare_tel",    //备用电话
    ];

    public static function tableName()
    {
        return '{{%ab_member_info}}';
    }

    public $business_name;

    /**
     * 获取字段
     * @return array
     */
    private function _getFields() {
        return $this->fieldArray;
    }

    /**
     * 验证规则
     */
    public function rules()
    {
        return [
            #添加
            [['name','sex','tel','wechat','email','address','age','parents_name','parents_baby_link','spare_tel'], 'required','message' => '{attribute}不能为空','on' => 'add'],
            [['tel','age','QQ'], 'integer','message' => '{attribute}格式不对','on' => 'add'],
            ['email', 'verifyMail','message' => '{attribute}格式不对','on' => 'add'],
            [['tel','spare_tel'], 'string', 'min' => 11, 'max' => 11,"tooLong"=>"手机号有误", "tooShort"=>"手机号有误",'on' => 'add'],
            ['QQ', 'string', 'min' => 6, 'max' => 12,"tooLong"=>"QQ有误", "tooShort"=>"QQ有误",'on' => 'add'],
            ['age', 'string', 'min' => 1, 'max' => 2,"tooLong"=>"年龄有误", "tooShort"=>"年龄有误",'on' => 'add'],
            ['address', 'string','min' => 0,'max' => 40,"tooLong"=>"限制40字", "tooShort"=>"输入地址",'on' => 'add'],
            ['tel', 'validTelUnique','message' => '{attribute}已存在','on' => 'add'],
            ['spare_tel', 'validSpareTelUnique','message' => '{attribute}已存在','on' => 'add'],

            #编辑
            [['name','sex','tel','wechat','email','address','age','parents_name','parents_baby_link','spare_tel'], 'required','message' => '{attribute}不能为空','on' => 'edit'],
            [['tel','age','QQ'], 'integer','message' => '{attribute}格式不对','on' => 'edit'],
            ['email', 'verifyMail','message' => '{attribute}格式不对','on' => 'edit'],
            [['tel','spare_tel'], 'string', 'min' => 11, 'max' => 11,"tooLong"=>"手机号有误", "tooShort"=>"手机号有误",'on' => 'edit'],
            ['QQ', 'string', 'min' => 6, 'max' => 12,"tooLong"=>"QQ有误", "tooShort"=>"QQ有误",'on' => 'edit'],
            ['age', 'string', 'min' => 1, 'max' => 2,"tooLong"=>"年龄有误", "tooShort"=>"年龄有误",'on' => 'edit'],
            ['address', 'string','min' => 0,'max' => 40,"tooLong"=>"限制40字", "tooShort"=>"输入地址",'on' => 'edit'],
            ['tel', 'validTelUnique','message' => '{attribute}已存在','on' => 'edit'],
            ['spare_tel', 'validSpareTelUnique','message' => '{attribute}已存在','on' => 'edit'],
        ];
    }


    public function verifyMail($attribute) {
            //$regex = "/^[A-Za-zd]+([-_.][A-Za-zd]+)*@([A-Za-zd]+[-.])+[A-Za-zd]{2,5}$/";
            $regex = "/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*\.[a-zA-Z0-9]{2,6}$/";
            if (!preg_match($regex, $this->email)) {
                $this->addError($attribute, '邮箱格式不正确');
            }

    }


    /**
     * 设置属性名称
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'name' => '宝宝姓名',
            'sex' => '性别',
            'wechat' => '微信',
            'tel' => '电话',
            'age' => '年龄',
            'address' => '地址',
            'mark' => '备注',
            'QQ' => 'QQ',
            'email' => '邮箱',
            'referrer_id' => '推荐人',
            'royalty_id' => '提成员工',
            'number' => '编号',
            'source' => '顾客来源',
            'parents_name' => '家长姓名',
            'parents_baby_link' => '与宝宝关系',
            'spare_tel' => '备用电话',
            'birthday' => '宝宝生日',
        ];
    }

    /**
     * 设置场景
     * @return array
     */
    public function scenarios()
    {
        $newScenarios = [
            'add' => ['name','sex','tel','wechat','QQ','email','address','age','mark','referrer_id','royalty_id','number','source','parents_name','parents_baby_link','spare_tel','birthday'],
            'edit' => ['id','name','sex','tel','wechat','address','QQ','email','age','mark','referrer_id','royalty_id','source','parents_name','parents_baby_link','spare_tel','birthday'],
        ];
        return array_merge(parent::scenarios(), $newScenarios);
    }

    /**
     * 手机号是否存在
     * @param $attribute
     * @param $params
     */
    public function validTelUnique($attribute, $params)
    {
        //判断是否有id
        $id = intval($this->id);
        if(isset($id) && !empty($id)) {
            $andWhere = ['and',['<>','id',$id],['or',['tel' => $this->tel],['spare_tel' => $this->tel]]];
            $telInfo = static::getOneByWhereAndWhere('',$andWhere,'tel');
        } else {
            $andWhere = ['or',['tel' => $this->tel],['spare_tel' => $this->tel]];
            $telInfo = static::getOneByWhereAndWhere('',$andWhere,'tel');
        }
        if($telInfo)
        {
            $this->addError($attribute, '已存在.');
        }
    }

    /**
     * 备用手机号是否存在
     * @param $attribute
     * @param $params
     */
    public function validSpareTelUnique($attribute, $params)
    {
        //判断是否有id
        $id = intval($this->id);
        if(isset($id) && !empty($id)) {
            $andWhere = ['and',['<>','id',$id],['or',['tel' => $this->tel],['spare_tel' => $this->tel]]];
            $telInfo = static::getOneByWhereAndWhere('',$andWhere,'tel');
        } else {
            $andWhere = ['or',['tel' => $this->tel],['spare_tel' => $this->tel]];
            $telInfo = static::getOneByWhereAndWhere('',$andWhere,'tel');
        }
        if($telInfo)
        {
            $this->addError($attribute, '已存在.');
        }
    }

    public function getListData()
    {
        $returnData = [
            "draw" => intval(Yii::$app->request->post('draw')),
            "recordsTotal" => 0,
            "recordsFiltered" => 0,
            "data" => null
        ];
        //搜索条件
        $searchWhere  = $this->getSearch(Yii::$app->request->post('extra_search'));//自定义搜索条件

        //得到文章的总数（但是还没有从数据库取数据）
        if(isset($searchWhere['andWhere'])){
            $count = self::getCountByAndWhere($searchWhere['where'], $searchWhere['andWhere']);
        } else {
            $count = self::getCountByWhere($searchWhere);
        }
        $returnData["recordsTotal"] = $returnData['recordsFiltered'] = intval($count);

        //设置分页
        $this->setPagination();

        $selectField = "";
        $fields = $this->_getFields();
        foreach($fields as $key => $value)
        {
            $selectField .= ",".$value;
        }
        $selectField = ltrim($selectField,',');
        //排序 order
        $orderSql = 'id ASC';
        if(isset($searchWhere['andWhere'])){
            $returnData['data'] = static::getByAndWhere($searchWhere['where'],$searchWhere['andWhere'], $selectField, $orderSql, $this->_Pagination['offset'], $this->_Pagination['limit']);
        } else {
            $returnData['data'] = static::getByWhere($searchWhere, $selectField, $orderSql, $this->_Pagination['offset'], $this->_Pagination['limit']);
        }
        return $returnData;
    }

    /**
     * 自定义参数的搜索  搜索,搜索也分每一列
     * 这里要根据业务逻辑进行修改
     * @param $search
     * @return string
     */
    public function getSearch ($search = [])
    {
        $where = [];
        $andWhere = [];
        $where['business_id'] = Common::getBusinessId();
        $where['is_delete'] = Status::MEMBER_NOT_DELETE;
        if(!empty($search)){
            $name = $search['name'];
            $tel = $search['tel'];
            $startTime = isset($search['startTime']) ? $search['startTime'] : '';
            $endTime = isset($search['endTime']) ? $search['endTime'] : '';
            if($name)
            {
                $where['name'] = $name;
            }
            if($tel)
            {
                $where['tel'] = $tel;
            }

            if(!empty($startTime) && empty($endTime))
            {
                $andWhere = ['>','create_time',$startTime. ' 00:00:00'];
            } elseif(empty($startTime) && !empty($endTime)) {
                $andWhere = ['<','create_time',$endTime. ' 23:59:59'];
            } elseif (!empty($startTime) && !empty($endTime)) {
                $andWhere = ['between','create_time',$startTime. ' 00:00:00', $endTime. ' 23:59:59'];
            }
        }
        return [
            'where' => $where,
            'andWhere' => $andWhere
        ];
    }

    /**
     * 进行初始化数据处理
     * @param array $list
     * @return array
     */
    public function handelInit($list = [])
    {
        $employeeList = Employee::getFormArray('','id','employee_name');
        $memberInfo = self::getFormArray('','id','name');
        $abInfo = AB::getFormArray('','id','AB_name');
        foreach($list['data'] as $key => $value)
        {
            $list['data'][$key]['create_time'] = date('Y-m-d',strtotime($value['create_time'])).'</br>'.date('H:i:s',strtotime($value['create_time']));
            $list['data'][$key]['sex'] = Status::sexyMap()[$value['sex']];
            $list['data'][$key]['operate_id'] = $value['operate_id'] ? $employeeList[$value['operate_id']] : '--';
            $list['data'][$key]['source'] = $value['source'] ? Status::memberSourceMap()[$value['source']] : '--';

            $list['data'][$key]['referrer_id'] = $value['referrer_id'] ? $memberInfo[$value['referrer_id']] : '--';
            $list['data'][$key]['business_id'] = $value['business_id'] ? $abInfo[$value['business_id']] : '--';
            $list['data'][$key]['valid_money'] = $value['valid_money'] ? $value['valid_money'] : 0;
            $list['data'][$key]['integral'] = $value['integral'] ? $value['integral'] : 0;
            $list['data'][$key]['total_consume'] = $value['total_consume'] ? $value['total_consume'] : 0;
        }
        return $list;
    }

    /**
     * 获取会员编号
     * @return string
     */
    public static function getMemberNum()
    {
        $num = date('YmdHis').'AB'.Common::getBusinessId();
        if(static::getOneByWhere(['number' => $num])){
                self::getMemberNum();
        }
        return $num;
    }

    /**
     * 获取推荐会员
     * @param string $id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getReferrerInfo($id = '')
    {
        $result = [];
        if(empty($id)){
            return $result;
        } else {
            $result = static::getByWhere(['referrer_id' => $id]);
        }
        return $result;
    }

    /**
     * 消息回复
     * @return array
     */
    public function addEdit()
    {
        $trans = Yii::$app->db->beginTransaction();
        try {
            if (!$this->validate()) {
                $error = '';
                foreach ($this->errors as $key => $value)
                {
//                    $error = $this->attributeLabels()[$key].' '.$value[0];
                    $error = $value[0];
                    break;
                }
                throw new Exception($error);
            }
            $post = Yii::$app->request->post('Member');
            $id = intval($post['id']);
            $data = [];
            $currentMember = [];
            if (!empty($id)) {
                $data = array_merge($data, $this->getSaveData('edit', $post));
                $data['update_time'] = date('Y-m-d H:i:s');
                $res = static::updateDataWithLog($data, ['id' => $id]);
                if ($res === false) {
                    throw new Exception('更新失败');
                }
            } else {
                $data = array_merge($data, $this->getSaveData('add', $post));
                $data['create_time'] = $data['update_time'] = date('Y-m-d H:i:s');
                $data['operate_id'] = Yii::$app->user->identity->id;
                $data['business_id'] = Common::getBusinessId();
                //如果是老顾客推荐 需要添加推荐返利表
                $res = static::insertDataWithLog($data);
                if ($res === false) {
                    throw new Exception('添加失败');
                }

                $currentMember = self::getOneInfoById($res);
                $currentMember['sex'] = Status::sexyMap()[$currentMember['sex']];
                foreach ($currentMember as $key => $value) {
                    $currentMember[$key] = (isset($value) && !empty($value)) ? $value : '--';
                }

                if($data['source'] == Status::MEMBER_SOURCE_LGKTJ)
                {
                    $rebateMoney = Functions::getABCommonByKey('recommend_rebate_money');
                    //添加推荐返利日志
                    $recommendRebate['business_id'] = Common::getBusinessId();
                    $recommendRebate['recommend_id'] = $data['referrer_id'];
                    $recommendRebate['target_id'] = $res;
                    $recommendRebate['rebate_money'] = $rebateMoney;
                    $recommendRebate['create_time'] = date('Y-m-d H:i:s');
                    $recommendRebate['mark'] = '推荐返利';
                    $recommendRebateModel = new RecommendRebate();
                    $resRecommendRebate = $recommendRebateModel->insertDataWithLog($recommendRebate);
                    if ($resRecommendRebate === false) {
                        throw new Exception('添加失败');
                    }

                    //更新用户表
                    $memberInfo = Member::getOneInfoById($data['referrer_id']);
                    $updateMember['valid_money'] = $rebateMoney + $memberInfo['valid_money'];
                    $updateMember['update_time'] = date('Y-m-d H:i:s');
                    $resUpdate = self::updateDataWithLog($updateMember,['id' => $data['referrer_id']]);
                    if ($resUpdate === false) {
                        throw new Exception('添加失败');
                    }
                }

            }
            $trans->commit();
            return Functions::formatJson(1000, '操作成功',$currentMember);
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(1001, $e->getMessage());
        }
    }

    /**
     * 物理删除员工操作
     * @return array
     */
    public function doDelete()
    {
        $trans = Yii::$app->db->beginTransaction();
        try {
            $post = Yii::$app->request->post();
            $id = intval($post['id']);
            $data = [];
            if (!empty($id)) {
                $data['update_time'] = date('Y-m-d H:i:s');
                $data['is_delete'] = Status::MEMBER_IS_DELETE;
                $res = static::updateDataWithLog($data, ['id' => $id]);
                if ($res === false) {
                    throw new Exception('删除失败');
                }
            } else {
                throw new Exception('请选择要删除的会员');
            }
            $trans->commit();
            return Functions::formatJson(1000, '删除成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(1001, $e->getMessage());
        }
    }

    /**
     * 会员充值
     * @return array
     */
    public function recharge()
    {
        $trans = Yii::$app->db->beginTransaction();
        try {
            $post = Yii::$app->request->post();
            $id = intval($post['id']);
            $oldMemberInfo = static::getOneInfoById($id,['valid_money','integral']);
            $memberData = [];
            $rechargeData = [];
            if (!empty($id)) {
                //充值表 添加
                $rechargeData['member_id'] = $id;
                $rechargeData['money'] = trim($post['money']);
                $rechargeData['send_integral'] = trim($post['integral']);
                $rechargeData['create_time'] = date('Y-m-d H:i:s');
                $rechargeData['business_id'] = Common::getBusinessId();
                $memberRechargeModel = new MemberRecharge();
                $res = $memberRechargeModel->insertDataWithLog($rechargeData);
                if ($res === false) {
                    throw new Exception('充值失败');
                }
                //用户表 更新
                $memberData['integral'] = $post['integral'] + $oldMemberInfo['integral'];
                $memberData['valid_money'] = $post['money'] + $oldMemberInfo['valid_money'];
                $memberData['update_time'] = date('Y-m-d H:i:s');
                $res = static::updateDataWithLog($memberData,['id' => $id]);
                if ($res === false) {
                    throw new Exception('充值失败');
                }
                //帐变表
            } else {
                throw new Exception('请选择要充值的会员');
            }
            $trans->commit();
            return Functions::formatJson(1000, '充值成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(1001, $e->getMessage());
        }
    }

    /**
     * 积分操作
     * @return array
     */
    public function doIntegral()
    {
        $trans = Yii::$app->db->beginTransaction();
        try {
            $post = Yii::$app->request->post();
            $id = intval($post['id']);
            $data = [];
            if (!empty($id)) {
                //更新
                $data['integral'] = $post['new_integral'];
                $data['update_time'] = date('Y-m-d H:i:s');
                $res = static::updateDataWithLog($data,['id' => $id]);
                if ($res === false) {
                    throw new Exception('操作失败');
                }
            } else {
                throw new Exception('请选择要操作的会员');
            }
            $trans->commit();
            return Functions::formatJson(1000, '操作成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(1001, $e->getMessage());
        }
    }

    public static function getTotalMember() {
        $where['business_id'] = static::getBusinessId();
        return static::getCountByWhere($where);
    }

    /**
     * 导出excel
     */
    public function exportExcel()
    {
        $list = [];
        $where['business_id'] = Common::getBusinessId();
        $where['is_delete'] = Status::MEMBER_NOT_DELETE;
        $list['data'] = self::find()->where($where)->asArray()->all();
        $list = $this->handelInit($list);
        $list = $list['data'];
        Excel::export([
            'models' => $list,
            'fileName' => date('Ymd').'导出会员信息',
            'columns' => [
                'business_id',
                'name',
                'number',
                'sex',
                'tel',
                'valid_money',
                'integral',
                'total_consume',
                'wechat',
                'age',
                'address',
                'QQ',
                'email',
                'source',
                'referrer_id',
                'mark',
            ], //没有头工作,因为头会得到标签的属性标签
            'headers' => [
                'business_id' => '商户',
                'name' => '姓名',
                'number' => '编号',
                'sex' => '性别',
                'tel' => '电话',
                "valid_money" => '余额',
                "integral" => '积分',
                "total_consume" => '总计消费',
                'wechat' => '微信',
                'age' => '年龄',
                'address' => '地址',
                'QQ' => 'QQ',
                'email' => '邮箱',
                'source' => '顾客来源',
                'referrer_id' => '推荐人',
                'mark' => '备注',
            ],
        ]);
    }
    /**
     * 下单量统计
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
    public function echartsOrderQuantity() {
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
    public function echartsVisited() {
        $post = Yii::$app->request->post();
        $startTime = !empty($post["start"]) ? date("Y-m-d 00:00:00", strtotime($post["start"])) : date("Y-m-d 00:00:00");
        $endTime = !empty($post["end"]) ? date("Y-m-d 23:59:59", strtotime($post["end"])) : date("Y-m-d 23:59:59");

        //获取下单量
        $where['source'] = Yii::$app->request->get('type');
        $andWhere[] = ['between', 'create_time', $startTime, $endTime];
        $list = static::getByAndWheres($where, $andWhere, ['id','create_time']);
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


    public function getMemberInfoByWhere($where = [])
    {
        $info = self::getOneByWhere($where);
        if(!$info) {
            return Functions::formatJson(2001, '未有此会员，请点击【创建会员】按钮');
        }
        $info['sex'] = Status::sexyMap()[$info['sex']];
        foreach ($info as $key => $value) {
            $info[$key] = (isset($value) && !empty($value)) ? $value : '--';
        }

        return Functions::formatJson(1000,'',$info);
    }

}
