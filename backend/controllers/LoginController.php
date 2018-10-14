<?php
namespace backend\controllers;

use backend\models\Employee;
use common\models\Functions;
use common\models\RESTSMS;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;
/**
 * Login controller
 */
class LoginController extends Controller
{
    public $layout = false;
    public $enableCsrfValidation = true;

    /**
     * 验证码  首先需要定义captcha,对应模型中声明captcha变量
     * @return array
     */
    public function actions() {
        return [
            'captcha' =>  [
                'class' => 'yii\captcha\CaptchaAction',
                'height' => 41,//高度
                'width' => 80,//宽度
                'minLength' => 4,//最大显示个数
                'maxLength' => 4//最小显示个数
            ],
        ];
    }

    /**
     * @用户授权规则
     */
    public function behaviors()
    {
//        ? 是一个特殊的标识，代表”访客用户” @是另一个特殊标识， 代表”已认证用户”。
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout','login','register','forgetPassword'],//这里一定要加
                'rules' => [
                    [
                        'actions' => ['login','captcha'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['register','captcha'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['forgetPassword','captcha'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions'=>['logout','login'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post','get'],//同时支持GET 和 POST 两种方式访问
                ],
            ],
        ];
    }

    public function actionLogin()
    {
        $loginEmployeeModel = new Employee(['scenario' => 'loginEmployee']);
        if ($loginEmployeeModel->load(Yii::$app->request->post()) && $loginEmployeeModel->loginEmployee())
        {
            return $this->goHome();
        }
        else
        {
            return $this->render('login', [
                'model' => $loginEmployeeModel,
            ]);
        }
    }

    public function actionRegister()
    {
        if (Functions::getCommonByKey('member_register_on_off') == 2) :
            return $this->redirect(['login/login']);
        endif;
        $loginEmployeeModel = new Employee(['scenario' => 'registerEmployee']);
        if ($loginEmployeeModel->load(Yii::$app->request->post()) && $loginEmployeeModel->registerEmployee())
        {
            return $this->goHome();
        }
        else
        {
            return $this->render('register', [
                'model' => $loginEmployeeModel,
            ]);
        }
    }

    public function actionForgetPassword()
    {
        $forgetPasswordModel = new Employee(['scenario' => 'forgetPassword']);
        if ($forgetPasswordModel->load(Yii::$app->request->post()) && $forgetPasswordModel->forgetPassword())
        {
            return $this->redirect(['login/login']);
        }
        else
        {
            return $this->render('forget_password', [
                'model' => $forgetPasswordModel,
            ]);
        }
    }

    public function actionSendMessageForForget()
    {
        $model =  new RESTSMS();
        Yii::$app->response->format = Response::FORMAT_JSON;
        //检查用户名是否存在
        $phone = Yii::$app->request->post('phone');
        $userInfo = Employee::getOneByWhere(['tel' => $phone]);
        if (!$userInfo) {
            return Functions::formatJson(2000, '手机号不存在');
        }
        return $model->setCode($phone, 'forgetPassword', 251987);
    }

    /**
     * 该方法是异步校验字段，输入框失去焦点之后自动会自动请求改地址
     * @return array
     */
    public function actionValidateForm()
    {
        $type = Yii::$app->request->get('type');
        $model = new Employee(['scenario' => $type]);
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(['login/login']);
    }

}
