<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;

/**
 * Site controller
 */
class IndexController extends CommonController
{
    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionError() {
        return $this->render('error');
    }

    //===================交换大厅================
    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionExchanges()
    {
        return $this->render('exchange');
    }

    //===================免费大厅================
    /**
     *
     *
     * @return mixed
     */
    public function actionFreeHall()
    {
        return $this->render('free');
    }

    //=====================租赁大厅=================
    public function actionLeases()
    {
        return $this->render('lease');
    }

    public function actionLeaseDetail() {
        return $this->render('lease_detail');
    }
}
