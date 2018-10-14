<?php
namespace backend\models;

use common\models\Functions;
use Yii;
use yii\base\Exception;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
/**
 * UserBackend model，这是user组件在后台认证的类
 * 集成了IdentityInterface这个认证接口（抽象类），注意：要实现抽象类中的所有方法
 *
 */
class EmployeeLogin extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 2;
    const STATUS_ACTIVE = 1;
    public $auth_key;

    /**
     * 设置表名
     */
    public static function tableName()
    {
        return '{{%employee_info}}';
    }

    /**
     * 操作进行限制
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * 验证规则
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED],'message' => '{attribute}字段不对'],
        ];
    }

    //--------- （登录）覆盖接口认证方法开始 ---------//

    /**
     * 寻找认证,覆盖认证接口的方法
     */
    public static function findIdentity($id)
    {
        return self::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * 覆盖认证接口的方法
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * 覆盖认证接口的方法
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * 覆盖认证接口的方法
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * 覆盖认证接口的方法
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }


    //------- （登录）覆盖接口认证方法结束 -----------//


    //------- （登录）以下都是自定义的逻辑开始 -------//
    /**
     * 通过登录名查找用户
     * @param $loginName
     * @return static
     */
    public static function findByLoginName($loginName)
    {
        return static::findOne(['login_name' => $loginName]);
    }

    /**
     * 验证密码
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        //方法一:使用自带的加密方式
        return $this->password === md5($password);
    }

    /**
     * 验证状态
     */
    public function validateStatus($login_name)
    {
        $statusArray = self::find()->where(['login_name' => $login_name])->select('status')->asArray()->one();
        if(intval($statusArray['status']) !== self::STATUS_ACTIVE)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    /**
     * 生成'记住我' 的认证密钥
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }
    //------- （登录）以上都是自定义的逻辑 结束 -------//
}
