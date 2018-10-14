<?php
namespace backend\models;

use common\models\Status;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Yii;
use yii\base\Exception;
use common\models\Functions;

class RecruitPost extends Common
{
    private $fieldArray = [
        "id",
        "business_id",
        "recruit_title",
        "address",
        "expected_salary",
        "working_duration",
        "degree",
        "post_id",
        "shop_introduced",
        "job_specification",
        "create_time",
        "update_time",
        "is_end",
        "business_name",
        "check_status",
    ];

    public static function tableName()
    {
        return '{{%recruit_post_info}}';
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
            [['recruit_title','post_id','address','shop_introduced','job_specification'], 'required','message' => '{attribute}不能为空','on' => 'addEdit'],
            ['business_name', 'validUniqueBusinessName','message' => '{attribute}不能重复','on' => 'addEdit'],
        ];
    }

    /**
     * 设置属性名称
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'recruit_title' => '标题',
            'address' => '地址',
            'expected_salary' => '期望薪资',
            'working_duration' => '工作年限',
            'degree' => '学历',
            'post_id' => '职位',
            'shop_introduced' => '店铺介绍',
            'job_specification' => '任职要求',
            'business_name' => '店铺名称',
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
                'business_name',
                'recruit_title',
                'address',
                'expected_salary',
                'working_duration',
                'degree',
                'post_id',
                'shop_introduced',
                'business_name',
                'job_specification',
            ],

        ];
        return array_merge(parent::scenarios(), $newScenarios);
    }

    /**
     * 判断发布职位商铺名称是否唯一
     * @param $attribute
     * @param $params
     * @return bool
     */
    public function validUniqueBusinessName($attribute, $params)
    {
        $abNameRepetition = Functions::getCommonByKey('ab_name_repetition');
        if($abNameRepetition == 2) {
            //不允许店铺名称重复
            $info = self::getByWhere(['business_name' => $this->business_name]);
            if($info) {
                $this->addError($attribute, '商铺名称不能重复.');
            }
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
        $orderSql = 'id DESC';
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
        $businessId = Common::getBusinessId();
        if(Common::checkEmployeeType() == Status::MODULE_TYPE_GUEST) {
            //如果要投简历  需要显示已经审核过的职位
            $where['check_status'] = Status::RECRUIT_CHECK_STATUS_YES;
        }
        if(Common::checkEmployeeType() == Status::MODULE_TYPE_FRANCHISEE) {
            if(!empty($businessId))
            {
                $where['business_id'] = $businessId;
            }
        }

        $where['is_end'] = Status::RECRUIT_POST_END_NO;
        if(!empty($search)){
            $sendStatus = isset($search['sendStatus']) ? $search['sendStatus'] : '';
            $recruitPostIdArray = SendResume::getByWhere(['send_user_id' => Yii::$app->user->getId()],'recruit_post_id');
            $recruitPostIdInfo = Functions::extractKey($recruitPostIdArray,'recruit_post_id','recruit_post_id');
            $checkStatus = isset($search['checkStatus']) ? $search['checkStatus'] : '';

            $totalRecruitPostIdArray = self::getByWhere(['is_end' => Status::RECRUIT_POST_END_NO],'id');
            $totalRecruitPostIdInfo = Functions::extractKey($totalRecruitPostIdArray,'id','id');
            if($sendStatus) {
                switch ($sendStatus) {
                    case Status::RESUME_SEND_YES:
                        $where['id'] = $recruitPostIdInfo;
                        break;
                    case Status::RESUME_SEND_NO:
                        $recruitPostIdInfo = array_diff($totalRecruitPostIdInfo,$recruitPostIdInfo);
                        $where['id'] = $recruitPostIdInfo;
                        break;
                }
            }

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
        $employeeInfo = EmployeePost::getFormArray(['status' => Status::EMPLOYEE_POST_SUCCESS],'id','post_name');
        $abInfo = AB::getFormArray('','id','AB_name');
        foreach($list['data'] as $key => $value)
        {
            $list['data'][$key]['create_time'] = date('Y-m-d',strtotime($value['create_time'])).'</br>'.date('H:i:s',strtotime($value['create_time']));
            $list['data'][$key]['update_time'] = date('Y-m-d',strtotime($value['update_time'])).'</br>'.date('H:i:s',strtotime($value['update_time']));
            $list['data'][$key]['expected_salary'] = $value['expected_salary'] ? Status::expectedSalaryLabelMap()[$value['expected_salary']] : '不限';
            $list['data'][$key]['working_duration'] = $value['working_duration'] ? Status::workingDurationLabelMap()[$value['working_duration']] : '不限';
            $list['data'][$key]['degree'] = $value['degree'] ? Status::degreeMap()[$value['degree']] : '不限';
            $list['data'][$key]['post_id'] = $value['post_id'] ? $employeeInfo[$value['post_id']] : '不限职位';
            $list['data'][$key]['check_status_name'] = $value['check_status'] ? Status::recruitCheckStatusMap()[$value['check_status']] : '--';
            $list['data'][$key]['business_name'] = $value['business_id'] ? $abInfo[$value['business_id']] : '--';

            $list['data'][$key]['time'] = $value['update_time'] ? date('Y-m-d',strtotime($value['update_time'])).'</br>'.date('H:i:s',strtotime($value['update_time'])) :  date('Y-m-d',strtotime($value['create_time'])).'</br>'.date('H:i:s',strtotime($value['create_time']));

            $list['data'][$key]['is_send'] = false;
            $isSend = SendResume::getOneByWhere(['recruit_post_id' => $value['id'],'send_user_id' => Yii::$app->user->getId()]);
            if($isSend) {
                $list['data'][$key]['is_send'] = true;
            }
        }
        return $list;
    }


    /**
     * 添加编辑
     */
    public function addEdit()
    {
        $trans = Yii::$app->db->beginTransaction();
        try {
            if (!$this->validate()) {
                throw new Exception(false);
            }
            $post = Yii::$app->request->post('RecruitPost');
            $data = [];
            if (isset($post['id']) && !empty($post['id'])) {
                $data = array_merge($data, $this->getSaveData('addEdit', $post));
                $data['update_time'] = date("Y-m-d H:i:s");
                //判断系统参数 修改职位后的审核状态
                $abUpdatePostStatus = Functions::getCommonByKey('ab_update_post_status');
                if($abUpdatePostStatus && ($abUpdatePostStatus != Status::RECRUIT_CHECK_STATUS_BCBB)) {
                    $data['check_Status'] = $abUpdatePostStatus;
                }
                $res = static::updateDataWithLog($data, ['id' => $post['id']]);
            } else {
                $data = array_merge($data, $this->getSaveData('addEdit', $post));
                $data['business_id'] = Common::getBusinessId();
                $data['create_time'] =  $data['update_time'] = date("Y-m-d H:i:s");
                $data['is_end'] =  Status::RECRUIT_POST_END_NO;
                //判断系统参数 添加职位后的审核状态
                $abIssuePostStatus = Functions::getCommonByKey('ab_issue_post_status');
                if($abIssuePostStatus ) {
                    $data['check_Status'] = $abIssuePostStatus;
                }
                $res = static::insertDataWithLog($data);
            }

            if ($res === false) {
                throw new Exception(false);
            }
            $trans->commit();
            return Functions::formatJson(1000,'操作成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(1001,'操作失败');
        }
    }


    /**
     * 物理删除简历
     * @return array
     */
    public function endRecruitPost()
    {
        $trans = Yii::$app->db->beginTransaction();
        try {
            $post = Yii::$app->request->post();
            $id = intval($post['id']);
            $data = [];
            if (!empty($id)) {
                $data['update_time'] = date('Y-m-d H:i:s');
                $data['is_end'] = Status::RECRUIT_POST_END_YES;
                $res = static::updateDataWithLog($data, ['id' => $id]);
                if ($res === false) {
                    throw new Exception('结束失败');
                }
            } else {
                throw new Exception('请选择要结束的招聘信息');
            }
            $trans->commit();
            return Functions::formatJson(1000, '结束成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(1001, $e->getMessage());
        }
    }

    public function quoteInfo()
    {
        $id = Yii::$app->request->post('id');

        $employeeInfo = EmployeePost::getFormArray(['status' => Status::EMPLOYEE_POST_SUCCESS],'id','post_name');
        $list = self::getOneInfoById($id);
        $list['expected_salary'] = $list['expected_salary'] ? Status::expectedSalaryLabelMap()[$list['expected_salary']] : '不限';
        $list['working_duration'] = $list['working_duration'] ? Status::workingDurationLabelMap()[$list['working_duration']] : '不限';
        $list['degree'] = $list['degree'] ? Status::degreeMap()[$list['degree']] : '不限';
        $list['post_id'] = $list['post_id'] ? $employeeInfo[$list['post_id']] : '不限职位';
        $list['job_specification'] = $list['job_specification'] ? addslashes($list['job_specification']) : '';

        $isSend = SendResume::getOneByWhere(['recruit_post_id' => $id,'send_user_id' => Yii::$app->user->getId()]);
        $list['is_send'] = $isSend ? true : false;
        return $list;

    }

    /**
     * 更改审核状态
     * @return array
     */
    public function checkRecruit()
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
