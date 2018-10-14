<?php
namespace frontend\models;

use common\models\Common;

/**
 * 登录日志类
 * Class UserLoginLog
 * @package frontend\models
 * @author Sunjianyu
 * @date 2017-10-10
 */
class UserLoginLog extends Common
{
    /**
     * 设置表名
     */
    public static function tableName()
    {
        return '{{%user_login_log}}';
    }
}
