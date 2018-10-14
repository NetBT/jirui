<?php
namespace backend\models;

use common\models\Status;
use Yii;
use yii\base\Exception;
use common\models\Functions;

class SendResume extends Common
{
    public static function tableName()
    {
        return '{{%ab_send_resume}}';
    }


    public function getListData($type = '')
    {
        $returnData = [
            "draw" => intval(Yii::$app->request->post('draw')),
            "recordsTotal" => 0,
            "recordsFiltered" => 0,
            "data" => null
        ];
        //搜索条件
        $searchWhere  = $this->getSearch(Yii::$app->request->post('extra_search'),$type);//自定义搜索条件

        //得到文章的总数（但是还没有从数据库取数据）
        if(isset($searchWhere['andWhere'])){
            $count = self::getCountByAndWhere($searchWhere['where'], $searchWhere['andWhere']);
        } else {
            $count = self::getCountByWhere($searchWhere);
        }
        $returnData["recordsTotal"] = $returnData['recordsFiltered'] = intval($count);

        //设置分页
        $this->setPagination();

        $selectField = "*";
//        $fields = $this->_getFields();
//        foreach($fields as $key => $value)
//        {
//            $selectField .= ",".$value;
//        }
//        $selectField = ltrim($selectField,',');
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
     * @param $type
     * @return string
     */
    public function getSearch ($search = [], $type = '')
    {
        $where = [];
        $andWhere = [];

        if(!empty($search)){
            $recruitPostId = isset($search['recruitPostId']) ? $search['recruitPostId'] : '';
            if($recruitPostId) {
                $where['recruit_post_id'] = $recruitPostId;
            }

            $download = isset($search['download']) ? $search['download'] : '';
            if($download) {
                $where['is_download'] = $download;
            }
        }

        if(!empty($type)) {
            switch ($type) {
                case 'invitation' :
                    $where['is_invitation'] = Status::RESUME_INVITATION_YES;
                    break;
                case 'download' :
                    $where['is_download'] = Status::RESUME_DOWNLOAD_YES;
                    break;
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
        $resumeArray = Resume::getByWhere(['is_delete' => Status::RESUME_DELETE_NO]);
        $resumeInfo = Functions::extractKey($resumeArray,'id');

        $recruitArray = RecruitPost::getByWhere(['is_end' => Status::RECRUIT_POST_END_NO]);
        $recruitInfo = Functions::extractKey($recruitArray,'id');
        foreach($list['data'] as $key => $value)
        {
            $list['data'][$key]['create_time'] = date('Y-m-d',strtotime($value['create_time'])).'</br>'.date('H:i:s',strtotime($value['create_time']));
            $list['data'][$key]['is_download'] =  Status::resumeDownloadMap()[$value['is_download']];
            //招聘信息
            $list['data'][$key]['recruit_title'] = $recruitInfo[$value['recruit_post_id']]['recruit_title'];
            $list['data'][$key]['recruit_time'] = date('Y-m-d',strtotime($recruitInfo[$value['recruit_post_id']]['create_time'])).'</br>'.date('H:i:s',strtotime($recruitInfo[$value['recruit_post_id']]['create_time']));

            //简历信息
            $list['data'][$key]['title'] = $resumeInfo[$value['resume_id']]['resume_title'];
            $list['data'][$key]['tel'] = $resumeInfo[$value['resume_id']]['tel'];
            $list['data'][$key]['resume_title'] = $resumeInfo[$value['resume_id']]['resume_title'];
            $list['data'][$key]['resume_time'] = date('Y-m-d',strtotime($value['create_time'])).'</br>'.date('H:i:s',strtotime($value['create_time']));
            $list['data'][$key]['name'] = $resumeInfo[$value['resume_id']]['name'];
            $list['data'][$key]['address'] = $resumeInfo[$value['resume_id']]['address'];
            $list['data'][$key]['expected_salary'] = Status::expectedSalaryLabelMap()[$resumeInfo[$value['resume_id']]['expected_salary']];
            $list['data'][$key]['working_duration'] =  Status::workingDurationLabelMap()[$resumeInfo[$value['resume_id']]['working_duration']];
            $list['data'][$key]['degree'] =  isset($resumeInfo[$value['resume_id']]['degree']) ? Status::degreeMap()[$resumeInfo[$value['resume_id']]['degree']] : '--';
            $list['data'][$key]['working_status'] =  Status::workingStatusMap()[$resumeInfo[$value['resume_id']]['working_status']];
            $list['data'][$key]['is_invite'] =  ($value['is_invitation'] == Status::RESUME_INVITATION_YES) ? true : false;
        }
        return $list;
    }


    /**
     * 投递简历
     */
    public function apply()
    {
        $post = Yii::$app->request->post();

        $recruitPostId = $post['id'];
        $guestId = Yii::$app->user->getId();

        $data = [];
        $trans = Yii::$app->db->beginTransaction();
        try {
            if (!$recruitPostId) {
                throw new Exception('请选择职位');
            }

            if (!$guestId) {
                throw new Exception('未获得简历信息');
            }

            //判断当前用户投递简历数量
            $numWhere['send_user_id'] = $guestId;
            $startTime = date('Y-m-d 00:00:00');
            $endTime = date('Y-m-d 23:59:59');
            $num = self::getCountByAndWhere($numWhere,['between','create_time',$startTime,$endTime]);
            $totalNum = Functions::getCommonByKey('everyday_max_apply_post');
            if(($totalNum != 0) && $num > $totalNum) {
                throw new Exception('今日投递简历个数已达到限制，请明天再投递');
            }

            $resumeInfo = Resume::getOneByWhere(['employee_id' => $guestId,'is_default' => Status::RESUME_DEFAULT_YES]);
            if(empty($resumeInfo)) {
                throw new Exception('请先设置默认简历');
            }

            $recruitPostInfo = RecruitPost::getOneInfoById($recruitPostId);

            $data['resume_id'] = $resumeInfo['id']; //简历ID
            $data['recruit_post_business_id'] = $recruitPostInfo['business_id'];//职位所属加盟商
            $data['recruit_post_id'] = $recruitPostId;//职位ID
            $data['send_user_id'] = $guestId;//投递者
            $data['create_time'] = date('Y-m-d H:i:s');//投递时间

            $res = self::insertDataWithLog($data);
            if($res === false) {
                throw new Exception('投递失败');
            }

            $trans->commit();
            return Functions::formatJson(1000,'投递成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(1001,$e->getMessage());
        }
    }


    /**
     * 面试邀请
     */
    public function inviteResume()
    {
        $post = Yii::$app->request->post();

        $id = $post['id'];

        $data = [];
        $trans = Yii::$app->db->beginTransaction();
        try {
            if (!$id) {
                throw new Exception('请选择简历');
            }


            $data['update_time'] = date('Y-m-d H:i:s');//投递时间
            $data['is_invitation'] = Status::RESUME_INVITATION_YES;

            $res = self::updateDataWithLog($data,['id' => $id]);
            if($res === false) {
                throw new Exception('邀请失败');
            }
            $trans->commit();
            return Functions::formatJson(1000,'邀请成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(1001,$e->getMessage());
        }
    }

    /**
     * 简历详情
     * @return array|null|\yii\db\ActiveRecord
     */
    public function quoteInfo()
    {
        $resumeModel = new Resume();
        $id = Yii::$app->request->post('id');
        $sendResumeInfo = self::getOneByWhere(['id' => $id]);
        $list = $resumeModel->quoteInfo($sendResumeInfo['resume_id']);
        $list['send_resume_id'] = $id;
        $isInviteInfo = self::getOneByWhere(['id' => $id]);
        $isInvite = $isInviteInfo['is_invitation'];
        $list['is_invite'] = ($isInvite == Status::RESUME_INVITATION_YES) ? true : false;
        $list['is_download'] = $isInviteInfo['is_download'];
        return $list;
    }

}
