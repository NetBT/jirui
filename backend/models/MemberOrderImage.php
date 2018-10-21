<?php

namespace backend\models;

use Yii;
use yii\base\Exception;
use common\models\Functions;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

class MemberOrderImage extends Common
{
    public $imageFile;
    public $attributeLabels = [
        'member_order_id',
        'path',
        'type',
        'filename',
        'size',
        'created_at',
        'updated_at',
    ];

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    public static function tableName()
    {
        return '{{%ab_member_order_images}}';
    }

    public function rules()
    {
        return [
            [
                [
                    'member_order_id',
                    'path',
                    'type',
                    'filename',
                    'size',
                    'imageFile',
                ],
                'required',
            ],
            [
                [
                    'path',
                    'type',
                    'filename',
                ],
                'string',
            ],

            ['size', 'integer'],
            ['member_order_id', 'integer'],
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'jpg, png, svg, jpeg, gif'],
        ];
    }

    public function uploadImage()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->imageFile = UploadedFile::getInstanceByName('file');
            $id = Yii::$app->request->get('order_id');
            if (!empty($id)) {
                $data['member_order_id'] = $id;
                $data['path'] = rtrim(Yii::$app->params['member_order_image_path'], '/') . '/' . $id . '/';
                $data['filename'] = md5($id . time() . rand(10000, 99999)) . '.' . $this->imageFile->extension;
                $data['type'] = $this->imageFile->type;
                $data['size'] = $this->imageFile->size;
                $this->load($data, '');
                if ($this->validate()) {
                    $path = $this->getPath();
                    Functions::mkdirs(dirname($path));
                    if (!$this->imageFile->saveAs($this->getPath())) {
                        throw new Exception($this->imageFile->error);
                    }
                    if (!$this->save(false)) {
                        throw new Exception(array_shift($this->firstErrors));
                    }
                } else {
                    throw new Exception(array_shift($this->firstErrors));
                }
            } else {
                throw new Exception('Order_id is required!');
            }
            $transaction->commit();
            return Functions::formatJson(1000, '上传成功', $this->id);
        } catch (Exception $e) {
            $transaction->rollBack();
            return Functions::formatJson(2000, $e->getMessage());
        }
    }

    public function getPath()
    {
        return Yii::getAlias('@webroot/' . $this->path . $this->filename);
    }

    public function getImageUrl()
    {
        $url = '';
        if (!$this->isNewRecord) {
            $url = '/' . $this->path . $this->filename;
        }
        return $url;
    }
}
