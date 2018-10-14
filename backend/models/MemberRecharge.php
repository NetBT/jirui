<?php
namespace backend\models;

use common\models\Status;
use Yii;
use yii\base\Exception;
use common\models\Functions;

class MemberRecharge extends Common
{

    private $fieldArray = [
        "id",           //主键
        "member_id",    //商户ID
        "money",        //姓名
        "status",       //性别
        "create_time",  //微信
        "type",         //联系电话
    ];

    public static function tableName()
    {
        return '{{%ab_member_recharge_info}}';
    }


    /**
     * 获取字段
     * @return array
     */
    private function _getFields() {
        return $this->fieldArray;
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
            if($name)
            {
                $where['name'] = $name;
            }
            if($tel)
            {
                $where['tel'] = $tel;
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
        foreach($list['data'] as $key => $value)
        {
            $list['data'][$key]['create_time'] = date('Y-m-d',strtotime($value['create_time'])).'</br>'.date('H:i:s',strtotime($value['create_time']));
            $list['data'][$key]['sex'] = Status::sexyMap()[$value['sex']];
            $list['data'][$key]['operate_id'] = $employeeList[$value['operate_id']];
        }
        return $list;
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
                throw new Exception('校验失败');
            }
            $post = Yii::$app->request->post('Member');
            $id = intval($post['id']);
            $data = [];
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
