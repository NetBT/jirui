<?php
namespace backend\models;

use common\models\Functions;
use Yii;
use yii\base\Exception;
use yii\db\ActiveRecord;

/**
 * ParameterSetting model
 * 总部参数设置
 */
class ABCommon extends Common
{

    public static function tableName()
    {
        return "{{%ab_common_info}}";
    }

    /**
     * 通过name获取一条数据
     * @param string $name
     * @return array|null|ActiveRecord
     */
    public function getInfoByName($name = '')
    {
        return self::find()->select(['name','value'])->where(['name' => $name])->asArray()->one();
    }

    /**
     * 初始化处理数组
     * @return array
     */
    public function handelList()
    {
        $businessId = static::getBusinessId();
        $initList = self::getByWhere(['business_id' => $businessId],['name','value']);
        $handelList = [];
        foreach ($initList as $key => $value)
        {
            $handelList[$value['name']] = $value['value'];
        }
        $handelList['plan_time_slot'] = json_decode($handelList['plan_time_slot'],true);
        return $handelList;
    }

    /**
     * 保存系统设置操作
     * @return array
     */
    public function doSave()
    {
        $businessId = static::getBusinessId();
        $data = Yii::$app->request->post();
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();//开始事务
        try{
            if(!$businessId){
                throw new Exception('数据错误');
            }
            //循环存入系统配置
            foreach ($data as $key => $value)
            {
                $flag = self::updateAll(['value' => $value],['name' => $key,'business_id' => $businessId]);
                if($flag === false)
                {
                    throw new Exception('保存失败');
                }
            }
            $transaction->commit();
            return Functions::formatJson(1000,'保存成功');
        }
        catch(Exception $e)
        {
            $transaction->rollBack();
            return Functions::formatJson(1001,$e->getMessage());
        }
    }

    /**
     * 保存拍摄时间操作
     * @return array
     */
    public function doSaveShootDate()
    {
        $businessId = static::getBusinessId();
        $data = Yii::$app->request->post();
        $db = Yii::$app->db;
        $insertData = '';
        $transaction = $db->beginTransaction();//开始事务
        try{
            if(!$businessId){
                throw new Exception('数据错误');
            }
            $date = json_decode($data['data'],true);
            foreach ($date as $key => $value ) {
                $insertData[$value['id']]['start'] = $value['start'];
                $insertData[$value['id']]['end'] = $value['end'];
            }
            $insertData = json_encode($insertData);
            $flag = self::updateAll(['value' => $insertData],['name' => 'plan_time_slot','business_id' => $businessId]);
            if($flag === false)
            {
                throw new Exception('保存失败');
            }

            $transaction->commit();
            return Functions::formatJson(1000,'保存成功');
        }
        catch(Exception $e)
        {
            $transaction->rollBack();
            return Functions::formatJson(1001,$e->getMessage());
        }
    }

    /**
     * 复制加盟商配置列表
     * @param int $AB_id
     *
     * @return array
     */
    public function createCommon($AB_id = 0) {
        $AB_id = intval($AB_id);
        $trans = Yii::$app->db->beginTransaction();
        try {
            if ($AB_id == 0) {
                throw new Exception('加盟商ID错误');
            }
            $basic = ABCommonBasic::getByWhere();
            foreach ($basic as $k => $v) {
                $v['business_id'] = $AB_id;
                $res = static::insertData($v);
                if ($res === false) {
                    throw new Exception($v['mark'].'配置生成失败');
                }
            }
            $trans->commit();
            return Functions::formatJson(1000, '成功');

        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(2000, $e->getMessage());
        }


    }

    public static function getTimeSlot() {
        $config = Functions::getABCommonByKey('plan_time_slot');
        $config = json_decode($config, true);
        $result = [];
        foreach ($config as $k => $v) {
            $result[ $v['start'] . '~' . $v['end']] = $v['start'] . ' ~ ' . $v['end'];
        }
        return $result;
    }
}
