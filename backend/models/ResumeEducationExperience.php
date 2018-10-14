<?php
namespace backend\models;

use common\models\Status;
use Yii;
use yii\base\Exception;
use common\models\Functions;

class ResumeEducationExperience extends Common
{
    public static function tableName()
    {
        return '{{%resume_education_experience}}';
    }

    /**
     * 验证规则
     */
    public function rules()
    {
        return [
            #添加
            [['school_name','final','major'], 'required','message' => '{attribute}不能为空','on' => 'addEdit'],
        ];
    }

    /**
     * 设置属性名称
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'school_name' => '学校名称',
            'final' => '毕业时间',
            'major' => '专业',
        ];
    }

    /**
     * 设置场景
     * @return array
     */
    public function scenarios()
    {
        $newScenarios = [
            'addEdit' => ['school_name','final','major'],
        ];
        return array_merge(parent::scenarios(), $newScenarios);
    }

    public function quoteInfo($resumeId = 0)
    {
        $info = self::getByWhere(['resume_id' => $resumeId]);
        foreach ($info as $key => $value) {
            $info[$key]['final'] = date('Y/m',strtotime($value['final']));
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
                throw new Exception('数据有误');
            }
            $post = Yii::$app->request->post('ResumeEducationExperience');
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
            $info['final'] = date('Y/m',strtotime($info['final']));
            $trans->commit();
            return Functions::formatJson(1000,'操作成功',$info);
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(1001,$e->getMessage());
        }
    }

    /**
     * 删除教育经历
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
