<?php
namespace backend\models;

use common\models\Functions;
use common\models\Status;
use Yii;
use yii\base\Exception;

class AbSupplier extends Common
{
    private $fieldArray = [
        "id",
        "title",//标题
        "keyword",//关键词
        "description",//公告描述
        "create_time",//发布时间
        "status",//状态
    ];

    public static function tableName()
    {
        return "{{%ab_supplier}}";
    }

    public function rules()
    {
        return [
            [['name','address', 'tel', 'link_person'],'required','message' => "不能为空", 'on' => ['add', 'edit']],//两个值必须有
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => '供应商名称',
            'address' => '供应商地址',
            'tel' => '联系电话',
            'link_person' => '负责人',
        ];

    }

    /**
     * 设置场景
     * @return array
     */
    public function scenarios()
    {
        $scenarios = [
            'add' => ['name','address', 'tel', 'link_person'],
            'edit' => ['name','address', 'tel', 'link_person'],
        ];
        return array_merge(parent::scenarios(), $scenarios);
    }


    /**
     * 获取字段
     * @return array
     */
    private function _getFields() {
        return $this->fieldArray;
    }

    /**
     * 获取公告列表
     * @return array
     */
    public function getListData () {
        $returnData = [
            "draw" => intval(Yii::$app->request->post('draw')),
            "recordsTotal" => 0,
            "recordsFiltered" => 0,
            "data" => null
        ];
        $where['business_id'] = static::getBusinessId();
        //得到文章的总数（但是还没有从数据库取数据）
        $count = $this->getCountByWhere($where);
        $returnData["recordsTotal"] = $returnData['recordsFiltered'] = intval($count);
        //设置分页
        $this->setPagination();
        //需要查找的字段
        $list = static::getByWhere($where, '*', 'create_time desc', $this->_Pagination['offset'], $this->_Pagination['limit']);
        foreach ($list as $k => $v) {
        }
        $returnData['data'] = $list;
        return $returnData;
    }

    /**
     * 自定义参数的搜索  搜索,搜索也分每一列
     * 这里要根据业务逻辑进行修改
     * @param $searchForm
     * @return string
     */
    public function getSearch ($searchForm = array()) {
        $search = '';
        $createStarttime = $searchForm['starttime'];
        $createEndtime = $searchForm['endtime'];
        if(!empty($createStarttime) && empty($createEndtime))
        {

            $search .= " and create_time > '{$createStarttime}'";
        }elseif(empty($createStarttime) && !empty($createEndtime))
        {
            $search .= " and create_time < '{$createEndtime}'";
        }elseif (!empty($createStarttime) && !empty($createEndtime))
        {
            $search .= " and create_time BETWEEN '{$createStarttime}' AND '{$createEndtime}'";
        }
        return $search ;

    }

    /**
     * 进行初始化数据处理
     * @param array $listInfo
     * @return array
     */
    public function handelInit($listInfo = array())
    {
        foreach ($listInfo['data'] as $key => $value)
        {
            //公告状态
            $listInfo['data'][$key]['status'] = $this->statusArray[$value['status']];

        }
        return $listInfo;
    }

    /**
     * 切换状态  发布和未发布切换
     * @return array
     */
    public function toggleStatus()
    {
        $id = intval(Yii::$app->request->post('id'));
        $trans = Yii::$app->db->beginTransaction();
        try {
            if (empty($id)) {
                throw new Exception('公告信息错误');
            }
            $info = static::getOneByWhere(['id' => $id], ['id', 'status']);
            if (!$info || $info['status'] == Status::NOTICE_STATUS_DELETE) {
                throw new Exception('未知公告或公告已删除');
            }
            $message = '';

            if($info['status'] == Status::NOTICE_STATUS_RELEASING) {
                $data['status'] = Status::NOTICE_STATUS_NOT_RELEASE;
                $message = '已取消发布';
            } else if ($info['status'] == Status::NOTICE_STATUS_NOT_RELEASE) {
                $data['status'] = Status::NOTICE_STATUS_RELEASING;
                $message = '发布成功';
            }
            $where['id'] = $id;
            $data['update_time'] = date('Y-m-d H:i:s');
            $flag = $this->updateDataWithLog($data, $where);
            if ($flag === false) {
                throw new Exception('操作失败,请联系技术支持');
            }
            $trans->commit();
            return Functions::formatJson(1000, $message);
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(2000, $e->getMessage());
        }
    }
    /**
     * 添加公告
     * @return mixed
     */
    public function doSave()
    {
        $trans = Yii::$app->db->beginTransaction();
        try {
            if (!$this->validate()) {
                throw new Exception('校验失败');
            }
            $post = \Yii::$app->request->post(static::formName());
            $data = [];
            if (isset($post['id']) && !empty($post['id'])) {
                $data = array_merge($data, $this->getSaveData('edit', $post));
                $data['update_time'] = date("Y-m-d H:i:s");
                $res = static::updateDataWithLog($data, ['id' => $post['id']]);
                if ($res === false) {
                    throw new Exception('数据库更新失败');
                }
            } else {
                $data = array_merge($data, $this->getSaveData('add', $post));
                $data['business_id'] = static::getBusinessId();
                $data['create_time'] = date("Y-m-d H:i:s");
                $res = $this->insertDataWithLog($data);
                if ($res === false) {
                    throw new Exception('数据插入失败');
                }
            }
            $trans->commit();
            return Functions::formatJson(1000, '保存成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(2000, $e->getMessage());
        }
    }

    /**
     * 通过ID删除公告
     * @return array
     */
    public function doDelete()
    {
        $id = intval(Yii::$app->request->post('id'));
        $trans = Yii::$app->db->beginTransaction();
        try {
            if (empty($id)) {
                throw new Exception('信息错误');
            }
            $where['id'] = $id;
            $where['business_id'] = static::getBusinessId();
            $info = static::getOneByWhere($where);
            if (empty($info)) {
                throw new Exception('未找到信息');
            }
            $res = static::deleteAll($where);
            if ($res === false) {
                throw new Exception('删除失败');
            }
            $trans->commit();
            return Functions::formatJson(1000, '删除成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(2000, $e->getMessage());
        }
    }
}
