<?php
namespace backend\models;

use common\models\Status;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Yii;
use yii\base\Exception;
use common\models\Functions;

class Resume extends Common
{
    private $fieldArray = [
        "id",           //序号
        "resume_title", //标题
        "name",         //姓名
        "create_time",  //创建时间
        "update_time",  //创建时间
        "is_default",
        "employee_id",
        "check_status",
    ];

    public static function tableName()
    {
        return '{{%resume_info}}';
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
            #添加编辑
            [['resume_title','name','tel','working_status','working_duration','expected_salary'], 'required','message' => '{attribute}不能为空','on' => 'addEdit'],
            [['QQ','tel','age'], 'integer','message' => '{attribute}格式不对','on' => 'addEdit'],
            ['email', 'email','message' => '{attribute}格式不对','on' => 'addEdit'],


        ];
    }

    /**
     * 设置属性名称
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'name' => '姓名',
            'tel' => '电话',
            'sex' => '性别',
            'age' => '年龄',
            'wechat' => '微信',
            'QQ' => 'QQ',
            'email' => '邮箱',
            'birthday' => '生日',
            'nation' => '民族',
            'province' => '省份',
            'school' => '学校',
            'address' => '地址',
            'degree' => '学历',
            'working_status' => '职位情况',
            'marriage' => '婚姻状况',
            'working_duration' => '工作年限',
            'expected_salary' => '期望薪资',
            'resume_title' => '标题',
        ];
    }

    /**
     * 设置场景
     * @return array
     */
    public function scenarios()
    {
        $newScenarios = [
            'addEdit' => [
                'name',
                'tel',
                'sex',
                'marriage',
                'birthday',
                'wechat',
                'age',
                'QQ',
                'nation',
                'email',
                'degree',
                'province',
                'school',
                'address',
                'working_status',
                'working_duration',
                'expected_salary',
                'resume_title',
            ],

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
//        if(Common::checkEmployeeType() == Status::MODULE_TYPE_HEADQUARTERS) {
//            $where['employee_id'] = '';
//        }
        if(Common::checkEmployeeType() == Status::MODULE_TYPE_GUEST) {
            $where['employee_id'] = Yii::$app->user->getId();
        }

        $where['is_delete'] = Status::RESUME_DELETE_NO;
        if(!empty($search)){
            $checkStatus = isset($search['checkStatus']) ? $search['checkStatus'] : '';
            if($checkStatus) {
                $where['check_status'] = $checkStatus;
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
        foreach($list['data'] as $key => $value)
        {
            $list['data'][$key]['create_time'] = date('Y-m-d',strtotime($value['create_time'])).'</br>'.date('H:i:s',strtotime($value['create_time']));
            $list['data'][$key]['update_time'] = date('Y-m-d',strtotime($value['update_time'])).'</br>'.date('H:i:s',strtotime($value['update_time']));
            $list['data'][$key]['is_default'] = $value['is_default'] ? Status::resumeDefaultMap()[$value['is_default']] : '--';
//            $list['data'][$key]['check_status'] = $value['check_status'] ? Status::resumeCheckStatusMap()[$value['check_status']] : '--';
            $list['data'][$key]['check_status_name'] = $value['check_status'] ? Status::resumeCheckStatusMap()[$value['check_status']] : '--';
        }
        return $list;
    }


    /**
     * 编辑
     */
    public function addEdit()
    {
        $trans = Yii::$app->db->beginTransaction();
        try {
            if (!$this->validate()) {
                throw new Exception(false);
            }
            $post = Yii::$app->request->post('Resume');
            $data = [];
            if (isset($post['id']) && !empty($post['id'])) {
                $data = array_merge($data, $this->getSaveData('addEdit', $post));
                $data['update_time'] = date("Y-m-d H:i:s");
                //判断系统参数简历审核状态
                $updateResumeStatus = Functions::getCommonByKey('update_resume_status');
                if($updateResumeStatus) {
                    if($updateResumeStatus != Status::RESUME_CHECK_STATUS_BCBB) {
                        $data['check_status'] = $updateResumeStatus;
                    }
                }
                $res = static::updateDataWithLog($data, ['id' => $post['id']]);
            } else {
                $data = array_merge($data, $this->getSaveData('addEdit', $post));
                $data['employee_id'] = Yii::$app->user->getId();
                $data['create_time'] =  $data['update_time'] = date("Y-m-d H:i:s");
                $data['is_default'] = Status::RESUME_DEFAULT_YES;
                //判断系统参数简历审核状态
                $newResumeStatus = Functions::getCommonByKey('new_resume_status');
                if($newResumeStatus) {
                    $data['check_status'] = $newResumeStatus;
                }
                //判断是否有简历，没有默认该简历为默认简历
                $resumeEmployeeInfo = self::getByWhere(['employee_id' => Yii::$app->user->getId(),'is_delete' => Status::RESUME_DELETE_NO]);
                if($resumeEmployeeInfo){
                    $data['is_default'] = Status::RESUME_DEFAULT_NO;
                }
                $res = static::insertDataWithLog($data);
            }

            if ($res === false) {
                throw new Exception(false);
            }
            $trans->commit();
            return Functions::formatJson(1000,'操作成功',$res);
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(1001,$e->getMessage());
        }
    }

    /**
     * 保存自我评价
     * @return array
     */
    public function saveAssessment()
    {
        $trans = Yii::$app->db->beginTransaction();
        try {
            $post = Yii::$app->request->post();
            $data = [];
            if (isset($post['id']) && !empty($post['id'])) {
                $data['update_time'] = date("Y-m-d H:i:s");
                $data['self_assessment'] = trim($post['content']);
                $res = static::updateDataWithLog($data, ['id' => $post['id']]);
            } else {
                throw new Exception('请先填写个人信息');
            }

            if ($res === false) {
                throw new Exception('操作失败');
            }
            $trans->commit();
            return Functions::formatJson(1000,'操作成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(1001,$e->getMessage());
        }
    }

    /**
     * 逻辑删除简历
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
                $data['is_delete'] = Status::RESUME_DELETE_YES;
                $res = static::updateDataWithLog($data, ['id' => $id]);
                if ($res === false) {
                    throw new Exception('删除失败');
                }
            } else {
                throw new Exception('请选择要删除的简历');
            }
            $trans->commit();
            return Functions::formatJson(1000, '删除成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(1001, $e->getMessage());
        }
    }

    /**
     * 默认/取消默认
     */
    public function updateDefault()
    {
        $post = Yii::$app->request->post();
        $id = intval($post['id']);
        $default = trim($post['defaultVal']);
        $data = [];
        $afterDefault = '';

        $db = Yii::$app->db;
        $trans = $db->beginTransaction();
        try {

            switch($default)
            {
                case '取消默认':
                    $afterDefault = Status::RESUME_DEFAULT_NO;
                    break;
                case '设为默认':
                    $afterDefault = Status::RESUME_DEFAULT_YES;
                    //先把当前用户所有的简历变为不是默认
                    $flag = self::updateData(['is_default' => Status::RESUME_DEFAULT_NO],["employee_id" => Yii::$app->user->getId()]);
                    if ($flag === false) {
                        throw new Exception($default.'失败');
                    }
                    //再把该简历设为默认简历
                    break;
            }
            $data['update_time'] = date('Y-m-d H:i:s');
            $data['is_default'] = $afterDefault;
            $res = self::updateDataWithLog($data,["id" => $id]);
            if($res === false)
            {
                throw new Exception($default.'失败');
            }
            $trans->commit();
            return Functions::formatJson(1000, $default.'成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(1001, $e->getMessage());
        }
    }

    /**
     * 简历详情
     * @param int $id
     * @return array|null|\yii\db\ActiveRecord
     */
    public function quoteInfo($id = 0)
    {
        $nationInfo = Nation::getFormArray('','id','name');
        $list = self::getOneInfoById($id);
        $list['expected_salary'] = $list['expected_salary'] ? Status::expectedSalaryLabelMap()[$list['expected_salary']] : '不限';
        $list['working_duration'] = $list['working_duration'] ? Status::workingDurationLabelMap()[$list['working_duration']] : '不限';
        $list['degree'] = $list['degree'] ? Status::degreeMap()[$list['degree']] : '不限';
        $list['sex'] = $list['sex'] ? Status::sexyMap()[$list['sex']] : '未知';
        $list['marriage'] = $list['marriage'] ? Status::marriageMap()[$list['marriage']] : '未知';
        $list['nation'] = $list['nation'] ? $nationInfo[$list['nation']] : '未知';
        $list['wechat'] = $list['wechat'] ? $list['wechat'] : '未知';
        $list['email'] = $list['email'] ? $list['email'] : '未知';
        $list['province'] = $list['province'] ? $list['province'] : '未知';
        $list['address'] = $list['address'] ? $list['address'] : '未知';
        $list['school'] = $list['school'] ? $list['school'] : '未知';
        $list['working_status'] = $list['working_status'] ? Status::workingStatusMap()[$list['working_status']] : '未知';
        return $list;
    }

    public function download()
    {
        //现将文件存入服务器
        $html =  '
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:w="urn:schemas-microsoft-com:office:word" xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<xml><w:WordDocument><w:View>Print</w:View></xml>
</head>';
        $post = Yii::$app->request->post();
        $content = $post['content'];
        $html .= '<body>'.$content.'</body></html>';
        $path = Yii::$app->basePath . '/web/uploads/download/';
        $dir = date("Ymd").'/';
        $file = date("YmdHis").'.doc';
        if (!file_exists($path . '/' . $dir)) {
            mkdir($path . '/' . $dir);
            chmod($path . '/' . $dir, 0777);
        }
        $title = $post['title'];
        $fileName = $path.$dir.$file;

        //删除旧图片
        if (file_exists($fileName)) {
            @unlink($fileName);
        }

        $fp = fopen($fileName,'wb');
                fwrite($fp,$html);
                fclose($fp);
                ob_flush();//每次执行前刷新缓存
                flush();
        chmod($fileName, 0777);

        $data['update_time'] = date('Y-m-d H:i:s');//投递时间
        $data['is_download'] = Status::RESUME_DEFAULT_YES;
        $sendResumeModel = new SendResume();
        $sendResumeModel->updateDataWithLog($data,['id' => $post['id']]);
        return Functions::formatJson(1000,$dir.$file);
    }


    /**
     * 获取简历数量
     * @return array
     */
    public function getResumeNumByEmployee()
    {
        $result = [
            'total' => Functions::getCommonByKey('member_max_issue_resume'),
            'already' => 0,
            'remain' => 0
        ];
        $where['employee_id'] = Yii::$app->user->getId();
        $where['is_delete'] = Status::RESUME_DELETE_NO;
        $num = intval(self::getCountByWhere($where));
        if($num) {
            $result['already'] = $num;
            $result['remain'] = intval($result['total'] - $result['already']);
        } else {
            $result['remain'] = intval($result['total']);
        }
        return $result;
    }

    /**
     * 更改审核状态
     * @return array
     */
    public function checkResume()
    {
        $post = Yii::$app->request->post();
        $id = intval($post['id']);
        $afterValue = intval($post['afterVal']);
        $db = Yii::$app->db;
        $trans = $db->beginTransaction();
        try {

            $data['update_time'] = date('Y-m-d H:i:s');
            $data['check_status'] = $afterValue;
            $res = self::updateDataWithLog($data,["id" => $id]);
            if($res === false)
            {
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
