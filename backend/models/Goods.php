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
class Goods extends Common
{
    public static function tableName()
    {
        return '{{%goods}}';
    }
    /**
     * 验证规则
     */
    public function rules()
    {
        return [
            #不能为空
            [[
                'goods_code', 'goods_name', 'goods_price', 'goods_cost', 'goods_discount',
                'goods_color', 'goods_size', 'goods_num', 'goods_category'
            ], 'required','message' => '不能为空','on' => ['add', 'edit']],

            //验证名称和编号
            [['goods_code', 'goods_name'], 'unique', 'message' => '已存在', 'on' => 'add'],
            //验证数字
            [['goods_num', 'goods_category', 'goods_cost', 'goods_discount', 'goods_price'], 'number', 'message' => '必须为数字','on' => ['add', 'edit']]
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
        ];
    }


    /**
     * 设置场景
     * @return array
     */
    public function scenarios()
    {
        $newScenarios =  [
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
                'goods_num',
                'goods_category',
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
            ],
        ];
        return array_merge(parent::scenarios(), $newScenarios);
    }

    /**
     * 获取商品列表
     * @return array
     */
    public function getListData () {
        $returnData = [
            "draw" => intval(Yii::$app->request->post('draw')),
            "recordsTotal" => 0,
            "recordsFiltered" => 0,
            "data" => null
        ];
        //搜索条件
        $post = Yii::$app->request->post('extra_search');
        //计算总数
        $where['goods_status'] = [Status::GOODS_STATUS_PUT_OFF_SHELVES, Status::GOODS_STATUS_PUT_ON_SHELVES];
        isset($post['goods_code']) && !empty($post['goods_code']) ? ($where['goods_code'] = $post['goods_code']) : null;
        isset($post['goods_name']) && !empty($post['goods_name']) ? ($where['goods_name'] = $post['goods_name']) : null;

        $andWhere = $this->getAndWhereForTime('create_time', $post['startTime'], $post['endTime']);
        $count = static::getCountByAndWhere($where, $andWhere);

        $returnData["recordsTotal"] = $returnData['recordsFiltered'] = intval($count);

        //设置分页
        $this->setPagination();
        //获取数据
        $returnData['data'] = static::getByAndWhere($where, $andWhere, ['*'], 'create_time desc', $this->_Pagination['offset'], $this->_Pagination['limit']);
        if (!empty($returnData['data'])) {
            foreach ($returnData['data'] as $k => $v) {
                $info = Employee::getOneByWhere(['id' => $v['create_user']], 'employee_name');
//                $returnData['data'][$k]['AB_operate_name'] = $info['employee_name'];
                $returnData['data'][$k]['create_user_name'] = $info['employee_name'];
                $returnData['data'][$k]['discount_time'] = $v['discount_start_time'] . '<br />' . $v['discount_end_time'];
            }
        }
        return $returnData;
    }

    /**
     * 保存商品信息
     * @return array
     */
    public function saveData() {
        $trans = Yii::$app->db->beginTransaction();
        try {
            if (!$this->validate()) {
                throw new Exception('校验失败');
            }
            $post = \Yii::$app->request->post('Goods');
            $data = [];
            if (isset($post['id']) && !empty($post['id'])) {
                $data = array_merge($data, $this->getSaveData('edit', $post));
                $data['update_user'] = \Yii::$app->user->getId();
                $data['update_time'] = date("Y-m-d H:i:s");
                $res = static::updateDataWithLog($data, ['id' => $post['id']]);
                if ($res === false) {
                    throw new Exception('数据库更新失败');
                }
                GoodsImages::updateGoodsImages($post['id']);
            } else {
                $data['create_user'] = \Yii::$app->user->getId();
                $data['create_time'] = date("Y-m-d H:i:s");
                $data = array_merge($data, $this->getSaveData('add', $post));
                $res = static::insertDataWithLog($data);

                if ($res === false) {
                    throw new Exception('数据插入失败');
                }

                //更新图片
                $session = Yii::$app->getSession();
                $imgList = $session->get('goodsImage');

                if (!empty($imgList)) {
                    foreach ($imgList as $k => $v) {
                        $imgData['goods_id'] = $res;
                        $imgData['image_url'] = $v;
                        $imgData['order_no'] = $k;
                        $imgData['create_time'] = date('Y-m-d H:i:s');
                        $imgRes = GoodsImages::insertData($imgData);
                        if ($imgRes === false) {
                            throw new Exception('图片添加失败');
                        }
                    }
                    $session->remove('goodsImage');
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
     * 删除数据
     * @param null $id
     *
     * @return bool|string
     */
    public function deleteData($id = null) {
        $trans = Yii::$app->db->beginTransaction();
        try {
            if (empty($id)) {
                throw new Exception('数据格式错误');
            }
            $data['goods_status'] = Status::GOODS_STATUS_DELETE;
            $data['update_time'] = date('Y-m-d H:i:s');
            $where['id'] = intval($id);
            $res = static::updateDataWithLog($data, $where);
            if ($res === false) {
                throw new Exception('系统错误');
            }
            $trans->commit();
            return  Functions::formatJson(1000, '删除成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return  Functions::formatJson(2000, $e->getMessage());
        }
    }

    public function toggleStatus($id = 0) {
        $trans = Yii::$app->db->beginTransaction();
        try {
            $id = intval($id);
            if (empty($id)) {
                throw new Exception('信息错误');
            }
            $info = static::getOneByWhere(['id' => $id]);
            if (empty($info)) {
                throw new Exception('未找到加盟商信息');
            }

            $data['AB_update_time'] = date('Y-m-d H:i:s');
            if ($info['AB_store_status'] == Status::AB_STORE_STATUS_LOCK) {
                $data['AB_store_status'] = Status::AB_STORE_STATUS_UNLOCK;
            }else {
                $data['AB_store_status'] = Status::AB_STORE_STATUS_LOCK;
            }
            $res = $this->updateDataWithLog($data, ['id' => $id]);
            if ($res === false) {
                throw new Exception('数据库操作有误');
            }
            $trans->commit();
            return Functions::formatJson(1000, '操作成功');
        } catch ( Exception $e ) {
            $trans->rollBack();
            return Functions::formatJson(2000, $e->getMessage());
        }
    }

    public static function makeGoodsCode() {
        //获取当日新增加盟商数量
        $curr = date('Y-m-d 00:00:00');
        $amount = static::getCountByAndWhere([], ['>=', 'create_time', $curr]);
        $amount ++;
        return 'G' . date("YmdH") . Functions::zeroFill($amount, 4, 0);
    }

    /**
     * 获取商品列表
     * @return mixed
     */
    public function getGoodsListForSale() {
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
        $data['list'] = $this->getGoodsListForWhere($where, '*', 'create_time desc',  $pages->offset,  $pages->limit);
        $data['goodsCode'] = $goodsCode;
        $data['goodsName'] = $goodsName;
        $data['pages'] = $pages;
        return $data;
    }

    public function getGoodsListForWhere($where = [], $field = '*', $order  = null, $offset = null, $limit = null) {
        $list = static::getByWhere($where, $field, $order, $offset, $limit);
        $currDate = date('Y-m-d H:i:s');
        foreach ($list as $k => $v) {
            $image = GoodsImages::getOneByWhere(['goods_id' => $v['id']], 'image_url');
            $list[$k]['image_url'] = !empty($image['image_url']) ? $image['image_url'] : '';
            if ($currDate >= $v['discount_start_time'] && $currDate <= $v['discount_end_time']) {
                $list[$k]['curr_real_price'] = $v['goods_discount'];
            } else {
                $list[$k]['curr_real_price'] = $v['goods_price'];
            }
        }
        return $list;
    }

    public function getGoodsCurrPrice($id = 0) {
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

    public static function getGoodsCurrPriceByInfo($info = []) {
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

    public function toggleShelf($goodsId = 0) {
        $trans = Yii::$app->db->beginTransaction();
        try {
            $goodsId = intval($goodsId);
            if ($goodsId == 0) {
                throw new Exception('商品信息错误');
            }
            $goodsInfo = static::getOneByWhere(['id' => $goodsId]);
            if(empty($goodsInfo)) {
                throw new Exception('未找到商品信息');
            }
            if($goodsInfo['goods_status'] == Status::GOODS_STATUS_DELETE) {
                throw new Exception('商品信息已删除,不能进行上下架操作');
            }

            if($goodsInfo['goods_status'] == Status::GOODS_STATUS_PUT_ON_SHELVES) {
                $data['goods_status'] = Status::GOODS_STATUS_PUT_OFF_SHELVES;
                $str = '下架';
            } else {
                $data['goods_status'] = Status::GOODS_STATUS_PUT_ON_SHELVES;
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

    /**
     * 导出excel
     */
    public function exportExcel()
    {
        $where['goods_status'] = [Status::GOODS_STATUS_PUT_OFF_SHELVES, Status::GOODS_STATUS_PUT_ON_SHELVES];
        $list = self::find()->where($where)->asArray()->all();
        $employeeInfo = Employee::getFormArray('','id','employee_name');
        $goodsCategoryInfo = GoodsCategory::getFormArray('','id','category_name');
        foreach ($list as $key => $value) {
            $list[$key]['create_user_name'] = $value['create_user'] ? $employeeInfo[$value['create_user']] : '--';
            $list[$key]['goods_category'] = $value['goods_category'] ? $goodsCategoryInfo[$value['goods_category']] : '--';
        }
        Excel::export([
            'models' => $list,
            'fileName' => date('Ymd').'导出商品信息',
            'columns' => [
                'goods_code',
                'create_user_name',
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
            ], //没有头工作,因为头会得到标签的属性标签
            'headers' => [
                'goods_code' => '商品编号',
                'create_user_name' => '操作者',
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
            ],
        ]);
    }
}
