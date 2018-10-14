<?php
namespace backend\models;

use common\models\Status;
use Yii;
use yii\base\Exception;
use common\models\Functions;

class EmployeePost extends Common
{

    private $fieldArray = [
        "id",           //主键
        "post_name",   //职位名称
        "module_content",//权限管理
        "create_time",     //创建时间
        "status",     //是否可用
    ];

    public static function tableName()
    {
        return '{{%employee_post_info}}';
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
        $moduleList = Module::getByWhere(['status' => Status::MODULE_LIST_SUCCESS]);
        $moduleList = Functions::extractKey($moduleList,'id','module_title');
        foreach($list['data'] as $key => $value)
        {
            $list['data'][$key]['status'] = Status::employeePostStatusMap()[$value['status']];
            $list['data'][$key]['short_module_content'] = '--';
            $list['data'][$key]['module_content'] = '--';
            $roleName = '';
            if(!empty($value['module_content'])) {
                $rolePermissionsList = explode(',',$value['module_content']);
                foreach ($rolePermissionsList as $kk => $vv)
                {
                    if(isset($moduleList[$vv])) {
                        $roleName .= $moduleList[$vv].',';
                    } else {
                        continue;
                    }
                }
                if($value['module_content'] == '-1') {
                    $roleName = '最高权限';
                }
                $list['data'][$key]['short_module_content'] = Functions::chinese_str_cut(rtrim($roleName,','),100);
                $list['data'][$key]['module_content'] = rtrim($roleName,',');
            }

        }
        return $list;
    }

    public static function getFormList()
    {
        $where['business_id'] = Common::getBusinessId();
        $info = static::getByWhere($where,'id,post_name');
        $list = Functions::extractKey($info,'id','post_name');
        return $list;
    }

    /**
     * 获取当前加盟商下面的权限
     * @return bool
     */
    public function getPostByAb()
    {
        $moduleModel = new Module();
        $moduleInfo = $moduleModel->getOrderModule(Status::MODULE_TYPE_FRANCHISEE);

        $businessId = static::getBusinessId();
        $businessPostId = AB::getOneByWhere(['id' => $businessId],'AB_store_type');
        $businessPostList = ABPost::getOneByWhere(['id' => $businessPostId],'module_content');
        $businessPost = explode(',',$businessPostList['module_content']);

        if(!empty($businessId) && $businessId != 1){
            //属于加盟商,把加盟商响应的权限修改
            foreach ($moduleInfo as $key => $value){
                if(!in_array($value['id'],$businessPost)){
                    unset($moduleInfo[$key]);
                }
            }
            return $moduleInfo;
        }else{
            //属于总部
            $moduleInfo = $moduleModel->getOrderModule(Status::MODULE_TYPE_HEADQUARTERS);
            return $moduleInfo;
        }
    }

    /**
     * 启用禁用
     * @param string $id
     * @param string $status
     * @return array
     */
    public function tabStatus($id = '', $status = '')
    {
        $afterStatus = '';
        switch($status)
        {
            case '启用':
                $afterStatus = Status::EMPLOYEE_POST_SUCCESS;
                break;
            case '禁用':
                $afterStatus = Status::EMPLOYEE_POST_DISABLED;
                break;
        }
        $db = Yii::$app->db;
        $trans = $db->beginTransaction();
        $flag = self::updateDataWithLog(['status' => $afterStatus],["id" => $id]);
        if($flag === false) {
            $trans->rollBack();
            return Functions::formatJson(1001,$status.'失败');
        } else {
            $trans->commit();
            return Functions::formatJson(1000,$status.'成功');
        }
    }

    /**
     * 获取需要编辑的信息
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getEditInfo()
    {
        $id = intval(Yii::$app->request->post('id'));
        $info = self::getOneInfoById($id);
        $info['module_content'] = explode(',',$info['module_content']);
        return $info;
    }

    /**
     * 角色添加编辑
     * @return array
     */
    public function addEdit()
    {
        $data = Yii::$app->request->post();
        if(empty($data['module_content'])) {
            return Functions::formatJson(1001,'角色内容不能为空');
        }
        if(empty($data['post_name'])) {
            return Functions::formatJson(1002,'角色名不能为空');
        }
        $addEditInfo['post_name'] = trim($data['post_name']);
        $addEditInfo['module_content'] = isset($data['module_content']) ? implode(',',$data['module_content']) : '';
        $addEditInfo['type'] = (isset($data['post_type']) && !empty($data['post_type'])) ? intval($data['post_type']) : '';

        $db = Yii::$app->db;
        $trans = $db->beginTransaction();
        if(isset($data['id']) && !empty($data['id']) ) {
            //编辑操作
            $id = intval($data['id']);
            $where['id'] = $id;
            $addEditInfo['update_time'] = date("Y-m-d H:i:s");
            $flag = $this->updateDataWithLog($addEditInfo,$where);
        } else {
            //添加操作
            $addEditInfo['create_time'] = $addEditInfo['update_time'] = date("Y-m-d H:i:s");
            $addEditInfo['business_id'] = static::getBusinessId();
            $flag = $this->insertDataWithLog($addEditInfo);
        }
        if($flag == false) {
            $trans->rollBack();
            return Functions::formatJson(2001,'操作失败');
        }
        $trans->commit();
        return Functions::formatJson(1000,'操作成功');
    }


    /**
     * 选择员工职位
     * @return array
     */
    public function changePost()
    {
        $postId = Yii::$app->request->post('postId');
        if(!$postId) {
            return Functions::formatJson(2001,'请选择职位');
        }
        $employeeInfo = self::getOneInfoById($postId);

        $typeInfo = self::getInfoByField($postId,'type');

        if(!$employeeInfo) {
            return Functions::formatJson(2002,'未查询到职位信息');
        }

        if($typeInfo == Status::EMPLOYEE_POST_TYPE_SHEYING) {
            return Functions::formatJson(1000,'',true);
        } else {
            return Functions::formatJson(1000,'',false);
        }
    }
}
