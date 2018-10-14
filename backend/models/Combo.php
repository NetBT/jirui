<?php
namespace backend\models;

use common\models\Status;
use Yii;
use yii\base\Exception;
use common\models\Functions;

class Combo extends Common
{

    private $fieldArray = [
        "id",           //主键
        "business_id",  //商户ID
        "combo_name",   //套系名称
        "combo_price",    //套系价格
        "register_count", //入底入册
        "goods_content",  //商品列表
        "combo_discount", //套系折扣
        "combo_integral", //套系积分
        "mark",           //备注
        "combo_clothing", //服装造型
        "combo_content",  //成长套系包含的套系
        "create_time",    //创建时间
        "combo_type",     //套系类型
        "is_delete",      //是否删除
    ];

    public static function tableName()
    {
        return '{{%ab_combo_info}}';
    }

    /**
     * 获取字段
     * @return array
     */
    private function _getFields() {
        return $this->fieldArray;
    }

    /**
     * 验证规则
     */
    public function rules()
    {
        return [
            #普通套系
            [['combo_name','combo_price'], 'required','message' => '{attribute}不能为空','on' => 'generalCombo'],
            [['combo_price'], 'double','message' => '{attribute}格式不对','on' => 'generalCombo'],
            [['combo_integral','register_count'], 'integer','message' => '{attribute}格式不对','on' => 'generalCombo'],
            ['combo_discount', 'validComboDiscount','message' => '{attribute}格式不对','on' => 'generalCombo'],

            #成长套系
            [['combo_name','combo_price'], 'required','message' => '{attribute}不能为空','on' => 'growCombo'],
            ['combo_price', 'double','message' => '{attribute}格式不对','on' => 'growCombo'],
            ['combo_integral', 'integer','message' => '{attribute}格式不对','on' => 'growCombo'],
            ['combo_discount', 'validComboDiscount','message' => '{attribute}格式不对','on' => 'growCombo'],
        ];
    }

    /**
     * 设置属性名称
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'combo_name' => '套系名称',
            'combo_price' => '套系价格',
            'register_count' => '入底入册',
            'goods_content' => '商品列表',
            'combo_discount' => '套系折扣',
            'combo_integral' => '套系积分',
            'combo_clothing' => '服装造型',
            'mark' => '备注',
            'combo_content' => '套系列表',
        ];
    }

    /**
     * 设置场景
     * @return array
     */
    public function scenarios()
    {
        $newScenarios = [
            'generalCombo' => ['combo_name','combo_price','register_count','goods_content','combo_discount','combo_integral','mark','combo_clothing'],
            'growCombo' => ['combo_name','combo_price','combo_content','combo_discount','combo_integral','mark'],
        ];
        return array_merge(parent::scenarios(), $newScenarios);
    }

    /**
     * 验证折扣格式
     * @param $attribute
     * @param $params
     */
    public function validComboDiscount($attribute, $params)
    {
        $comboDiscount = $this->combo_discount;
        $currArr = explode(',',$comboDiscount);
        foreach ($currArr as $key => $value) {
            if ($value < 0 || $value >= 1) {
               $this->addError($attribute,'格式错误');
            }
        }

    }

    public function getListData($type = 1)
    {
        $returnData = [
            "draw" => intval(Yii::$app->request->post('draw')),
            "recordsTotal" => 0,
            "recordsFiltered" => 0,
            "data" => null
        ];
        //搜索条件
        $searchWhere  = $this->getSearch(Yii::$app->request->post('extra_search'), $type);//自定义搜索条件

        //得到文章的总数（但是还没有从数据库取数据）
        if(isset($searchWhere['andWhere'])){
            $count = self::getCountByAndWhere($searchWhere['where'], $searchWhere['andWhere']);
        } else {
            $count = self::getCountByWhere($searchWhere);
        }
        $returnData["recordsTotal"] = $returnData['recordsFiltered'] = intval($count);

        //设置分页
        $this->setPagination();

        $selectField = "";
        $fields = $this->_getFields();
        foreach($fields as $key => $value)
        {
            $selectField .= ",".$value;
        }
        $selectField = ltrim($selectField,',');
        //排序 order
        $orderSql = 'id ASC';
        if(isset($searchWhere['andWhere'])){
            $returnData['data'] = static::getByAndWhere($searchWhere['where'],$searchWhere['andWhere'], $selectField, $orderSql, $this->_Pagination['offset'], $this->_Pagination['limit']);
        } else {
            $returnData['data'] = static::getByWhere($searchWhere, $selectField, $orderSql, $this->_Pagination['offset'], $this->_Pagination['limit']);
        }
        return $returnData;
    }

    /**
     * 自定义参数的搜索  搜索,搜索也分每一列
     * 这里要根据业务逻辑进行修改
     * @param array $search
     * @param int $type
     * @return array
     */
    public function getSearch ($search = [], $type = 1)
    {
        $where = [];
        $andWhere = [];
        $where['business_id'] = Common::getBusinessId();
        $where['is_delete'] = Status::COMBO_NOT_DELETE;
        $where['combo_type'] = $type;
        if(!empty($search)){
            $name = isset($search['name']) ? $search['name'] : '';
            if($name)
            {
                $where['combo_name'] = $name;
            }
        }
        return [
            'where' => $where,
            'andWhere' => $andWhere
        ];
    }

    /**
     * 进行初始化数据处理
     * @param array $list
     * @return array
     */
    public function handelInit($list = [])
    {
        //套系是否有该商品
        $post = Yii::$app->request->post('extra_search');
        isset($post['selectCombo']) && !empty($post['selectCombo']) ? $selectCombo = explode(',',rtrim($post['selectCombo'],',')) : $selectCombo = [];

        $adGoodsInfo = AbGoods::getFormArray(['AB_id' => Common::getBusinessId()],'id','goods_name');
        $comboInfo = self::getFormArray(['business_id' => Common::getBusinessId(),'combo_type' => Status::COMBO_TYPE_GENERAL],'id','combo_name');
        foreach($list['data'] as $key => $value)
        {
            $goodsContent = '';
            $comboContent = '';
            $list['data'][$key]['create_time'] = date('Y-m-d',strtotime($value['create_time'])).'</br>'.date('H:i:s',strtotime($value['create_time']));
            if($value['goods_content']){
                $goodsArray = explode(',',$value['goods_content']);
                foreach ($goodsArray as $k => $v) {
                    if(isset($adGoodsInfo[$v])) {
                        $goodsContent .= $adGoodsInfo[$v].',';
                    }
                }
            }
            if($value['combo_content']){
                $comboArray = explode(',',$value['combo_content']);
                foreach ($comboArray as $k => $v) {
                    if(isset($comboInfo[$v])) {
                        $comboContent .= $comboInfo[$v].',';
                    }
                }
            }

            $list['data'][$key]['goods_content'] = rtrim($goodsContent,',');
            $list['data'][$key]['combo_content'] = rtrim($comboContent,',');
            //判断成长套系
            if(isset($selectCombo) && !empty($selectCombo)){
                $list['data'][$key]['checked'] = in_array($value['id'],$selectCombo) ? 'checked' : '' ;
            }
        }
        return $list;
    }

    /**
     * 获取商品信息
     * @param string $model
     * @return array|string
     */
    public function getGoodsList($model = '')
    {
        $goodsIdArray = explode(',',$model->goods_content);
        $abGoodsInfo = AbGoods::getFormArray(['id' => $goodsIdArray], 'id', 'goods_name');
        return $abGoodsInfo ? $abGoodsInfo : '';
    }

    /**
     * 获取套系信息
     * @param string $model
     * @return array|string
     */
    public function getComboList($model = '')
    {
        $comboIdArray = explode(',',$model->combo_content);
        $comboInfo = self::getFormArray(['id' => $comboIdArray], 'id', 'combo_name');
        return $comboInfo ? $comboInfo : '';
    }

    /**
     * 普通套系添加编辑操作
     * @return array
     */
    public function generalComboAddEdit()
    {
        $trans = Yii::$app->db->beginTransaction();
        try {
            if (!$this->validate()) {
                throw new Exception('校验失败');
            }
            $post = Yii::$app->request->post('Combo');
            $id = intval($post['id']);
            $data = [];
            if (!empty($id)) {
                $data = array_merge($data, $this->getSaveData('generalCombo', $post));
                $data['update_time'] = date('Y-m-d H:i:s');
                $data['goods_content'] = implode(',',$data['goods_content']);
                $res = static::updateDataWithLog($data, ['id' => $id]);
                if ($res === false) {
                    throw new Exception('更新失败');
                }
            } else {
                $data = array_merge($data, $this->getSaveData('generalCombo', $post));
                $data['create_time'] = $data['update_time'] = date('Y-m-d H:i:s');
                $data['business_id'] = Common::getBusinessId();
                $data['goods_content'] = implode(',',$data['goods_content']);
                $data['combo_type'] = Status::COMBO_TYPE_GENERAL;
                $res = static::insertDataWithLog($data);
                if ($res === false) {
                    throw new Exception('添加失败');
                }
            }
            $trans->commit();
            return Functions::formatJson(1000, '操作成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(1001, $e->getMessage());
        }
    }

    /**
     * 成长套系编辑添加操作
     * @return array
     */
    public function growComboAddEdit()
    {
        $trans = Yii::$app->db->beginTransaction();
        try {
            if (!$this->validate()) {
                throw new Exception('校验失败');
            }
            $post = Yii::$app->request->post('Combo');
            $id = intval($post['id']);
            $data = [];
            if (!empty($id)) {
                $data = array_merge($data, $this->getSaveData('growCombo', $post));
                $data['update_time'] = date('Y-m-d H:i:s');
                $data['combo_content'] = implode(',',$data['combo_content']);
                $res = static::updateDataWithLog($data, ['id' => $id]);
                if ($res === false) {
                    throw new Exception('更新失败');
                }
            } else {
                $data = array_merge($data, $this->getSaveData('growCombo', $post));
                $data['create_time'] = $data['update_time'] = date('Y-m-d H:i:s');
                $data['business_id'] = Common::getBusinessId();
                $data['combo_content'] = implode(',',$data['combo_content']);
                $data['combo_type'] = Status::COMBO_TYPE_GROW;
                $res = static::insertDataWithLog($data);
                if ($res === false) {
                    throw new Exception('添加失败');
                }
            }
            $trans->commit();
            return Functions::formatJson(1000, '操作成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(1001, $e->getMessage());
        }
    }

    /**
     * 物理删除套系操作
     * @return array
     */
    public function doDelete()
    {
        $trans = Yii::$app->db->beginTransaction();
        try {
            $post = Yii::$app->request->post();
            $id = intval($post['id']);
            $data = [];
            if (!empty($id)) {
                $data['update_time'] = date('Y-m-d H:i:s');
                $data['is_delete'] = Status::COMBO_IS_DELETE;
                $res = static::updateDataWithLog($data, ['id' => $id]);
                if ($res === false) {
                    throw new Exception('删除失败');
                }
            } else {
                throw new Exception('请选择要删除的套系');
            }
            $trans->commit();
            return Functions::formatJson(1000, '删除成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(1001, $e->getMessage());
        }
    }


    /**
     * 获取套系和商品信息
     * @return array
     */
    public function getComboOneInfoByWhere()
    {
        $id = intval(Yii::$app->request->post('id'));
        if (!$id) {
            return Functions::formatJson(2001, '未查询到套系消息');
        }
        $data = [
            'combo' => [],
            'goods' => [],
            'detailcombo' => [],
        ];
        $goodsIdInfo = [];
        //套系信息
        $comboInfo = Combo::getOneInfoById($id);
        //var_dump($comboInfo);die;
        foreach ($comboInfo as $key => $value) {
            $comboInfo[$key] = (isset($value) && !empty($value)) ? $value : '';
        }
        $comboInfo['combo_discount'] = explode(',',$comboInfo['combo_discount']);

        if (!$comboInfo) {
            return Functions::formatJson(2000, '未查询到套系消息');
        }
        $detailComboInfo = array();
        if($comboInfo['combo_type'] == Status::COMBO_TYPE_GENERAL) {
            $goodsIdInfo = explode(',',$comboInfo['goods_content']);
            $detailComboInfo[0] = $comboInfo;
        }

        if($comboInfo['combo_type'] == Status::COMBO_TYPE_GROW) {
            $comboIdInfo = explode(',',$comboInfo['combo_content']);

            //判断商品是否有重复，如果有重复则商品数量需要+1
            $detailComboInfo = self::getByWhere(['id' => $comboIdInfo]);
            //var_dump($detailComboInfo);die;
            foreach ($detailComboInfo as $key => $value) {
                $singleGoodsId = explode(',',$value['goods_content']);
                foreach ($singleGoodsId as $k => $v) {
                    array_push($goodsIdInfo,$v);
                }
            }
        }

        if (!$goodsIdInfo) {
            return Functions::formatJson(2000, '未查询到商品消息');
        }
        $numGoodsArray = array_count_values($goodsIdInfo);
        //商品信息

        $goodsInfo = AbGoods::getByWhere(['id' => $goodsIdInfo],'id,goods_style,goods_code,goods_name,goods_p');
        if (!$goodsInfo) {
            return Functions::formatJson(2000, '未查询到商品消息');
        }
        foreach ($goodsInfo as $key => $value) {
            $goodsInfo[$key]['goods_num'] = $numGoodsArray[$value['id']];
            $goodsInfo[$key]['goods_name'] = (isset($value['goods_name']) && !empty($value['goods_name'])) ? $value['goods_name'] : '--';
            $goodsInfo[$key]['goods_style'] = (isset($value['goods_style']) && !empty($value['goods_style'])) ? $value['goods_style'] : '--';
            $goodsInfo[$key]['goods_code'] = (isset($value['goods_code']) && !empty($value['goods_code'])) ? $value['goods_code'] : '--';
            $goodsInfo[$key]['goods_p'] = (isset($value['goods_p']) && !empty($value['goods_p'])) ? $value['goods_p'] : '--';
        }

        $data = [
            'goods' => $goodsInfo,
            'combo' => $comboInfo,
            'detailcombo' => $detailComboInfo,
        ];
        return Functions::formatJson(1000, '', $data);
    }

    /**
     * 获取订单中商品信息
     * @return array
     */
    public function getGoodsByOrder()
    {
        $combo_order_number = Yii::$app->request->post('combo_order_number');
        $id = intval(Yii::$app->request->post('id'));
        if (!$id) {
            return Functions::formatJson(2001, '未查询到套系消息');
        }
        $data = [
            'combo' => [],
            'goods' => [],
        ];
        //套系信息
        $comboInfo = Combo::getOneInfoById($id);
        foreach ($comboInfo as $key => $value) {
            $comboInfo[$key] = (isset($value) && !empty($value)) ? $value : '';
        }
        $comboInfo['combo_discount'] = explode(',',$comboInfo['combo_discount']);

        $orderDetailModel = new MemberOrderDetail();
        $goodsInfo = $orderDetailModel->getByWhere(array('combo_order_number'=>$combo_order_number));
        foreach ($goodsInfo as $key => $value) {
            $goodsInfo[$key]['id'] = $value['id'];
            $goodsInfo[$key]['goods_num'] = (isset($value['goods_num']) && !empty($value['goods_num'])) ? $value['goods_num'] : '--';
            $goodsInfo[$key]['goods_name'] = (isset($value['goods_name']) && !empty($value['goods_name'])) ? $value['goods_name'] : '--';
            $goodsInfo[$key]['goods_style'] = (isset($value['goods_style']) && !empty($value['goods_style'])) ? $value['goods_style'] : '--';
            $goodsInfo[$key]['goods_code'] = (isset($value['goods_code']) && !empty($value['goods_code'])) ? $value['goods_code'] : '--';
            $goodsInfo[$key]['goods_p'] = (isset($value['goods_p']) && !empty($value['goods_p'])) ? $value['goods_p'] : '--';
            $goodsInfo[$key]['goods_price'] = (isset($value['goods_price']) && !empty($value['goods_price'])) ? $value['goods_price'] : '--';
            $goodsInfo[$key]['goods_discount'] = (isset($value['goods_discount']) && !empty($value['goods_discount'])) ? $value['goods_discount'] : '--';
            $goodsInfo[$key]['goods_color'] = (isset($value['goods_color']) && !empty($value['goods_color'])) ? $value['goods_color'] : '--';
            $goodsInfo[$key]['goods_size'] = (isset($value['goods_size']) && !empty($value['goods_size'])) ? $value['goods_size'] : '--';
            $goodsInfo[$key]['goods_texture'] = (isset($value['goods_texture']) && !empty($value['goods_texture'])) ? $value['goods_texture'] : '--';
        }

        $data = [
            'combo' => $comboInfo,
            'goods' => $goodsInfo,
        ];
        return Functions::formatJson(1000, '', $data);
    }


    /**
     * 获取商品信息
     * @return array
     */
    public function getGoodsInfo()
    {
        $goods_content = Yii::$app->request->post('goods_content');
        $data = [
            'goods' => [],
        ];

        if (!$goods_content) {
            return Functions::formatJson(2000, '未查询到商品消息');
        }
        //商品信息
        $goodsIdInfo = explode(',',$goods_content);
        $goodsInfo = AbGoods::getByWhere(['id' => $goodsIdInfo],'id,goods_style,goods_code,goods_name,goods_p');
        if (!$goodsInfo) {
            return Functions::formatJson(2000, '未查询到商品消息');
        }
        foreach ($goodsInfo as $key => $value) {
            $goodsInfo[$key]['goods_num'] = (isset($value['goods_num']) && !empty($value['goods_num'])) ? $value['goods_num'] : '--';
            $goodsInfo[$key]['goods_name'] = (isset($value['goods_name']) && !empty($value['goods_name'])) ? $value['goods_name'] : '--';
            $goodsInfo[$key]['goods_style'] = (isset($value['goods_style']) && !empty($value['goods_style'])) ? $value['goods_style'] : '--';
            $goodsInfo[$key]['goods_code'] = (isset($value['goods_code']) && !empty($value['goods_code'])) ? $value['goods_code'] : '--';
            $goodsInfo[$key]['goods_p'] = (isset($value['goods_p']) && !empty($value['goods_p'])) ? $value['goods_p'] : '--';
        }
        $data = [
            'goods' => $goodsInfo,
        ];
        return Functions::formatJson(1000, '', $data);
    }

    public static function getDiscountByCombo($comboId = 0)
    {
        $comboInfo = self::getOneInfoById($comboId);
        $comboDiscountArray = explode(',',$comboInfo['combo_discount']);
        $newArray = [];
        foreach ($comboDiscountArray as $key => $value) {
            $newArray[$value] = $value;
        }
        return $newArray;
    }

}
