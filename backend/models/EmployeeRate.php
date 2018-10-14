<?php
namespace backend\models;

use common\models\Status;
use Yii;
use yii\base\Exception;
use common\models\Functions;

class EmployeeRate extends Common
{

    private $fieldArray = [
        "id",           //主键
        "business_id",  //商户ID
        "employee_id",  //员工ID
        "rate_money",   //提成金额
        "rate_type",    //提成类型
        "create_time",  //创建时间
        "mark",         //备注
    ];

    public static function tableName()
    {
        return '{{%ab_employee_rate_info}}';
    }

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
            [['employee_id','rate_money','rate_type'], 'required','message' => '{attribute}不能为空','on' => 'add'],
            [['rate_money'], 'integer','message' => '{attribute}格式不对','on' => 'add'],

            #编辑
            [['employee_id','rate_money','rate_type'], 'required','message' => '{attribute}不能为空','on' => 'add'],
            [['rate_money'], 'integer','message' => '{attribute}格式不对','on' => 'add'],
        ];
    }

    /**
     * 设置属性名称
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'employee_id' => '员工',
            'rate_money' => '提成金额',
            'rate_type' => '提成类型',
            'create_time' => '创建时间',
            'mark' => '备注',
        ];
    }

    /**
     * 设置场景
     * @return array
     */
    public function scenarios()
    {
        $newScenarios = [
            'add' => ['employee_id','rate_money','rate_type','mark'],
            'edit' => ['employee_id','rate_money','rate_type','mark'],
        ];
        return array_merge(parent::scenarios(), $newScenarios);
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
        if(!empty($search)){
            $employeeName = $search['employeeName'];
            $employeeIdInfo = Employee::getFormArray(['employee_name' => $employeeName],'id','id');
            $rateType = $search['rateType'];
            $startTime = $search['startTime'];
            $endTime = $search['endTime'];
            if($employeeName)
            {
                $where['employee_id'] = $employeeIdInfo;
            }
            if($rateType)
            {
                $where['rate_type'] = $rateType;
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
        $employeeInfo = Employee::getFormArray(['status' => Status::EMPLOYEE_STATUS_ACTIVE,'alliance_business_id' => Common::getBusinessId()],'id','employee_name');
        foreach($list['data'] as $key => $value)
        {
            $list['data'][$key]['create_time'] = date('Y-m-d',strtotime($value['create_time'])).'</br>'.date('H:i:s',strtotime($value['create_time']));
            $list['data'][$key]['employee_id'] = $employeeInfo[$value['employee_id']];
            $list['data'][$key]['rate_type'] = Status::employeeRateTypeMap()[$value['rate_type']];
        }
        return $list;
    }


    /**
     * 编辑添加操作
     * @return array
     */
    public function addEdit()
    {
        $trans = Yii::$app->db->beginTransaction();
        $post = Yii::$app->request->post('EmployeeRate');

        try {
            if (!$this->validate()) {
                throw new Exception('数据有误');
            }

            if(!$post['rate_type']) {
                throw new Exception('请选择类型');
            }

            $id = intval($post['id']);
            $data = [];
            if (!empty($id)) {
                //更新提成
                $data = array_merge($data, $this->getSaveData('edit', $post));
                $data['update_time'] = date('Y-m-d H:i:s');
                $res = static::updateDataWithLog($data, ['id' => $id]);
                if ($res === false) {
                    throw new Exception('更新失败');
                }
            } else {
                if(!$post['employee_id']) {
                    throw new Exception('请选择会员');
                }
                $data = array_merge($data, $this->getSaveData('add', $post));
                //添加提成
                $data['business_id'] = Common::getBusinessId();
                $data['create_time'] = date('Y-m-d H:i:s');
                $res = static::insertDataWithLog($data);
                if ($res === false) {
                    throw new Exception('添加失败');
                }
            }
            $trans->commit();
            return Functions::formatJson(1000, '操作成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(1001, $e->getMessage());
        }
    }
}
