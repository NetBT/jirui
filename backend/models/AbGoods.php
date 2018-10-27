<?php

namespace backend\models;

use common\models\Status;
use moonland\phpexcel\Excel;
use Yii;
use yii\base\Exception;
use common\models\Functions;
use yii\data\Pagination;

/**
 * 供应商信息表
 * Class AB
 * @package backend\models
 */
class AbGoods extends Common
{
    const CATEGORY_THUMBS = 1;//相册
    const CATEGORY_FRAME = 2;//相框
    const CATEGORY_ORNAMENT = 3;//摆台

    static $category_name = [
        self::CATEGORY_THUMBS => 'THUMBS',
        self::CATEGORY_FRAME => 'FRAME',
        self::CATEGORY_ORNAMENT => 'ORNAMENT',
    ];

    public static function tableName()
    {
        return '{{%ab_goods}}';
    }

    /**
     * 验证规则
     */
    public function rules()
    {
        return [
            #不能为空
            [
                [
                    'goods_code',
                    'goods_name',
                    'goods_price',
                    'goods_cost',
                    'goods_discount',
                    'goods_color',
                    'goods_size',
                    'goods_num',
                    'goods_category',
                    'goods_type'
                ],
                'required',
                'message' => '不能为空',
                'on' => ['add', 'edit']
            ],

            //验证名称和编号
            [['goods_code', 'goods_name'], 'unique', 'message' => '已存在', 'on' => 'add'],
            //验证数字
            [
                ['goods_num', 'goods_category', 'goods_cost', 'goods_discount', 'goods_price', 'goods_p'],
                'number',
                'message' => '必须为数字',
                'on' => ['add', 'edit']
            ]
        ];
    }

    /**
     * 设置属性名称
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'goods_code' => '商品编号',
            'goods_name' => '商品名称',
            'goods_price' => '产品单价',
            'goods_cost' => '成本价',
            'goods_discount' => '限时折扣价',
            'discount_start_time' => '折扣开始时间',
            'discount_end_time' => '折扣结束时间',
            'goods_color' => '颜色',
            'goods_size' => '尺寸',
            'goods_texture' => '材质',
            'goods_style' => '内页/风格',
            'goods_num' => '数量',
            'goods_category' => '商品分类',
            'goods_type' => '商品类型',
            'goods_p ' => 'P数',
        ];
    }


    /**
     * 设置场景
     * @return array
     */
    public function scenarios()
    {
        $newScenarios = [
            'add' => [
                'goods_code',
                'goods_name',
                'goods_price',
                'goods_cost',
                'goods_discount',
                'discount_start_time',
                'discount_end_time',
                'goods_color',
                'goods_size',
                'goods_texture',
                'goods_style',
                'goods_type',
                'goods_num',
                'goods_category',
                'goods_p',
            ],
            'edit' => [
                'goods_code',
                'goods_name',
                'goods_price',
                'goods_cost',
                'goods_discount',
                'discount_start_time',
                'discount_end_time',
                'goods_color',
                'goods_size',
                'goods_texture',
                'goods_style',
                'goods_num',
                'goods_category',
                'goods_type',
                'goods_p',
            ],
        ];
        return array_merge(parent::scenarios(), $newScenarios);
    }

    /**
     * 获取商品列表
     * @return array
     */
    public function getListData()
    {
        $returnData = [
            "draw" => intval(Yii::$app->request->post('draw')),
            "recordsTotal" => 0,
            "recordsFiltered" => 0,
            "data" => null
        ];
        //搜索条件
        $post = Yii::$app->request->post('extra_search');
        //计算总数
        $where = [];
        $where['AB_id'] = Common::getBusinessId();
        isset($post['goods_code']) && !empty($post['goods_code']) ? ($where['goods_code'] = $post['goods_code']) : null;
        isset($post['goods_name']) && !empty($post['goods_name']) ? ($where['goods_name'] = $post['goods_name']) : null;
        $startTime = isset($post['startTime']) && !empty($post['startTime']) ? $post['startTime'] : null;
        $endTime = isset($post['endTime']) && !empty($post['endTime']) ? $post['endTime'] : null;
        //套系是否有该商品
        isset($post['selectGoods']) && !empty($post['selectGoods']) ? $selectGoods = explode(',',
            rtrim($post['selectGoods'], ',')) : $selectGoods = [];
        $andWhere = $this->getAndWhereForTime('create_time', $startTime, $endTime);
        $count = static::getCountByAndWhere($where, $andWhere);

        $returnData["recordsTotal"] = $returnData['recordsFiltered'] = intval($count);

        //设置分页
        $this->setPagination();
        //获取数据
        $returnData['data'] = static::getByAndWhere($where, $andWhere, ['*'], 'create_time desc',
            $this->_Pagination['offset'], $this->_Pagination['limit']);
        if (!empty($returnData['data'])) {
            foreach ($returnData['data'] as $k => $v) {
                $info = Employee::getOneByWhere(['id' => $v['create_user']], 'employee_name');
                $returnData['data'][$k]['AB_operate_name'] = $info['employee_name'];
                $returnData['data'][$k]['discount_time'] = $v['discount_start_time'] . '<br />' . $v['discount_end_time'];

                //套系是否有该商品
                $returnData['data'][$k]['checked'] = '';
                if (isset($selectGoods) && !empty($selectGoods)) {
                    $returnData['data'][$k]['checked'] = in_array($v['id'], $selectGoods) ? 'checked' : '';
                }
            }
        }
        return $returnData;
    }

    public static function makeGoodsCode()
    {
        //获取当日新增加盟商数量
        $curr = date('Y-m-d 00:00:00');
        $amount = static::getCountByAndWhere([], ['>=', 'create_time', $curr]);
        $amount++;
        return 'AB' . Common::getBusinessId() . 'G' . date("YmdH") . Functions::zeroFill($amount, 4, 0);
    }

    /**
     * 保存商品信息
     * @return array
     */
    public function saveData()
    {
        $trans = Yii::$app->db->beginTransaction();
        try {
            if (!$this->validate()) {
                throw new Exception('校验失败');
            }
            $post = \Yii::$app->request->post('AbGoods');
            $data = [];
            if (isset($post['id']) && !empty($post['id'])) {
                $data = array_merge($data, $this->getSaveData('edit', $post));
                $data['update_user'] = \Yii::$app->user->getId();
                $data['update_time'] = date("Y-m-d H:i:s");
                $res = static::updateDataWithLog($data, ['id' => $post['id']]);
                if ($res === false) {
                    throw new Exception('数据库更新失败');
                }
                ABGoodsImages::updateGoodsImages($post['id']);
            } else {
                $data['create_user'] = \Yii::$app->user->getId();
                $data['create_time'] = date("Y-m-d H:i:s");
                $data['AB_id'] = Common::getBusinessId();
                $data = array_merge($data, $this->getSaveData('add', $post));
                $res = static::insertDataWithLog($data);

                if ($res === false) {
                    throw new Exception('数据插入失败');
                }

                //更新图片
                $session = Yii::$app->getSession();
                $imgList = $session->get('AbGoodsImage');

                if (!empty($imgList)) {
                    foreach ($imgList as $k => $v) {
                        $imgData['goods_id'] = $res;
                        $imgData['image_url'] = $v;
                        $imgData['order_no'] = $k;
                        $imgData['create_time'] = date('Y-m-d H:i:s');
                        $imgRes = ABGoodsImages::insertData($imgData);
                        if ($imgRes === false) {
                            throw new Exception('图片添加失败');
                        }
                    }
                    $session->remove('AbGoodsImage');
                }
            }
            $trans->commit();
            return Functions::formatJson(1000, '操作成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(2000, $e->getMessage());
        }
    }


    /**
     * 获取商品列表
     * @return mixed
     */
    public function getGoodsListForSale()
    {
        $where['goods_status'] = Status::GOODS_STATUS_PUT_ON_SHELVES;
        $goodsCode = Yii::$app->request->get('goodsCode');
        if (!empty($goodsCode)) {
            $where['goods_code'] = $goodsCode;
        }
        $goodsName = Yii::$app->request->get('goodsName');
        if (!empty($goodsName)) {
            $where['goods_name'] = $goodsName;
        }
        $count = static::getCountByWhere($where);
        $pages = new Pagination(['totalCount' => $count, 'pageSize' => 4]);
        $data['list'] = $this->getGoodsListForWhere($where, '*', 'create_time desc', $pages->offset, $pages->limit);
        $data['goodsCode'] = $goodsCode;
        $data['goodsName'] = $goodsName;
        $data['pages'] = $pages;
        return $data;
    }

    public function getGoodsListForWhere($where = [], $field = '*', $order = null, $offset = null, $limit = null)
    {
        $list = static::getByWhere($where, $field, $order, $offset, $limit);
        $currDate = date('Y-m-d H:i:s');
        foreach ($list as $k => $v) {
            $image = ABGoodsImages::getOneByWhere(['goods_id' => $v['id']], 'image_url');
            $list[$k]['image_url'] = !empty($image['image_url']) ? $image['image_url'] : '';
            if ($currDate >= $v['discount_start_time'] && $currDate <= $v['discount_end_time']) {
                $list[$k]['curr_real_price'] = $v['goods_discount'];
            } else {
                $list[$k]['curr_real_price'] = $v['goods_price'];
            }
        }
        return $list;
    }

    public function getGoodsCurrPrice($id = 0)
    {
        $info = static::getOneByWhere(['id' => $id]);
        $currTime = date('Y-m-d H:i:s');
        if (!empty($info)) {
            if ($currTime >= $info['discount_start_time'] && $currTime <= $info['discount_end_time']) {
                return $info['goods_discount'];
            } else {
                return $info['goods_price'];
            }
        } else {
            return false;
        }
    }

    public static function getGoodsCurrPriceByInfo($info = [])
    {
        if (empty($info)) {
            return false;
        }
        $currTime = date('Y-m-d H:i:s');

        if ($currTime >= $info['discount_start_time'] && $currTime <= $info['discount_end_time']) {
            return $info['goods_discount'];
        } else {
            return $info['goods_price'];
        }
    }

    /**
     * 导出excel
     */
    public function exportExcel()
    {
        $where['AB_id'] = Common::getBusinessId();
        $list = self::find()->where($where)->asArray()->all();
        $employeeInfo = Employee::getFormArray('', 'id', 'employee_name');
        $goodsCategoryInfo = GoodsCategory::getFormArray('', 'id', 'category_name');
        $abInfo = AB::getFormArray('', 'id', 'AB_name');
        foreach ($list as $key => $value) {
            $list[$key]['create_user'] = $value['create_user'] ? $employeeInfo[$value['create_user']] : '--';
            $list[$key]['goods_category'] = $value['goods_category'] ? $goodsCategoryInfo[$value['goods_category']] : '--';
            $list[$key]['goods_type'] = $value['goods_type'] ? Status::getABGoodsTypeMap()[$value['goods_type']] : '--';
            $list[$key]['AB_id'] = $value['AB_id'] ? $abInfo[$value['AB_id']] : '--';
        }
        Excel::export([
            'models' => $list,
            'fileName' => date('Ymd') . '导出商品信息',
            'columns' => [
                'AB_id',
                'goods_code',
                'goods_name',
                'create_user',
                'goods_price',
                'goods_cost',
                'goods_discount',
                'discount_start_time',
                'discount_end_time',
                'goods_color',
                'goods_size',
                'goods_texture',
                'goods_style',
                'goods_num',
                'goods_category',
                'goods_type',
            ], //没有头工作,因为头会得到标签的属性标签
            'headers' => [
                'AB_id' => '商户',
                'goods_code' => '商品编号',
                'goods_name' => '商品名称',
                'create_user' => '创建者',
                'goods_price' => '单价',
                'goods_cost' => '成本价',
                'goods_discount' => '限时折扣价',
                'discount_start_time' => '折扣开始时间',
                'discount_end_time' => '折扣结束时间',
                'goods_color' => '颜色',
                'goods_size' => '尺寸',
                'goods_texture' => '材质',
                'goods_style' => '内页/风格',
                'goods_num' => '数量',
                'goods_category' => '商品分类',
                'goods_type' => '商品类型',
            ],
        ]);
    }


    public function toggleShelf($goodsId = 0)
    {
        $trans = Yii::$app->db->beginTransaction();
        try {
            $goodsId = intval($goodsId);
            if ($goodsId == 0) {
                throw new Exception('商品信息错误');
            }
            $goodsInfo = static::getOneByWhere(['id' => $goodsId]);
            if (empty($goodsInfo)) {
                throw new Exception('未找到商品信息');
            }
            if ($goodsInfo['goods_status'] == Status::AB_GOODS_STATUS_DELETE) {
                throw new Exception('商品信息已删除,不能进行上下架操作');
            }

            if ($goodsInfo['goods_status'] == Status::AB_GOODS_STATUS_PUT_ON_SHELVES) {
                $data['goods_status'] = Status::AB_GOODS_STATUS_PUT_OFF_SHELVES;
                $str = '下架';
            } else {
                $data['goods_status'] = Status::AB_GOODS_STATUS_PUT_ON_SHELVES;
                $str = '上架';
            }
            $data['update_time'] = date('Y-m-d H:i:s');
            $res = static::updateDataWithLog($data, ['id' => $goodsId]);
            if ($res === false) {
                throw new Exception($str . '失败');
            }
            $trans->commit();
            return Functions::formatJson(1000, $str . '成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(2000, $e->getMessage());
        }
    }

    public function searchGoods($goodsCode = '')
    {
        try {
            if (empty($goodsCode)) {
                throw new Exception('商品编号不能为空');
            }

            $where['goods_code'] = $goodsCode;
            $where['AB_id'] = static::getBusinessId();
            $info = static::getOneByWhere($where);
            if (empty($info)) {
                throw new Exception('商品信息未找到');
            }

            if (empty($info['head_goods_id'])) {
                $image = ABGoodsImages::getByWhere(['goods_id' => $info['id']]);
            } else {
                $image = GoodsImages::getBusinessId(['goods_id' => $info['head_goods_id']]);
            }
            $data['info'] = $info;
            $data['images'] = $image;
            return Functions::formatJson(1000, '查讯成功', $data);
        } catch (Exception $e) {
            return Functions::formatJson(2000, $e->getMessage());
        }
    }

    public function doSockIn()
    {
        $goodsCode = Yii::$app->request->post('goodsCode');
        $num = intval(Yii::$app->request->post('inNum'));
        $trans = Yii::$app->db->beginTransaction();
        try {
            if (empty($goodsCode)) {
                throw new Exception('商品编号不能为空');
            }

            if (empty($num)) {
                throw new Exception('数量不能为空');
            }

            if ($num < 0) {
                throw new Exception('入库数量不能为负数');
            }

            $where['goods_code'] = $goodsCode;
            $where['AB_id'] = static::getBusinessId();
            $info = static::getOneByWhere($where);
            if (empty($info)) {
                throw new Exception('商品信息未找到');
            }

            $data['goods_num'] = $info['goods_num'] + $num;
            $data['update_time'] = date('Y-m-d H:i:s');
            $res = static::updateData($data, $where);
            if ($res === false) {
                throw new Exception('更新库存失败');
            }
            //记录日志
            $res = AbGoodsStock::recordStockLog($info['id'], $num, Status::AB_G_STOCK_TYPE_IN);
            if ($res !== true) {
                throw new Exception($res['msg']);
            }

            $trans->commit();
            return Functions::formatJson(1000, '入库成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(2000, $e->getMessage());
        }
    }

    public function doSockOut()
    {
        $goodsCode = Yii::$app->request->post('goodsCode');
        $num = intval(Yii::$app->request->post('inNum'));
        $trans = Yii::$app->db->beginTransaction();
        try {
            if (empty($goodsCode)) {
                throw new Exception('商品编号不能为空');
            }

            if (empty($num)) {
                throw new Exception('数量不能为空');
            }

            if ($num < 0) {
                throw new Exception('数量不能为负数');
            }

            $where['goods_code'] = $goodsCode;
            $where['AB_id'] = static::getBusinessId();
            $info = static::getOneByWhere($where);
            if (empty($info)) {
                throw new Exception('商品信息未找到');
            }
            if ($info['goods_num'] < $num) {
                throw new Exception('库存不足');
            }
            $data['goods_num'] = $info['goods_num'] - $num;
            $data['update_time'] = date('Y-m-d H:i:s');
            $res = static::updateData($data, $where);
            if ($res === false) {
                throw new Exception('更新库存失败');
            }
            //记录日志
            $res = AbGoodsStock::recordStockLog($info['id'], $num, Status::AB_G_STOCK_TYPE_OUT);
            if ($res !== true) {
                throw new Exception($res['msg']);
            }

            $trans->commit();
            return Functions::formatJson(1000, '出库成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(2000, $e->getMessage());
        }
    }

    /**
     * 方法描述：
     * @param $combo_order_number
     * @return MemberOrderGoodsImages[]
     * 注意：
     */
    public function getComboGoodsImages($combo_order_number)
    {
        return MemberOrderGoodsImages::findAll(['combo_order_number' => $combo_order_number, 'goods_id' => $this->id]);
    }

    /**
     * 方法描述：
     * @param $combo_order_number
     * @return MemberOrderGoodsImages
     * 注意：
     */
    public function getComboGoodsFirstImage($combo_order_number)
    {
        return MemberOrderGoodsImages::findOne([
            'combo_order_number' => $combo_order_number,
            'goods_code' => $this->goods_code
        ]);
    }

    public function getCategoryEnName()
    {
        return isset(self::$category_name[$this->goods_category]) ? self::$category_name[$this->goods_category] : 'UNKNOWN';
    }
}
