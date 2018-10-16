<?php

namespace backend\models;

use Yii;
use yii\base\Exception;
use common\models\Functions;

class MemberOrderImage extends Common
{
    public $uploadedImage;
    private $fieldArray = [
        "id",
        'member_order_id',
        'path',
        'type',
        'filename',
        'size',
        'created_at',
        'updated_at',
    ];

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
                    'created_at',
                    'updated_at',
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
            ['member_order_id', 'integer'],
            [['uploadedImage'], 'file', 'skipOnEmpty' => false, 'extensions' => 'jpg,png,svg,jpeg,gif'],
        ];
    }

    public function uploadImage()
    {
        try {
            $res = Functions::uploadFile('file');
            $id = Yii::$app->request->get('order_id');
            if ($res === false) {
                throw new \Exception(false);
            }
            if (!empty($id)) {
                $data['member_order_id'] = $id;
                $path_info = pathinfo($res);
                $data['path'] = $path_info['dirname'];
                $data['filename'] = $path_info['basename'];
                $data['type'] = $path_info['extension'];
                if (!($this->load($data) && $this->save())) {
                    throw new \Exception(array_shift($this->firstErrors));
                }
            } else {
                throw new \Exception(false);
            }
            return Functions::formatJson(1000, '上传成功', $res);
        } catch (Exception $e) {
            return Functions::formatJson(2000, $e->getMessage());
        }
    }

}
