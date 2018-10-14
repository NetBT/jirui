<?php
namespace frontend\models;

use common\models\Status;
use Yii;

class UserForm extends User
{
    public $login_name;
    public $password;
    public $rememberMe = false;
    public $re_password;
    public $verify_code;
    public $new_password;
    public $credit_secret_password;
    public $_user;

    public function rules()
    {
        return [
            //登录
            [['login_name', 'password'],'required','message' => "{attribute}不能为空", 'on' => 'login'],
            ['login_name','filter','filter' => 'trim'],
            ['login_name',"string", "max"=>"30", "min"=>"5", "tooLong"=>"{attribute}不能大于30个字符", "tooShort"=>"{attribute}不能小于5个字符", "on" => "login"],
            ['rememberMe', 'boolean', 'on' => 'login'],
            ['password', 'validatePassword', 'on' => 'login'],
            //手机注册
            ['login_name','required','message' => "{attribute}不能为空", 'on' => 'phoneRegister'],
            ['login_name', 'match' , 'pattern' => '/^((13[0-9])|(14[5|7])|(15([0-3]|[5-9]))|(17[0-9])|(18[0,5-9]))\\d{8}$/', 'message' => '手机号格式不正确', 'on' => 'phoneRegister'],
            ['login_name', 'validateRegisterLoginName', 'on' => 'phoneRegister'],
            ['verify_code', 'required', 'on' => 'phoneRegister'],
            ['verify_code', 'validatePhoneCode', 'on' => 'phoneRegister'],
            [['re_password','new_password'], 'required', 'message' => "不能为空", 'on' => 'phoneRegister'],
            ['new_password', "string", "max"=>"18", "min"=>"5", "tooLong"=>"{attribute}不能大于18个字符", "tooShort"=>"{attribute}不能小于5个字符", "on" => "phoneRegister"],
            ['re_password', 'compare',  'compareAttribute' => 'new_password', 'message' => '密码不一致', 'on' => "phoneRegister"],
            //邮箱注册
            [['login_name', 'password'],'required','message' => "{attribute}不能为空", 'on' => 'mailRegister'],
            ['login_name',"string", "max"=>"11", "min"=>"11", "tooLong"=>"{attribute}不能大于11个字符", "tooShort"=>"{attribute}不能小于11个字符", "on" => "mailRegister"],
            ['login_name', "match", 'pattern' => '/^[A-Za-zd]+([-_.][A-Za-zd]+)*@([A-Za-zd]+[-.])+[A-Za-zd]{2,5}$/', 'message' => '邮箱格式不正确', "on" => "mailRegister"],

            ['verify_code', 'validatePhoneCode', 'on' => 'mailRegister'],
            ['password',"string", "max"=>"18", "min"=>"5", "tooLong"=>"{attribute}不能大于18个字符", "tooShort"=>"{attribute}不能小于5个字符", "on" => "mailRegister"],
            ['re_password', 'required', 'message' => "不能为空", 'on' => 'mailRegister'],
            ['re_password', 'compare',  'compareAttribute' => 'password','message' => '两次密码不一致', 'on' => 'mailRegister'],
            //修改密码
            ['password', 'validateOriginPassword', 'on' => 'modifyPassword'],
            ['new_password',"string", "max"=>"18", "min"=>"5", "tooLong"=>"{attribute}不能大于18个字符", "tooShort"=>"{attribute}不能小于5个字符", "on" => 'modifyPassword'],
            ['new_password', 'required', 'message' => "新密码不能为空", 'on' => 'modifyPassword'],
            ['re_password', 'required', 'message' => "不能为空", 'on' => 'modifyPassword'],
            ['re_password', 'compare',  'compareAttribute' => 'new_password','message' => '两次密码不一致', 'on' => 'modifyPassword'],
            //修改资金密码
            ['credit_secret_password', 'required', 'message' => '资金密码不能为空', 'on' => 'setSafePassword'],
            ['credit_secret_password', 'validateSafePassword', 'on' => 'setSafePassword'],
            ['verify_code', 'validatePhoneCode', 'on' => 'setSafePassword'],
            ['new_password', 'required', 'message' => "新密码不能为空", 'on' => 'setSafePassword'],
            ['re_password', 'required', 'message' => "不能为空", 'on' => 'setSafePassword'],
            ['re_password', 'compare',  'compareAttribute' => 'new_password','message' => '两次密码不一致', 'on' => 'setSafePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'login_name' => '用户名',
            'password' => '密码',
            'rememberMe' => '记住我',
            're_password' => '确认密码',
            'new_password' => '新密码',
            'credit_secret_password' => '资金密码',
        ];
    }
    /**
     * 设置场景
     * @return array
     */
    public function scenarios()
    {
        $newScenarios =  [
            'login' => ['login_name','password','rememberMe'],
            'phoneRegister' => ['login_name','new_password','re_password', 'verify_code'],
            'mailRegister' => ['login_name','password','re_password', 'verify_code'],
            'modifyPassword' => ['password','new_password', 're_password', 'verify_code'],
            'setSafePassword' => ['credit_secret_password','new_password', 're_password', 'verify_code']
        ];
        return array_merge(parent::scenarios(), $newScenarios);
    }

    /**
     * 自定义的密码认证方法
     * 该方法作为内部验证密码方法.
     *
     * @param string $attribute 目前验证的特性
     */
    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, '密码错误');
            }
            if(!$this->validateStatus($this->login_name))
            {
                $this->addError($attribute,'账号已被禁用，请联系管理员.');
            }
        }
    }

    /**
     * 验证原始密码
     * @param $attribute
     */
    public function validateOriginPassword($attribute)
    {
        if (!$this->hasErrors()) {
            if (md5($this->password) != Yii::$app->user->identity->password) {
                $this->addError($attribute, '原密码不正确');
            }
        }
    }

    /**
     * 验证原始资金密码
     * @param $attribute
     */
    public function validateSafePassword($attribute)
    {
        if (!$this->hasErrors()) {
            if (md5($this->credit_secret_password) != Yii::$app->user->identity->credit_secret_password) {
                $this->addError($attribute, '原资金密码不正确');
            }
        }
    }

    /**
     * 注册验证登录名是否存在
     * @param $attribute
     */
    public function validateRegisterLoginName($attribute)
    {
        if (!$this->hasErrors()) {
            $isExists = static::getCountByWhere(['login_name' => $this->login_name]);
            if ($isExists > 0) {
                $this->addError($attribute, '用户名已存在');
            }
        }
    }

    /**
     * 验证状态
     * @param $login_name
     *
     * @return bool
     */
    public function validateStatus($login_name)
    {
        if (empty($login_name)) {
            return false;
        }
        $statusArray = static::getOneByWhere(['login_name' => $login_name], ['status']);
        return intval($statusArray['status']) === Status::USER_INFO_STATUS_NORMAL;
    }

    /**
     * 验证手机验证码
     * @param $attribute
     */
    public function validatePhoneCode($attribute) {
        if (!$this->hasErrors()) {
            $code = $this->getPhoneCode();
            if (!$code) {
                $this->addError($attribute, '请获取验证码');
            } else if ($code != $this->verify_code) {
                $this->addError($attribute, '');
            }
        }
    }

    /**
     * 获取手机验证码
     * @return int
     */
    private function getPhoneCode() {
        $session = Yii::$app->session;
        return 1234;
        return $session->get('phoneCode');
    }

    /**
     * 获取邮箱验证码
     * @return mixed
     */
    private function getMailCode() {
        $session = Yii::$app->session;
        return $session->get('mailCode');
    }

    /**
     * 验证邮箱验证码
     * @param $attribute
     */
    public function validateMailCode($attribute) {
        if (!$this->hasErrors()) {
            $code = $this->getMailCode();
            if (!$code) {
                $this->addError($attribute, '请获取验证码');
            } else if ($code !== $this->verify_code) {
                $this->addError($attribute, '');
            }
        }
    }

    /**
     * 登录用户 需要的用户名和密码
     *
     * @return bool 用户是否登录成功
     */
    public function login()
    {
        if ($this->validate()) {
            //把上次登录时间和登录IP信息更改
            $userModel = new User();
            if($userModel->updateLogin($this->login_name))
            {
                // 校验成功，session保存用户信息
                return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
            }
        } else {
            return false;
        }
    }

    /**
     * 通过[login_name]查找用户
     * @return null|static
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = UserLogin::findByLoginName($this->login_name);
        }
        return $this->_user;
    }

}
