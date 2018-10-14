<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

class LoginForm extends Model
{
    public $login_name;
    public $password;
    public $rememberMe = false;
    public $re_password;
    public $verify_code;
    public $new_password;
    public $_user;

    public function rules()
    {
        return [
            [['login_name','password', 'new_password'],'required','message' => "{attribute}不能为空", 'on' => ['login', 'phoneRegister', 'mailRegister', 'modifyPassword']],//两个值必须有
            ['login_name','filter','filter' => 'trim'],
            ['login_name','verifyMail', 'on' => 'mailRegister'],
            ['login_name','verifyPhone', 'on' => 'phoneRegister'],
            ['login_name',"string", "max"=>"30", "min"=>"5", "tooLong"=>"{attribute}不能大于30个字符", "tooShort"=>"{attribute}不能小于5个字符", "on" => "login"],
            ['login_name',"string", "max"=>"11", "min"=>"11", "tooLong"=>"{attribute}不能大于11个字符", "tooShort"=>"{attribute}不能小于11个字符", "on" => "phoneRegister"],
            ['login_name',"string", "max"=>"30", "min"=>"5", "tooLong"=>"{attribute}不能大于30个字符", "tooShort"=>"{attribute}不能小于5个字符", "on" => "mailRegister"],

            ['rememberMe', 'boolean', 'on' => 'login'],
            ['verify_code', 'required', 'on' => ['phoneRegister', 'mailRegister', 'modifyPassword']],
            ['verify_code', 'validatePhoneCode', 'on' => ['phoneRegister', 'modifyPassword']],
            ['verify_code', 'validateMailCode', 'on' => ['mailRegister']],

            // 密码通过validatePassword()方法进行验证
            ['password', 'validatePassword', 'on' => ['login', 'modifyPassword']],
            ['re_password', 'required', 'message' => "不能为空", 'on' => ['phoneRegister', 'mailRegister', 'modifyPassword']],
            ['re_password', 'compare',  'compareAttribute' => 'password','message' => '两次密码不一致', 'on' => ['register', 'new_user', 'modifyPassword']],

            [['password', 'new_password'],"string", "max"=>"18", "min"=>"5", "tooLong"=>"{attribute}不能大于18个字符", "tooShort"=>"{attribute}不能小于5个字符", "on" => ["phoneRegister", 'mailRegister', 'modifyPassword']],

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
            'phoneRegister' => ['login_name','password','re_password', 'verify_code'],
            'mailRegister' => ['login_name','password','re_password', 'verify_code'],
            'modifyPassword' => ['password','new_password', 're_password', 'verify_code'],
        ];
        return array_merge(parent::scenarios(), $newScenarios);
    }

    /**
     * 自定义的密码认证方法
     * 该方法作为内部验证密码方法.
     *
     * @param string $attribute 目前验证的特性
     * @param array $params 键值对的规则
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, '用户名或密码错误.');
            }
            if(!$user->validateStatus($this->login_name))
            {
                $this->addError($attribute,'账号已被禁用，请联系管理员.');
            }
        }
    }
    public function verifyPhone($attribute) {
        if (!$this->hasErrors()) {
            $regex = "/^((13[0-9])|(14[5|7])|(15([0-3]|[5-9]))|(17[0-9])|(18[0,5-9]))\\d{8}$/";
            if (!preg_match($regex, $this->login_name)) {
                $this->addError($attribute, '手机号码格式不正确');
            }
        }
    }

    public function verifyMail($attribute) {
        if (!$this->hasErrors()) {
            $regex = "/^[A-Za-zd]+([-_.][A-Za-zd]+)*@([A-Za-zd]+[-.])+[A-Za-zd]{2,5}$/";
            if (!preg_match($regex, $this->login_name)) {
                $this->addError($attribute, '邮箱格式不正确');
            }
        }
    }
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

    private function getPhoneCode() {
        $session = Yii::$app->session;
        return 1234;
        return $session->get('phoneCode');
    }

    private function getMailCode() {
        $session = Yii::$app->session;
        return $session->get('mailCode');
    }

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
