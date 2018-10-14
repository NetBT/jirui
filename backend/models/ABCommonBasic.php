<?php
namespace backend\models;

use common\models\Functions;
use Yii;
use dosamigos\datepicker\DatePicker;
use yii\base\Exception;
use yii\db\ActiveRecord;
use backend\models\DataTables;

/**
 * ParameterSetting model
 * 总部参数设置
 */
class ABCommonBasic extends Common
{
    private $fieldArray = [
        "name",//配置项的名称
        "value",//配置项的值
    ];

    public static function tableName()
    {
        return "{{%ab_common_basic}}";
    }


    public function _getFields()
    {
        return $this->fieldArray;
    }

    /**
     * 得到总列表
     * @return array|ActiveRecord[]
     */
    public function getList()
    {
        return self::find()->select($this->_getFields())->asArray()->all();
    }

    /**
     * 通过name获取一条数据
     * @param string $name
     * @return array|null|ActiveRecord
     */
    public function getInfoByName($name = '')
    {
        return self::find()->select($this->_getFields())->where(['name' => $name])->asArray()->one();
    }

    /**
     * 初始化处理数组
     * @return array
     */
    public function handelList()
    {
        $initList = $this->getList();
        $handelList = [];
        foreach ($initList as $key => $value)
        {
            $handelList[$value['name']] = $value['value'];
        }
        return $handelList;
    }
}
