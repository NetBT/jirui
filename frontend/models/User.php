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
class User extends Common
{
    public static function tableName()
    {
        return '{{%user_info}}';
    }

    /**
     * 保存用户信息
     * @param $data
     *
     * @return array
     */
    public function saveInfo($data) {
        $data['update_time'] = date('Y-m-d H:i:s');
        try {
            if (isset($data['province_id']) && !empty($data['province_id'])) {
                $province = Address::getOneByWhere(['id' => $data['province_id']]);
                $data['address'] = $province['name'];
                if (isset($data['city_id']) && !empty($data['city_id'])) {
                    $city = Address::getOneByWhere(['id' => $data['city_id']]);
                    $data['address'] .=  !empty($city['name']) ? $city['name'] : '';
                }
            }

            if (isset($data['nick_name'])) {
                if (empty($data['nick_name'])) {
                    throw new Exception('昵称不能为空');
                }

                $userInfo = static::getOneByWhere(['nick_name' => $data['nick_name']]);
                if (!empty($userInfo) && $userInfo['id'] != Yii::$app->user->getId()) {
                    throw new Exception('昵称已存在');
                }
            }
            $res = static::updateData($data, ['id' => Yii::$app->user->getId()]);
            if ($res <= 0) {
                throw new Exception('操作失败');
            }
            $user = static::getOneByWhere(['id' => Yii::$app->user->getId()]);
            $user = $this->setChinese($user, ['sexy', 'blood_type', 'date_of_both']);
            return Functions::formatJson(1000, '', $user);
        } catch (Exception $e) {
            return Functions::formatJson(2000, $e->getMessage(), []);
        }
    }

    public function updateUserFace() {
        $data['update_time'] = date('Y-m-d H:i:s');
        try {
            $user = static::getOneByWhere(['id' => Yii::$app->user->getId()]);
            $newFacePath = Functions::uploadFile('user-face', $user['user_face']);
            if ($newFacePath === false) {
                throw new Exception('上传失败');
            }
            $data['user_face'] = $newFacePath;
            $res = static::updateData($data, ['id' => Yii::$app->user->getId()]);
            if ($res <= 0) {
                throw new Exception('操作失败');
            }
            return Functions::formatJson(1000, '', static::imgUrl() . $newFacePath);
        } catch (Exception $e) {
            return Functions::formatJson(2000, $e->getMessage(), []);
        }
    }

    public function setChinese($data = [], $fields = []) {
        if (empty($fields) || empty($data)) {
            return $data;
        }
        foreach ($fields as $v) {
            if (array_key_exists($v, $data) && !empty($data[$v])) {
                switch ($v) {
                    case 'sexy' :
                        $data[$v] = Status::userInfoSexyMap()[$data[$v]];
                        break;
                    case 'blood_type' :
                        $data[$v] = Status::userInfoBloodTypeMap()[$data[$v]];
                        break;
                    case 'date_of_both':
                        $data['both_year'] = explode('-', $data[$v])[0];
                        $data['both_month'] = explode('-', $data[$v])[0];
                        break;
                    default:
                        break;
                }
            }
        }
        return $data;
    }

    public function modifyPassword () {
        $postData = Yii::$app->request->post('UserForm');
        $data['update_time'] = date("Y-m-d H:i:s");
        $data['password'] = md5($postData['new_password']);
        $res = static::updateData($data, ['id' => Yii::$app->user->getId()]);
        return boolval($res);
    }

    public function setSafePass () {
        $postData = Yii::$app->request->post('UserForm');
        $data['update_time'] = date("Y-m-d H:i:s");
        $data['credit_secret_password'] = md5($postData['new_password']);
        $res = static::updateData($data, ['id' => Yii::$app->user->getId()]);
        return boolval($res);
    }
    /**
     * 登录之后更改状态，然后把登录信息存入登录日志表中
     * @param string $loginName
     * @return bool
     */
    public function updateLogin($loginName = '')
    {
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();//开始事务
        try{
            $userInfo["is_online"] = Status::USER_INFO_IS_ONLINE;
            $userInfo["update_time"] = date("Y-m-d H:i:s");
            //先更新会员表
            $userFlag = static::updateData($userInfo,['login_name' => $loginName]);
            if ($userFlag <= 0) {
                throw new Exception('');
            }
            $userInfo = static::getOneByWhere(['login_name' => $loginName]);
            //再添加会员登录日志
            $updateInfo['user_id'] = intval($userInfo['id']);
            $updateInfo['login_time'] = date("Y-m-d H:i:s");
            $updateInfo['status'] = Status::USER_LOGIN_STATUS_NORMAL;
            $updateInfo['login_ip'] = Yii::$app->getRequest()->getUserIP();

            $functions = new Functions();
            $loginInfo = $functions->GetIpLookup($updateInfo['login_ip']);

            if (!empty($loginInfo)) {
                $updateInfo["login_position"] = $loginInfo["country"] . $loginInfo["province"] . $loginInfo["city"];
            }
            $userLoginLog = UserLoginLog::insertData($updateInfo);

            if($userLoginLog <= 0)
            {
                throw new Exception('');
            }
            $transaction->commit();
            return true;
        }
        catch(Exception $e)
        {
            return false;
        }
    }

    /**
     * 用户注册
     * @param int $type
     *
     * @return bool
     */
    public function register ($type = 1) {
        $formData = Yii::$app->request->post('UserForm');
        $data['login_name'] = $formData['login_name'];
        $data['password'] = md5($formData['new_password']);
        $data['nick_name'] = time() . '-' . rand(10000, 20000);
        $data['register_time'] = date("Y-m-d H:i:s");
        $data['user_face'] = '/theme/default/images/user-faces.png';
        $data['status'] = Status::USER_INFO_STATUS_NORMAL;
        $data['account_type'] = $type;
        $res = $this->insertData($data);
        if ($res <= 0) {
            return false;
        }
        $user = UserLogin::findByLoginName(['login_name' => $data['login_name']]);
        if (Yii::$app->user->login($user)) {
            $this->updateLogin($data['login_name']);
        }
        return true;
    }


    public function modifySecretInfo()
    {
        $useId = Yii::$app->user->getId();
        try {
            $post = Yii::$app->request->post();
            $oldPass = $post["oldPass"];

            $info = static::findOne($useId);
            if (!empty($info->credit_secret_password) && md5($oldPass) !== $info->credit_secret_password) {
                throw new Exception("当前资金密码不正确");
            }
            $newPass = $post["newPass"];
            $comPass = $post["comPass"];
            if ($newPass != $comPass) {
                throw new Exception("确认资金密码不正确");
            }
            if (md5($newPass) == $info["password"]) {
                throw new Exception("资金密码与登录密码相同");
            }
            $info->credit_secret_password = md5($newPass);
            $info->update_time = date("Y-m-d H:i:s");
            $info->save();

            return ["success" => true, 'message' => '修改成功'];
        } catch (Exception $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    public function saveQuestion()
    {
        $post = Yii::$app->request->post();
        $questions = json_decode($post["questions"], true);
        $userId = Yii::$app->user->getId();

        $updateData["question_one"] = $questions[0]['value'];
        $updateData["question_one_answer"] = $questions[0]['answer'];
        $updateData["question_two"] = $questions[1]['value'];
        $updateData["question_two_answer"] = $questions[1]['answer'];
        $updateData["question_three"] = $questions[2]['value'];
        $updateData["question_three_answer"] = $questions[2]['answer'];
        $updateData["update_time"] = date("Y-m-d H:i:s");

        $db = Yii::$app->db;
        $res = $db->createCommand()->update($this->_tableName, $updateData, "id={$userId}")->execute();
        if ($res) {
            return ["success" => true, "message" => '修改成功'];
        }

        return ["success" => false, "message" => '修改失败'];
    }

    /**
     * 通过登录名获取用户信息 如果登录名为空  则为返回false
     *
     * @param string $loginName
     * @param string $fields
     *
     * @return array|bool|null|ActiveRecord
     */
    public function quoteUserInfoByLoginName($loginName = '', $fields = "*")
    {
        if (empty($loginName)) {
            return false;
        }

        return static::find()->select($fields)->where(["login_name" => $loginName])->asArray()->one();
    }
}
