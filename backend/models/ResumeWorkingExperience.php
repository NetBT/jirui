<?php
namespace backend\models;

use common\models\Status;
use Yii;
use yii\base\Exception;
use common\models\Functions;

class ResumeWorkingExperience extends Common
{


    public static function tableName()
    {
        return '{{%resume_working_experience}}';
    }


    /**
     * 验证规则
     */
    public function rules()
    {
        return [
            #添加
            [['company_name','post_name','start_time'], 'required','message' => '{attribute}不能为空','on' => 'addEdit'],
            [['QQ','tel','age'], 'integer','message' => '{attribute}格式不对','on' => 'addEdit'],
            ['email', 'email','message' => '{attribute}格式不对','on' => 'addEdit'],


        ];
    }

    /**
     * 设置属性名称
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'company_name' => '公司名称',
            'post_name' => '职位名称',
            'start_time' => '开始时间',
            'end_time' => '离职时间',
        ];
    }

    /**
     * 设置场景
     * @return array
     */
    public function scenarios()
    {
        $newScenarios = [
            'addEdit' => ['company_name','post_name','start_time','end_time'],
        ];
        return array_merge(parent::scenarios(), $newScenarios);
    }


    public function quoteInfo($resumeId = 0)
    {
        $info = self::getByWhere(['resume_id' => $resumeId]);
        foreach ($info as $key => $value) {
            $info[$key]['start_time'] = date('Y/m',strtotime($value['start_time']));
            $info[$key]['end_time'] = date('Y/m',strtotime($value['end_time']));
        }
        return $info;
    }


    /**
     * 编辑
     */
    public function addEdit()
    {
        $trans = Yii::$app->db->beginTransaction();
        try {
            if (!$this->validate()) {
                throw new Exception(false);
            }
            $post = Yii::$app->request->post('ResumeWorkingExperience');
            $data = [];
            if (isset($post['id']) && !empty($post['id'])) {
                $data = array_merge($data, $this->getSaveData('addEdit', $post));
                $data['update_time'] = date("Y-m-d H:i:s");
                $res = static::updateDataWithLog($data, ['id' => $post['id']]);
                $info = self::getOneInfoById(['id' => $post['id']]);
            } else {
                $data = array_merge($data, $this->getSaveData('addEdit', $post));
                $data['create_time'] =  $data['update_time'] = date("Y-m-d H:i:s");
                $data['resume_id'] =  intval($post['resume_id']);
                $res = static::insertDataWithLog($data);
                $info = self::getOneInfoById(['id' => $res]);
            }

            if ($res === false) {
                throw new Exception(false);
            }
            $info['start_time'] = date('Y/m',strtotime($info['start_time']));
            $info['end_time'] = date('Y/m',strtotime($info['end_time']));
            $info['time'] =  $info['start_time'] .'-'. $info['end_time'];
            $trans->commit();
            return Functions::formatJson(1000,'操作成功',$info);
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(1001,'操作失败');
        }
    }

    /**
     * 删除
     * @return array
     */
    public function doDelete()
    {
        $trans = Yii::$app->db->beginTransaction();
        try {
            $post = Yii::$app->request->post();
            $id = intval($post['id']);
            if (!empty($id)) {
                $res = static::deleteAll(['id' => $id]);
                if ($res === false) {
                    throw new Exception('删除失败');
                }
            } else {
                throw new Exception('请选择要删除的内容');
            }
            $trans->commit();
            return Functions::formatJson(1000, '删除成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(1001, $e->getMessage());
        }
    }

}
