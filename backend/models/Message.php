<?php
namespace backend\models;

use common\models\Status;
use Yii;
use yii\base\Exception;
use common\models\Functions;

class Message extends Common
{

    private $fieldArray = [
        "id",           //主键
        "business_id",  //商户ID
        "content",      //消息内容
        "type",         //消息类型
        "status",       //消息状态
        "create_time",  //发送时间
        "reply_content",//回复内容
        "reply_time",   //回复时间
        "reply_id",     //回复者
        "reply_status", //回复状态
        "money", //
        "pay_type", //
        "postpone_time", //
        "postpone_type", //
    ];

    public static function tableName()
    {
        return '{{%message}}';
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
            #登录
            ['reply_content', 'required','message' => '{attribute}不能为空','on' => 'replyMessage'],
            [['type', 'content'], 'required','message' => '{attribute}不能为空','on' => 'sendMessage'],
        ];
    }

    /**
     * 设置属性名称
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'business_name' => '发信人',
            'content' => '内容',
            'reply_content' => '回复',
            'type' => '类型'
        ];
    }

    /**
     * 设置场景
     * @return array
     */
    public function scenarios()
    {
        $newScenarios = [
            'replyMessage' => ['business_name','reply_content','reply_id'],
            'sendMessage' => ['content','type'],
        ];
        return array_merge(parent::scenarios(), $newScenarios);
    }

    /**
     * 获取总部消息列表
     * @return array
     */
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
        $orderSql = 'reply_status asc, id desc';
        if(isset($searchWhere['andWhere'])){
            $returnData['data'] = static::getByAndWhere($searchWhere['where'],$searchWhere['andWhere'], $selectField, $orderSql, $this->_Pagination['offset'], $this->_Pagination['limit']);
        } else {
            $returnData['data'] = static::getByWhere($searchWhere, $selectField, $orderSql, $this->_Pagination['offset'], $this->_Pagination['limit']);
        }
        return $returnData;
    }

    /**
     * 获取加盟商消息列表
     * @return array
     */
    public function getABListData()
    {
        $returnData = [
            "draw" => intval(Yii::$app->request->post('draw')),
            "recordsTotal" => 0,
            "recordsFiltered" => 0,
            "data" => null
        ];
        $post = Yii::$app->request->post('extra_search');
        $post['ab_name'] = static::getBusinessId();
        //搜索条件
        $searchWhere  = $this->getSearch($post);//自定义搜索条件
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
        $orderSql = 'reply_status asc, id desc';
        if(isset($searchWhere['andWhere'])){
            $list = static::getByAndWhere($searchWhere['where'],$searchWhere['andWhere'], $selectField, $orderSql, $this->_Pagination['offset'], $this->_Pagination['limit']);
        } else {
            $list = static::getByWhere($searchWhere, $selectField, $orderSql, $this->_Pagination['offset'], $this->_Pagination['limit']);
        }
        $typeMap = Status::messageTypeCommonMap();
        $statusMap = Status::messageStatusMap();
        foreach ($list as $k => $v) {
            $list[$k]['status'] = $statusMap[$v['status']];
            $list[$k]['type'] = $typeMap[$v['type']];
            $list[$k]['short_content'] = '--';
            $list[$k]['short_reply_content'] = '--';
            if(!empty($v['content'])) {
                $list[$k]['short_content'] = Functions::chinese_str_cut(rtrim($v['content'],','),50);
            }
            if(!empty($v['reply_content'])) {
                $list[$k]['short_reply_content'] = Functions::chinese_str_cut(rtrim($v['reply_content'],','),50);
            }
            foreach ($v as $kk => $vv) {
                if ($vv === null) {
                    $list[$k][$kk] = '';
                }
            }
            $list[$k]['recharge_info'] = !empty($v['money']) ? Status::AbRechargePayWayMap()[$v['pay_type']].'充值'.$v['money'] : '--';
            $list[$k]['postpone_info'] = (!empty($v['money']) && !empty($v['postpone_time'])) ? '延期'.$v['postpone_time'].Status::AbPostponeTimeUnitMap()[$v['postpone_type']].'<br />'.Status::HeadRefundMoneyTypeMap()[$v['pay_type']].'充值'.$v['money'] : '--';

        }
        $returnData['data'] = $list;
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
        if(!empty($search)){
            $abName = $search['ab_name'];
            $type = $search['type'];
            $status = $search['status'];
            if($abName)
            {
                $where['business_id'] = $abName;
            }
            if($type)
            {
                $where['type'] = $type;
            }
            if($status)
            {
                $where['status'] = $status;
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
        $abInfo = AB::getByWhere('','id,AB_name');
        $abList = Functions::extractKey($abInfo,'id','AB_name');
        $employeeList = Employee::getFormArray('','id','employee_name');
        foreach($list['data'] as $key => $value)
        {
            $list['data'][$key]['create_time'] = date('Y-m-d',strtotime($value['create_time'])).'</br>'.date('H:i:s',strtotime($value['create_time']));
            $list['data'][$key]['reply_time'] = $value['reply_time'] === '0000-00-00 00:00:00' ? '--' : str_replace(' ', '<br />', $value['reply_time']);
            $list['data'][$key]['business_name'] = $abList[$value['business_id']];
            $list['data'][$key]['reply_name'] = !empty($value['reply_id']) ? $employeeList[$value['reply_id']] : '--';
            $list['data'][$key]['status'] = Status::messageStatusMap()[$value['status']];
            $list['data'][$key]['type'] = Status::messageTypeCommonMap()[$value['type']];
            $list['data'][$key]['short_content'] = '--';
            $list['data'][$key]['short_reply_content'] = '--';
            if(!empty($value['content'])) {
                $list['data'][$key]['short_content'] = Functions::chinese_str_cut(rtrim($value['content'],','),50);
            }
            if(!empty($value['reply_content'])) {
                $list['data'][$key]['short_reply_content'] = Functions::chinese_str_cut(rtrim($value['reply_content'],','),50);
            }

            $list['data'][$key]['recharge_info'] = !empty($value['money']) ? Status::AbRechargePayWayMap()[$value['pay_type']].'充值'.$value['money'] : '--';
            $list['data'][$key]['postpone_info'] = (!empty($value['money']) && !empty($value['postpone_time'])) ? '延期'.$value['postpone_time'].Status::AbPostponeTimeUnitMap()[$value['postpone_type']].'<br />'.Status::HeadRefundMoneyTypeMap()[$value['pay_type']].'充值'.$value['money'] : '--';
        }
        return $list;
    }

    /**
     * 消息回复
     * @return array
     */
    public function reply()
    {
        $trans = Yii::$app->db->beginTransaction();
        try {
            if (!$this->validate()) {
                throw new Exception('校验失败');
            }
            $post = Yii::$app->request->post('Message');
            $id = intval($post['id']);
            $data = [];
            if (!empty($id)) {
                $data = array_merge($data, $this->getSaveData('replyMessage', $post));
                $data['reply_time'] = date('Y-m-d H:i:s');
                $data['reply_id'] = Yii::$app->user->identity->id;
                $data['status'] = Status::MESSAGE_STATUS_YHF;
                $res = static::updateDataWithLog($data, ['id' => $id]);
                if ($res === false) {
                    throw new Exception('数据库更新失败');
                }
                $trans->commit();
                return Functions::formatJson(1000, '回复成功');
            } else {
                throw new Exception('请指定回复商户');
            }
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(1001, $e->getMessage());
        }
    }

    /**
     * 消息回复
     * @return array
     */
    public function send()
    {
        $trans = Yii::$app->db->beginTransaction();
        try {
            if (!$this->validate()) {
                throw new Exception('校验失败');
            }
            $post = Yii::$app->request->post('Message');
            $data = [];
            $data = array_merge($data, $this->getSaveData('sendMessage', $post));
            $data['create_time'] = date("Y-m-d H:i:s");
            $data['status'] = Status::MESSAGE_STATUS_WHF;
            $data['business_id'] = self::getBusinessId();
            $res = $this->insertDataWithLog($data);
            if ($res === false) {
                throw new Exception('消息发送失败');
            }
            $trans->commit();
            return Functions::formatJson(1000, '发送成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(2000, $e->getMessage());
        }
    }

    /**
     * 加盟商自己充值
     * @return array
     */
    public function rechargeBySelf()
    {
        $trans = Yii::$app->db->beginTransaction();
        try {
            $post = Yii::$app->request->post();
            $data['create_time'] = date("Y-m-d H:i:s");
            $data['status'] = Status::MESSAGE_STATUS_WHF;
            $data['business_id'] = self::getBusinessId();
            $data['type'] = Status::MESSAGE_TYPE_XTCZ;

            $data['money'] = trim($post['money']);
            $data['pay_type'] = intval($post['type']);
            $data['content'] = trim($post['mark']);
            $res = $this->insertDataWithLog($data);
            if ($res === false) {
                throw new Exception('操作失败');
            }
            $trans->commit();
            return Functions::formatJson(1000, '操作成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(2000, $e->getMessage());
        }
    }

    /**
     * 加盟商自己充值
     * @return array
     */
    public function postponeBySelf()
    {
        $trans = Yii::$app->db->beginTransaction();
        try {
            $post = Yii::$app->request->post();
//            var_dump($post);die;
            $data['create_time'] = date("Y-m-d H:i:s");
            $data['status'] = Status::MESSAGE_STATUS_WHF;
            $data['business_id'] = self::getBusinessId();
            $data['type'] = Status::MESSAGE_TYPE_YQSQ;

            $data['money'] = trim($post['money']);
            $data['pay_type'] = intval($post['type']);
            $data['content'] = trim($post['mark']);
            $data['postpone_time'] = trim($post['postpone_time']);
            $data['postpone_type'] = intval($post['postpone_type']);
            $res = $this->insertDataWithLog($data);
            if ($res === false) {
                throw new Exception('操作失败');
            }
            $trans->commit();
            return Functions::formatJson(1000, '操作成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(2000, $e->getMessage());
        }
    }
}
