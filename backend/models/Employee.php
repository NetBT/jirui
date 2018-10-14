<?php
namespace backend\models;

use common\models\Status;
use moonland\phpexcel\Excel;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Yii;
use yii\base\Exception;
use common\models\Functions;

class Employee extends Common
{
    public $statusArray = [
        Status::EMPLOYEE_STATUS_ACTIVE => '启用',
        Status::EMPLOYEE_STATUS_DELETED => '禁用'
    ];

    private $fieldArray = [
        "id",                   //主键
        "alliance_business_id", //加盟商ID
        "post_id",              //职位
        "employee_name",        //姓名
        "tel",         //电话
        "create_time",          //创建时间
        "update_time",          //更新时间
        "sex",         //性别
        "age",         //年龄
        "login_name",         //登录账号
        "status",         //状态
    ];

    public $re_password;        #确认密码
    public $new_password;       #新密码
    public $re_new_password;    #确认新密码
    public $rememberMe ;        #记住我
    public $code = null;        #验证码
    public $phone_code = null;        #手机验证码
    private $_employee;


    public static function tableName()
    {
        return '{{%employee_info}}';
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
            #登录
            [['login_name','password'], 'required','message' => '{attribute}不能为空','on' => 'loginEmployee'],
            // 自定义validatePassword()函数验证密码
            ['password', 'validatePassword','on' => 'loginEmployee'],
            // 记住我是一个boolean值
            ['rememberMe', 'boolean','on' => 'loginEmployee'],

            #添加
            [['login_name','password', 're_password'], 'required', 'message' => '{attribute}不能为空', 'on' => 'addEmployee'],
            ['login_name', 'validLoginNameUnique', 'message' => '{attribute}已存在', 'on' => 'addEmployee'],
            ['photographer_color', 'validPhotographerColor', 'on' => 'addEmployee'],
            ['re_password', 'compare', 'compareAttribute' => 'password', 'message' => '密码不一致', 'on' => 'addEmployee'],
            ['address', 'required','message' => '{attribute}不能为空','on' => 'addEmployee'],
            ['email', 'email','message' => '{attribute}格式不对','on' => 'addEmployee'],
            [['QQ','tel','age'], 'number','message' => '{attribute}格式不对','on' => 'addEmployee'],
//            ['post_id', 'required','message' => '{attribute}不能为空','on' => 'addEmployee'],
            ['tel', 'validTelUnique','message' => '{attribute}已存在','on' => 'addEmployee'],
            ['tel', 'string', 'min' => 11, 'max' => 11,"tooLong"=>"手机号有误", "tooShort"=>"手机号有误",'on' => 'addEmployee'],
            ['QQ', 'string', 'min' => 6, 'max' => 12,"tooLong"=>"QQ有误", "tooShort"=>"QQ有误",'on' => 'addEmployee'],
            ['age', 'string', 'min' => 1, 'max' => 2,"tooLong"=>"年龄有误", "tooShort"=>"年龄有误",'on' => 'addEmployee'],
            ['address', 'string','min' => 0,'max' => 40,"tooLong"=>"限制40字", "tooShort"=>"请输入地址",'on' => 'addEmployee'],

            #修改密码
            ['password','required','message' => '{attribute}不能为空','on' => 'editPwd'],
            ['password','validEditPassword','on' => 'editPwd'],
            [['new_password','re_new_password'],'required','message' => '{attribute}不能为空','on' => 'editPwd'],
            ['re_new_password', 'compare', 'compareAttribute' => 'new_password', 'message' => '密码不一致', 'on' => 'editPwd'],

            #编辑
            ['login_name', 'required', 'message' => '{attribute}不能为空', 'on' => 'editEmployee'],
            ['login_name', 'validLoginNameUniqueForEdit', 'message' => '{attribute}已存在', 'on' => 'editEmployee'],
            ['address', 'required','message' => '{attribute}不能为空','on' => 'editEmployee'],
            ['email', 'email','message' => '{attribute}格式不对','on' => 'editEmployee'],
            [['QQ','tel','age'], 'number','message' => '{attribute}格式不对','on' => 'editEmployee'],
//            ['post_id', 'required','message' => '{attribute}不能为空','on' => 'editEmployee'],
            ['tel', 'validTelUniqueForEdit','message' => '{attribute}已存在','on' => 'editEmployee'],
            ['tel', 'string', 'min' => 11, 'max' => 11,"tooLong"=>"手机号有误", "tooShort"=>"手机号有误",'on' => 'editEmployee'],
            ['QQ', 'string', 'min' => 6, 'max' => 12,"tooLong"=>"QQ有误", "tooShort"=>"QQ有误",'on' => 'editEmployee'],
            ['age', 'string', 'min' => 1, 'max' => 2,"tooLong"=>"年龄有误", "tooShort"=>"年龄有误",'on' => 'editEmployee'],
            ['address', 'string','min' => 0,'max' => 40,"tooLong"=>"限制40字", "tooShort"=>"请输入地址",'on' => 'editEmployee'],

            #游客修改个人信息
            ['employee_name', 'required', 'message' => '{attribute}不能为空', 'on' => 'editBySelf'],
            ['address', 'required','message' => '{attribute}不能为空','on' => 'editBySelf'],
            ['email', 'email','message' => '{attribute}格式不对','on' => 'editBySelf'],
            [['QQ','tel','age'], 'number','message' => '{attribute}格式不对','on' => 'editBySelf'],
            ['tel', 'validTelUnique','message' => '{attribute}已存在','on' => 'editBySelf'],
            ['tel', 'string', 'min' => 11, 'max' => 11,"tooLong"=>"手机号有误", "tooShort"=>"手机号有误",'on' => 'editBySelf'],
            ['QQ', 'string', 'min' => 6, 'max' => 12,"tooLong"=>"QQ有误", "tooShort"=>"QQ有误",'on' => 'editBySelf'],
            ['age', 'string', 'min' => 1, 'max' => 2,"tooLong"=>"年龄有误", "tooShort"=>"年龄有误",'on' => 'editBySelf'],
            ['address', 'string','min' => 0,'max' => 40,"tooLong"=>"限制40字", "tooShort"=>"请输入地址",'on' => 'editBySelf'],

            #注册
            [['login_name','password'], 'required','message' => '{attribute}不能为空','on' => 'registerEmployee'],
            ['login_name', 'validLoginNameUnique', 'message' => '{attribute}已存在', 'on' => 'registerEmployee'],
            ['re_password', 'compare', 'compareAttribute' => 'password', 'message' => '密码不一致', 'on' => 'registerEmployee'],
            ['code', "required", "message" => '{attribute}不能为空', "on" => "registerEmployee"],
            ['code', "captcha", 'captchaAction'=>'login/captcha','message'=>'验证码不正确', "on" => "registerEmployee" ],
            ['tel', 'validTelUnique','message' => '{attribute}已存在','on' => 'registerEmployee'],
            ['tel', 'string', 'min' => 11, 'max' => 11,"tooLong"=>"手机号有误", "tooShort"=>"手机号有误",'on' => 'registerEmployee'],

            #忘记密码
            [['login_name','new_password','re_new_password','tel'],'required','message' => '{attribute}不能为空','on' => 'forgetPassword'],
            ['login_name','validLoginName','message' => '{attribute}不存在','on' => 'forgetPassword'],
            ['re_new_password', 'compare', 'compareAttribute' => 'new_password', 'message' => '密码不一致', 'on' => 'forgetPassword'],
            ['tel','validTel','message' => '{attribute}错误','on' => 'forgetPassword'],
            ['tel', 'string', 'min' => 11, 'max' => 11,"tooLong"=>"手机号有误", "tooShort"=>"手机号有误",'on' => 'forgetPassword'],
            ['phone_code','validateForgetCode','message' => '{attribute}有误','on' => 'forgetPassword'],
        ];
    }

    /**
     * 设置属性名称
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'rememberMe' => '记住我',
            'login_name' => '账号',
            'password' => '密码',
            're_password' => '确认密码',
            'new_password' => '新密码',
            're_new_password' => '确认密码',
            'employee_name' => '姓名',
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
            'post_id' => '职位',
            'working_status' => '职位情况',
            'marriage' => '婚姻状况',
            'working_duration' => '工作年限',
            'expected_salary' => '期望薪资',
            'code' => '验证码',
            'phone_code' => '手机验证码',
            'photographer_color' => '标志颜色',
        ];
    }

    /**
     * 设置场景
     * @return array
     */
    public function scenarios()
    {
        $newScenarios =  [
            'loginEmployee' => ['login_name','password','rememberMe'],
            'registerEmployee' => ['login_name','password','re_password','code'],
            'forgetPassword' => ['login_name','tel','new_password','re_new_password','phone_code'],
            'editPwd' => [
                'password',
                're_new_password',
                'new_password',
                'id'
            ],
            'editEmployee' => [
                'id',
                'login_name',
                'employee_name',
                'sex',
                'tel',
                'birthday',
                'wechat',
                'age',
                'QQ',
                'nation',
                'degree',
                'email',
                'province',
                'school',
                'address',
                'post_id',
            ],
            'addEmployee' => [
                'login_name',
                'employee_name',
                'password',
                're_password',
                'sex',
                'tel',
                'birthday',
                'wechat',
                'age',
                'QQ',
                'nation',
                'degree',
                'email',
                'province',
                'school',
                'address',
                'post_id',
                'photographer_color',
            ],
            'editBySelf' => [
                'employee_name',
                'sex',
                'wechat',
                'tel',
                'birthday',
                'age',
                'QQ',
                'nation',
                'degree',
                'email',
                'province',
                'school',
                'address',
                'marriage',
                'working_status',
                'working_duration',
                'expected_salary',
            ],
            'addJMS' => ['password','re_new_password','new_password','employeeId'],
        ];
        return array_merge(parent::scenarios(), $newScenarios);
    }

    /**
     * 登录用户使用提供的用户名和密码。
     *
     * @return bool 返回一个boolean,是否登录成功
     */
    public function loginEmployee()
    {
        if ($this->validate()) {
            //更新employee_login_log
            if($this->updateLogin($this->login_name))
            {
                // 校验成功，session保存用户信息
                return Yii::$app->user->login($this->getEmployee(), $this->rememberMe ? 3600 * 24 * 30 : 0);
            }
        } else {
            return false;
        }
    }

    /**
     * 注册用户
     *
     * @return bool 返回一个boolean,是否登录成功
     */
    public function registerEmployee()
    {
        if ($this->validate()) {
            //更新employee_login_log
            if($this->doRegister($this->login_name))
            {
                return true;
            }
        } else {
            return false;
        }
    }

    /**
     * 登录用户使用提供的用户名和密码。
     *
     * @return bool 返回一个boolean,是否登录成功
     */
    public function forgetPassword()
    {
        if ($this->validate()) {
            //更新employee_login_log
            if($this->doForgetPassword($this->tel))
            {
                // 校验成功，session保存用户信息
                return Yii::$app->user->login($this->getEmployee(), $this->rememberMe ? 3600 * 24 * 30 : 0);
            }
        } else {
            return false;
        }
    }

    /**
     * 验证密码
     * 该方法作为内部验证密码
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $employee = $this->getEmployee();

            if($this->login_name !== $employee->login_name) {
                $this->addError('login_name', '账号填写错误.');
            }

            if (!$employee || !$employee->validatePassword($this->password)) {
                $this->addError($attribute, '账号或密码错误.');
            }
            //是否是启用状态
            if(!$this->validateStatus($this->login_name))
            {
                $this->addError($attribute,'账号已被禁用，请联系管理员.');
            }
            $result = AB::checkABInfo($this->login_name);
            if ($result['isLock']['status'] === true) {
                $this->addError($attribute, $result['isLock']['message']);
            }
            if ($result['isStart']['status'] === false) {
                $this->addError($attribute, $result['isStart']['message']);
            }
            if ($result['isEnd']['status'] === true) {
                $this->addError($attribute, $result['isEnd']['message']);
            }
            $isClose = Functions::getCommonByKey('web_close_on_off');
            if ($isClose == 2) {
                $this->addError($attribute, '因'. Functions::getCommonByKey('web_close_reason') . ', 网站关闭');
            }
        }
    }

    /**
     * 通过login_name获取admin的对象
     *
     * @return Admin|null
     */
    protected function getEmployee()
    {
        if ($this->_employee === null) {
            $this->_employee = EmployeeLogin::findByLoginName($this->login_name);
        }
        return $this->_employee;
    }

    /**
     * 验证状态
     * @param $login_name
     * @return bool
     */
    public function validateStatus($login_name)
    {
        $statusArray = static::getOneByWhere(['login_name' => $login_name],'status');
        if(intval($statusArray['status']) !== Status::EMPLOYEE_STATUS_ACTIVE)
        {
            return false;
        }
        return true;
    }

    /**
     * 登录之后更改状态，然后把登录信息存入登录日志表中
     * @param string $loginName
     * @return bool
     */
    public function updateLogin($loginName = '')
    {
        $publicInfo = new Functions();
        $updateInfo['last_login_time'] = date("Y-m-d H:i:s",time());
        $updateInfo['last_login_ip'] = Yii::$app->getRequest()->getUserIP();

        $addLogInfo['login_name'] = $loginName;
        $addLogInfo['login_time'] = date("Y-m-d H:i:s",time());
        $addLogInfo['login_ip'] = Yii::$app->getRequest()->getUserIP();
        $addLogInfo['login_explorer'] = $_SERVER['HTTP_USER_AGENT'];
        //$positionInfo = $publicInfo->GetIpLookup(Yii::$app->getRequest()->getUserIP());
        //$addLogInfo['login_position'] = $positionInfo['country'].$positionInfo['province'].$positionInfo['city'];
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();//开始事务
        try{
            //先更新管理员表
            $employeeFlag = static::updateData($updateInfo,['login_name' => $loginName]);
            //再添加管理员登录日志
            $employeeLogFlag = EmployeeLoginLog::insertData($addLogInfo);
            if($employeeFlag && $employeeLogFlag)
            {
                $transaction->commit();
                return true;
            }
        }
        catch(Exception $e)
        {
            return false;
        }
        return true;
    }

    /**
     * 注册用户
     * @param string $loginName
     * @return bool
     */
    public function doRegister($loginName = '')
    {
        $publicInfo = new Functions();
        //添加employee信息
        $addEmployeeInfo['login_name'] = $loginName;
        $addEmployeeInfo['password'] = md5($this->password);
        $addEmployeeInfo['last_login_ip'] = Yii::$app->getRequest()->getUserIP();
        $addEmployeeInfo['last_login_time'] = date("Y-m-d H:i:s",time());
        $addEmployeeInfo['alliance_business_id'] = '';//注册进来的都是空
        $addEmployeeInfo['create_time'] = date("Y-m-d H:i:s",time());

        //更新日志表
        $addLogInfo['login_name'] = $loginName;
        $addLogInfo['login_time'] = date("Y-m-d H:i:s",time());
        $addLogInfo['login_ip'] = Yii::$app->getRequest()->getUserIP();
        $addLogInfo['login_explorer'] = $_SERVER['HTTP_USER_AGENT'];
        $positionInfo = $publicInfo->GetIpLookup(Yii::$app->getRequest()->getUserIP());
        $addLogInfo['login_position'] = $positionInfo['country'].$positionInfo['province'].$positionInfo['city'];
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();//开始事务
        try{
            //先添加employee
            $employeeFlag = static::insertDataWithLog($addEmployeeInfo);
            //再添加登录日志
            $employeeLogFlag = EmployeeLoginLog::insertData($addLogInfo);
            if($employeeFlag && $employeeLogFlag)
            {
                $transaction->commit();
                return true;
            }
        }
        catch(Exception $e)
        {
            return false;
        }
        return true;
    }

    /**
     *  忘记密码->找回密码
     * @param string $tel
     * @return bool
     */
    public function doForgetPassword($tel = '')
    {
        $editData['password'] = md5($this->new_password);
        $editData['update_time'] = date("Y-m-d H:i:s",time());
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();//开始事务
        try{
            $employeeFlag = static::updateDataWithLog($editData,['tel' => $tel]);
            if($employeeFlag === false)
            {
                throw new Exception(false);
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
     * 自定义比较原密码是否正确
     * @param $attribute
     * @param $params
     */
    public function validEditPassword($attribute, $params)
    {
        $employeeId = intval($this->id);
        $employeeInfo = static::getOneInfoById($employeeId, 'password');
        if(md5($this->password) !== $employeeInfo['password'])
        {
            $this->addError($attribute, '密码填写错误.');
        }
    }

    /**
     * 添加账号验证是否被注册过
     * @param $attribute
     * @param $params
     */
    public function validLoginNameUnique($attribute, $params)
    {
        $employeeInfo = static::getOneByWhere(['login_name' => $this->login_name],'id');
        if($employeeInfo)
        {
            $this->addError($attribute, '账号已被占用.');
        }
    }
    /**
     * 修改账号时。判断账号是否存在
     * @param $attribute
     * @param $params
     */
    public function validLoginNameUniqueForEdit($attribute, $params)
    {
        $employeeInfo = static::getOneByWhere(['login_name' => $this->login_name],'id');
        if($employeeInfo['id'] != $this->id)
        {
            $this->addError($attribute, '账号已被占用.');
        }
    }

    /**
     * 账号是否存在
     * @param $attribute
     * @param $params
     */
    public function validLoginName($attribute, $params)
    {
        $employeeInfo = static::getOneByWhere(['login_name' => $this->login_name],'id');
        if(!$employeeInfo)
        {
            $this->addError($attribute, '账号不存在.');
        }
    }

    /**
     * 手机号是否存在
     * @param $attribute
     * @param $params
     */
    public function validTel($attribute, $params)
    {
        $telInfo = static::getOneByWhere(['login_name' => $this->login_name],'tel');
        if($telInfo['tel'] != $this->tel)
        {
            $this->addError($attribute, '未绑定该手机号.');
        }
    }

    /**
     * 手机号是否存在
     * @param $attribute
     * @param $params
     */
    public function validTelUnique($attribute, $params)
    {
        $telInfo = static::getOneByWhere(['tel' => $this->tel],'tel');
        if($telInfo)
        {
            $this->addError($attribute, '已存在.');
        }
    }

    /**
     * 手机号是否存在
     * @param $attribute
     * @param $params
     */
    public function validTelUniqueForEdit($attribute, $params)
    {
        $telInfo = static::getOneByWhere(['tel' => $this->tel], ['tel', 'id']);
        if ($telInfo['id'] != $this->id) {
            $this->addError($attribute, '已存在.');
        }
    }

    /**
     * 验证手机验证码
     * @param $attribute
     */
    public function validateForgetCode($attribute) {
        if (!$this->hasErrors()) {
            $code = $this->getPhoneCode('forgetPassword');
            if (!$code) {
                $this->addError($attribute, '请获取验证码');
            } else if ($code['expire_time'] < time()) {
                $this->addError($attribute, '验证码已失效');
            } else if ($code['phone'] != $this->tel) {
                $this->addError($attribute, '验证码不属于此手机');
            } else if ($code['code'] != $this->phone_code) {
                $this->addError($attribute, '验证码错误');
            }
        }
    }

    /**
     * 判断摄影师背景颜色
     * @param $attribute
     */
    public function validPhotographerColor($attribute)
    {
        $postId = $this->post_id;
        $typeInfo = intval(EmployeePost::getInfoByField($postId,'type'));
        if($typeInfo == Status::EMPLOYEE_POST_TYPE_SHEYING) {
            $color = $this->photographer_color;
            if(!$color) {
                $this->addError($attribute, '不能为空');
            }
            $res = self::getOneByWhere(['alliance_business_id' => Common::getBusinessId(),'post_id' => $postId, 'photographer_color' => $color]);
            if($res) {
                $this->addError($attribute, '已存在');
            }
        }
    }

    /**
     * 获取手机验证码
     * @return int
     */
    private function getPhoneCode($sessionKey = 'registerCode') {
        $session = Yii::$app->session;
        return $session->get($sessionKey);
    }

    /**
     * 返回adminList数据
     * @return array
     */
    public function getListData () {
        $returnData = [
            "draw" => intval(Yii::$app->request->post('draw')),
            "recordsTotal" => 0,
            "recordsFiltered" => 0,
            "data" => null
        ];
        //搜索条件
        $searchWhere  = $this->getSearch(Yii::$app->request->post('extra_search'));//自定义搜索条件

        //得到文章的总数（但是还没有从数据库取数据）
        if(isset($searchWhere['andWhere'])) {
            $count = static::getCountByWhereAndWhere($searchWhere['where'],$searchWhere['andWhere']);
        } else {
            $count = static::getCountByWhere($searchWhere);
        }
        $returnData["recordsTotal"] = $returnData['recordsFiltered'] = intval($count);

        //设置分页
        $this->setPagination();
        //最终返回的data数据
        //需要查找的字段
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
        $businessId = Common::getBusinessId();
        $where['alliance_business_id'] = Common::getBusinessId();
        if($businessId) {
            $where['is_working'] = Status::EMPLOYEE_IS_WORKING;
        }
        if(!empty($search)){
            $tel = $search['tel'];
            if($tel)
            {
                $where = ['like','tel',$tel];
            }
        }
        return [
            'where' => $where,
            'andWhere' => $andWhere
        ];
    }


    public function handelList($list)
    {
        $abInfo = AB::getByWhere('','id,AB_name');
        $abList = Functions::extractKey($abInfo,'id','AB_name');
        $employeePostList = EmployeePost::getFormList();
        $nationInfo = Nation::getFormArray('','id','name');
        if(!empty($list['data']))
        {
            foreach($list['data'] as $key => $value)
            {
                $list['data'][$key]['status'] = $this->statusArray[$value['status']];
                $list['data'][$key]['sex'] = $value['sex'] ? Status::sexyMap()[$value['sex']] : '--';
                $list['data'][$key]['age'] = $value['age'] ? $value['age'] : '--';
                $list['data'][$key]['tel'] = $value['tel'] ? $value['tel'] : '--';
                $list['data'][$key]['alliance_business_id'] = $value['alliance_business_id'] == 1 ? '总部' : $abList[$value['alliance_business_id']];
                $list['data'][$key]['post_id'] = empty($value['post_id']) ? '--' : $employeePostList[$value['post_id']];
                $list['data'][$key]['nation'] = empty($value['nation']) ? '--' : $nationInfo[$value['nation']];
                $list['data'][$key]['degree'] = empty($value['degree']) ? '--' : Status::degreeMap()[$value['degree']];
            }
        }
        return $list;
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
                $afterStatus = Status::EMPLOYEE_STATUS_ACTIVE;
                break;
            case '禁用':
                $afterStatus = Status::EMPLOYEE_STATUS_DELETED;
                break;
        }

        $db = Yii::$app->db;
        $trans = $db->beginTransaction();
        $flag = self::updateDataWithLog(['status' => $afterStatus],["id" => $id]);

        if($flag === false)
        {
            $trans->rollBack();
            return Functions::formatJson(1001,$status.'失败');
        }
        else
        {
            $trans->commit();
            return Functions::formatJson(1000,$status.'成功');
        }
    }

    /**
     * 修改密码
     * @return array
     */
    public function updatePassword()
    {
        $data = Yii::$app->request->post('Employee');
        $where['id'] = intval($data['id']);
        $updateInfo['password'] = md5(trim($data['new_password']));
        $updateInfo['update_time'] = date('Y-m-d H:i:s');
        $db = Yii::$app->db;
        $trans = $db->beginTransaction();
        $flag = static::updateDataWithLog($updateInfo, $where);
        if($flag !== false)
        {
            $trans->commit();
            return Functions::formatJson(1000,'修改成功');
        }
        $trans->rollBack();
        return Functions::formatJson(1001,'修改失败');
    }

    public static function getFormList() {
        $where['status'] = Status::EMPLOYEE_STATUS_ACTIVE;
        $where['alliance_business_id'] = 1;
        $list = static::getByWhere($where, ['id', 'employee_name', 'post_id']);
        return Functions::extractKey($list, 'id', 'employee_name');
    }


    public static function getFormListForAB($showRole = false) {
        $where['status'] = Status::EMPLOYEE_STATUS_ACTIVE;
        $where['alliance_business_id'] = static::getBusinessId();
        $list = static::getByWhere($where, ['id', 'employee_name', 'post_id']);
        if ($showRole) {
            $postWhere['business_id'] = static::getBusinessId();
            $postWhere['status'] = Status::EMPLOYEE_POST_SUCCESS;
            $postList = EmployeePost::getByWhere($postWhere, ['id', 'post_name']);
            $postMap = Functions::extractKey($postList, 'id', 'post_name');
            foreach ($list as $k => $v) {
                $list[$k]['employee_name'] = $v['employee_name'] . '('. $postMap[$v['post_id']] .')';
            }
        }

        return Functions::extractKey($list, 'id', 'employee_name');
    }

    /**
     * 获取助理
     * @param bool $showRole
     *
     * @return array
     */
    public static function getFormListFormByType($showRole = false, $type = 0) {
        //获取角色信息
        $roleWhere['type'] = $type;
        $roleWhere['business_id'] = static::getBusinessId();
        $role = EmployeePost::getOneByWhere($roleWhere, ['id', 'type']);
        $where['status'] = Status::EMPLOYEE_STATUS_ACTIVE;
        $where['alliance_business_id'] = static::getBusinessId();
        $where['post_id'] = $role['id'];
        $list = static::getByWhere($where, ['id', 'employee_name', 'post_id']);
        if ($showRole) {
            foreach ($list as $k => $v) {
                $list[$k]['employee_name'] = $v['employee_name'] . '('. Status::employeePostTypeMap()[$type] .')';
            }
        }
        return Functions::extractKey($list, 'id', 'employee_name');
    }

    public static function getHeadquartersEmployeeMap() {
        $list = static::getByWhere('alliance_business_id is NULL', ['id', 'employee_name']);
        return Functions::extractKey($list, 'id', 'employee_name');
    }

    /**
     * 添加员工信息
     */
    public function addEditEmployee()
    {
        $trans = Yii::$app->db->beginTransaction();
        $post = Yii::$app->request->post('Employee');
        try {
            if (!$this->validate()) {
                throw new Exception('数据有误');
            }
            $data = [];
            if (isset($post['id']) && !empty($post['id'])) {
                $data = array_merge($data, $this->getSaveData('editEmployee', $post));
                $data['update_time'] = date("Y-m-d H:i:s");
                if(array_key_exists('id', $data)) unset($data['id']);
                $res = static::updateDataWithLog($data, ['id' => $post['id']]);
            } else {
                $data = array_merge($data, $this->getSaveData('addEmployee', $post));
                $data['alliance_business_id'] = Common::getBusinessId();
                $data['create_time'] =  $data['update_time'] = date("Y-m-d H:i:s");
                $data['password'] = md5($data['password']);
                $res = static::insertDataWithLog($data);
            }

            if ($res === false) {
                throw new Exception(false);
            }
            $trans->commit();
            return Functions::formatJson(1000,'操作成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(2000,$e->getMessage());
        }
    }

    /**
     * 加盟商添加员工信息
     * @return array
     */
    public function addABEmployee()
    {
        $trans = Yii::$app->db->beginTransaction();
        $post = Yii::$app->request->post('Employee');
        try {
            $id = intval($post['id']);
            if(!$id) {
                throw new Exception('未获取到员工信息');
            }

            $info = self::getOneInfoById($id);
            if(!empty($info['alliance_business_id']) && $info['is_working'] == Status::EMPLOYEE_IS_WORKING) {
                throw new Exception('改员工正在其他加盟商工作中，不能添加，请选择其他员工');
            }

            $data = [];
            $data['update_time'] = date("Y-m-d H:i:s");
            $data['alliance_business_id'] = Common::getBusinessId();
            $data['post_id'] = intval($post['post_id']);
            $data['is_working'] = Status::EMPLOYEE_IS_WORKING;
            $data['photographer_color'] = trim($post['photographer_color']);

            $res = static::updateDataWithLog($data, ['id' => $id]);
            if ($res === false) {
                throw new Exception('入职失败');
            }
            $trans->commit();
            return Functions::formatJson(1000,'操作成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(2000,$e->getMessage());
        }
    }

    /**
     * 自己修改个人信息
     * @return array
     */
    public function editBySelf()
    {
        $trans = Yii::$app->db->beginTransaction();
        try {
            if (!$this->validate()) {
                throw new Exception('验证失败');
            }
            $post = Yii::$app->request->post('Employee');
            $data = [];
            if (isset($post['id']) && !empty($post['id'])) {
                $data = array_merge($data, $this->getSaveData('editBySelf', $post));
                $data['update_time'] = date("Y-m-d H:i:s");
                $res = static::updateDataWithLog($data, ['id' => $post['id']]);
            } else {
                throw new Exception('未查到该用户');
            }

            if ($res === false) {
                throw new Exception(false);
            }
            $trans->commit();
            return Functions::formatJson(1000,'操作成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(1001,$e->getMessage());
        }
    }

    public static function getEmployeeNameById($id = [], $field = '') {
        if (empty($id)) {
            return [];
        }
        $user = static::getByWhere(['id' => $id], ['id', $field]);
        return Functions::extractKey($user, 'id', $field);
    }


    //获取摄影师背景颜色
    public static function getPhotographerColor()
    {
        //每个摄影师的颜色是唯一的
        $employeeInfo = self::getFormArray(['status' => Status::EMPLOYEE_STATUS_ACTIVE,'alliance_business_id' => Common::getBusinessId()],'id','photographer_color');

        $photographerColorInfo = Status::photographerColorMap();
        foreach ($employeeInfo as $key => $value) {
            if(array_key_exists($value,$photographerColorInfo)) {
                unset($photographerColorInfo[$value]);
            }
        }

        foreach ($photographerColorInfo as $key => $value) {
            $photographerColorInfo[$key] = '<span class="text" style="height: 20px;background-color: '.$value.'"></span>';
        }
        return $photographerColorInfo;

    }


    /**
     * 获取单个员工信息
     * @param array $where
     * @return array
     */
    public function getEmployeeOneInfo($where = [])
    {
        if(empty($where)) {
            return Functions::formatJson(2001,'请填写名称');
        }
        $info = self::getOneByWhere($where);
        if(empty($info)) {
            return Functions::formatJson(2002,'未获取到相关信息');
        }

        $nationInfo = Nation::getFormArray('','id','name');

        $info['nation'] = $nationInfo[$info['nation']];
        $info['sex'] = Status::sexyMap()[$info['sex']];
        $info['degree'] = Status::degreeMap()[$info['degree']];

        return Functions::formatJson(1000,'',$info);
    }


    /**
     * 对员工进行离职操作
     * @return array
     */
    public function WorkingStatus()
    {
        $id = intval(Yii::$app->request->post('id'));
        if(!$id) {
            return Functions::formatJson(2001,'未获取到员工信息');
        }

        $data['is_working'] = Status::EMPLOYEE_NO_WORKING;
        $data['alliance_business_id'] = '';
        $data['update_time'] = date('Y-m-d H:i:s');

        $res = self::updateDataWithLog($data,['id' => $id]);
        if($res === false) {
            return Functions::formatJson(2002,'操作失败');
        }

        return Functions::formatJson(1000,'离职成功');
    }

    /**
     * 导出excel
     */
    public function exportExcel()
    {
        $list = [];
        $where['alliance_business_id'] = Common::getBusinessId();
        $list['data'] = self::find()->where($where)->asArray()->all();
        $list = $this->handelList($list);
        $list = $list['data'];
        Excel::export([
            'models' => $list,
            'fileName' => date('Ymd').'导出员工信息',
            'columns' => [
                'alliance_business_id',
                'employee_name',
                'post_id',
                'sex',
                'age',
                'tel',
                'degree',
                'wechat',
                'QQ',
                'email',
                'birthday',
                'nation',
                'province',
                'school',
                'address',
            ], //没有头工作,因为头会得到标签的属性标签
            'headers' => [
                'alliance_business_id' => '商户号',
                'employee_name' => '姓名',
                'post_id' => '职位',
                'sex' => '性别',
                'age' => '年龄',
                'tel' => '电话',
                'degree' => '学历',
                'wechat' => '微信',
                'QQ' => 'QQ',
                'email' => '邮箱',
                'birthday' => '生日',
                'nation' => '民族',
                'province' => '省份',
                'school' => '学校',
                'address' => '地址',
            ],
        ]);
    }
}


