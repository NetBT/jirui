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
class ParameterSetting extends Common
{
    private $fieldArray = [
        "name",//配置项的名称
        "value",//配置项的值
    ];

    public static function tableName()
    {
        return "{{%common_info}}";
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

    /**
     * 保存系统设置操作
     * @return array
     */
    public function doSave()
    {
        $data = Yii::$app->request->post();
        $oldWebLogo = self::getOneByWhere(['name' => 'web_logo'],'value');
        $wechayQRCode = self::getOneByWhere(['name' => 'wechat_qr_code'],'value');
        $alipayQRCode = self::getOneByWhere(['name' => 'alipay_qr_code'],'value');
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();//开始事务
        try{
            $data['web_logo'] = $oldWebLogo['value'];
            $data['wechat_qr_code'] = $wechayQRCode['value'];
            $data['alipay_qr_code'] = $alipayQRCode['value'];
            if(isset($_FILES['web_logo']) && !empty($_FILES['web_logo'])){
                $imagePath = Functions::uploadMultiFile('web_logo',$oldWebLogo['value']);
                if(!$imagePath) {
                    throw new Exception('LOGO上传失败', 1001);
                }
                $data['web_logo'] = $imagePath;
            }

            if(isset($_FILES['wechat_qr_code']) && !empty($_FILES['wechat_qr_code'])){
                $imagePath = Functions::uploadMultiFile('wechat_qr_code',$wechayQRCode['value']);
                if(!$imagePath) {
                    throw new Exception('微信二维码上传失败', 1001);
                }
                $data['wechat_qr_code'] = $imagePath;
            }

            if(isset($_FILES['alipay_qr_code']) && !empty($_FILES['alipay_qr_code'])){
                $imagePath = Functions::uploadMultiFile('alipay_qr_code',$alipayQRCode['value']);
                if(!$imagePath) {
                    throw new Exception('支付宝二维码上传失败', 1001);
                }
                $data['alipay_qr_code'] = $imagePath;
            }

            //循环存入系统配置
            foreach ($data as $key => $value)
            {
                $flag = self::updateAll(['value' => $value],['name' => $key]);
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
}
