<?php
namespace frontend\models;

use common\models\Functions;
use common\models\Status;
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
class UserLogin extends ActiveRecord implements IdentityInterface
{

    public $auth_key;


    /**
     * 设置表名
     */
    public static function tableName()
    {
        return '{{%user_info}}';
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
            ['status', 'default', 'value' => Status::USER_INFO_STATUS_NORMAL],
        ];
    }


    /**
     * 寻找认证,覆盖认证接口的方法
     */
    public static function findIdentity($id)
    {
        return self::findOne(['id' => $id, 'status' => Status::USER_INFO_STATUS_NORMAL]);
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
     * 返回当前AR类所对应的id
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * 覆盖认证接口的方法
     * 返回当前AR类所对应的auth_key
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


    //-------以下都是自定义的逻辑-------//
    /**
     * 通过登录名查找用户
     *
     * @param string $username
     * @return static|null
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


    public static function findById($id)
    {
        return self::find()->where(['id' => $id])->asArray()->one();
    }


    /**
     * 生成'记住我' 的认证密钥
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }
}
