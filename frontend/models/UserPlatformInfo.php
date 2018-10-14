<?php

namespace frontend\models;

use common\models\Common;
use common\models\Functions;
use common\models\Status;
use Yii;
use yii\base\Exception;
use yii\db\ActiveRecord;

/**
 * ContactForm is the model behind the contact form.
 */
class UserPlatformInfo extends Common
{
    public static function tableName()
    {
        return '{{%user_platform_info}}';
    }

    public function saveInfo($data = []) {
        $saveData['user_id'] = Yii::$app->user->getId();

        try {
            $saveData['platform_id'] = intval($data['platform']);
            $saveData['platform_account'] = strval($data['account']);
            $saveData['platform_account_password'] = $data['password'];
            $saveData['platform_vip_endtime'] = strval($data['endTime']);
            $currDateTime = date("Y-m-d H:i:s");
            if ($currDateTime < $saveData['platform_vip_endtime']) {
                $saveData['platform_account_status'] = Status::USER_PLATFORM_STATUS_NORMAL;
            } else {
                $saveData['platform_account_status'] = Status::USER_PLATFORM_STATUS_OVERTIME;
            }

            if (isset($data['id']) && !empty($data['id'])) {
                if (intval($data['id']) <= 0) {
                    throw new Exception('用户名错误');
                }
                $saveData['update_time'] = date("Y-m-d H:i:s");
                $saveWhere['id'] = intval($data['id']);
                $res = static::updateData($saveData, $saveWhere);
                if ($res <= 0) {
                    throw new Exception('修改失败');
                }
                return Functions::formatJson(1000, '修改成功');

            } else {
                $saveData['create_time'] = date("Y-m-d H:i:s");
                $res = static::insertData($saveData);
                if ($res <= 0) {
                    throw new Exception('添加失败');
                }
                return Functions::formatJson(1000, '添加成功');
            }
        } catch (Exception $e) {
            return Functions::formatJson(2000, $e->getMessage(), []);
        }
    }

    public function quoteUserVip() {
        $returnData = [
            "draw" => intval(Yii::$app->request->post('draw')),
            "recordsTotal" => 0,
            "recordsFiltered" => 0,
            "data" => null
        ];

//        //自定义搜索条件，组装where条件
        $where = [];
        $params = Yii::$app->request->post('extra_search');
        $where["user_id"] = Yii::$app->user->getId();
        isset($params['platform']) && !empty($params['platform']) ? ($where['platform_id'] = $params['platform']) : null;
        isset($params['status']) && !empty($params['status']) ? ($where['platform_account_status'] = $params['status']) : null;

        $where['user_id'] = Functions::getCurrUser()->getId();
        //得到文章的总数（但是还没有从数据库取数据）
        $count = static::getCountByWhere($where);
        $returnData["recordsTotal"] = $returnData['recordsFiltered'] = intval($count);

        //分页
        $offset = $limit = "";
        $start = Yii::$app->request->post('start');
        $length = Yii::$app->request->post('length');
        $limitFlag = isset($start) && $length != -1;

        if($limitFlag)
        {
            $offset = $start;
            $limit = $length;
        }
        $fields = ['id', 'platform_id', 'platform_account', 'platform_account_password', 'platform_vip_endtime', 'platform_account_status'];

        //最终返回的data数据
        $list = static::getByWhere($where, $fields, 'platform_vip_endtime desc', $offset, $limit);
        $videoPlatformList = VideoPlatform::getByWhere([], ['id', 'platform_name', 'platform_app_logo']);
        $videoPlatformList = Functions::extractKey($videoPlatformList, 'id');
        //拼装数据
        $returnData['data'] = [];
        foreach ($list as $v) {
            $tmp["id"] = $v["id"];
            $tmp["myPlatformName"] = $videoPlatformList[$v['platform_id']]['platform_name'];
//            $tmp["myPlatformLogo"] = Common::imgUrl() . $videoPlatformList[$v['id']]['platform_app_logo'];
            $tmp["myAccount"] = $v['platform_account'];
            $tmp["myPlatformLogo"] = $videoPlatformList[$v['platform_id']]['platform_app_logo'];
            $tmp["password"] = $v['platform_account_password'];
            $tmp["endTime"] = $v['platform_vip_endtime'];
            //定义字体颜色
            switch ($v['platform_account_status']) {
                case Status::USER_PLATFORM_STATUS_OVERTIME:
                    $_class = 'text-gray';
                    break;
                case Status::USER_PLATFORM_STATUS_EXCEPTION:
                    $_class = 'text-hot';
                    break;
                default:
                    $_class = 'text-black';
            }
            $tmp["status"] = '<span class="'.$_class.'">' . Status::userPlatformStatusMap()[$v['platform_account_status']] . '</span>';
            array_push($returnData['data'],$tmp);
        }
        return $returnData;
    }

    public static function getCurrVipList($fields = ['*']){
        $where['user_id'] = Yii::$app->user->getId();
        return static::getByWhere($where, $fields);
    }
}
