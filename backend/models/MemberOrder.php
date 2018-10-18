<?php

namespace backend\models;

use common\models\Status;
use moonland\phpexcel\Excel;
use Yii;
use yii\base\Exception;
use common\models\Functions;
use yii\bootstrap\ActiveForm;

class MemberOrder extends Common
{

    private $fieldArray = [
        "id",           //主键
        "member_id",    //会员ID
        "business_id",  //商户ID
        "order_number", //订单编号
        "price",        //价格
        "earnest",      //定金
        "number",       //数量
        "discount",     //折扣
        "integral",     //积分
        "final_payment",//尾款
        "create_time",  //创建时间
        "pay_type",     //支付方式
        "order_type",   //订单类型
        "mark",         //备注
        "combo_id",         //套系
        "gathering_money",   //实收
        "total_money",       //总收款
        "order_number",      //订单编码
        "combo_price",       //套系价格
        "order_type",       //套系类型
        "orderstate",       //挂单
    ];

    public $order_date;
    public $order_number_type = Status::MEMBER_ORDER_NUMBER_TYPE_AUTO;

    public static function tableName()
    {
        return '{{%ab_member_order}}';
    }

    public $business_name;

    /**
     * 获取字段
     * @return array
     */
    private function _getFields()
    {
        return $this->fieldArray;
    }

    /**
     * 验证规则
     */
    public function rules()
    {
        return [
            #添加
            [
                [
                    'member_id',
                    'combo_price',
                    'combo_id',
                    'number',
                    'gathering_fund',
                    'gathering_money',
                    'pay_type',
                    'price'
                ],
                'required',
                'message' => '{attribute}不能为空',
                'on' => 'add'
            ],
            [['combo_price', 'number', 'integral'], 'integer', 'message' => '{attribute}格式不对', 'on' => 'add'],
            ['price', 'double', 'message' => '{attribute}格式不对', 'on' => 'add'],
            ['order_number', 'required', 'message' => '{attribute}不能为空', 'on' => 'add'],
            ['order_number', 'validateOrderNumber', 'on' => 'add'],

            #编辑
            [
                ['combo_id', 'number', 'gathering_fund', 'gathering_money', 'pay_type', 'price'],
                'required',
                'message' => '{attribute}不能为空',
                'on' => 'edit'
            ],
            [['number', 'integral'], 'integer', 'message' => '{attribute}格式不对', 'on' => 'edit'],
            ['price', 'double', 'message' => '{attribute}格式不对', 'on' => 'edit'],
        ];
    }

    /**
     * 设置属性名称
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'member_id' => '会员名称',
            'combo_id' => '套系名称',
            'business_id' => '商户号',
            'order_number' => '订单编号',
            'price' => '成单价',
            'earnest' => '定金',
            'number' => '数量',
            'discount' => '折扣',
            'integral' => '积分',
            'final_payment' => '尾款',
            'create_time' => '创建时间',
            'pay_type' => '支付方式',
            'gathering_fund' => '收款款项',
            'gathering_money' => '收款金额',
            'second_pay_type' => '支付方式',
            'order_type' => '订单类型',
            'mark' => '备注',
            'order_number_type' => '编码方式',
            'combo_price' => '套系价',
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
                'member_id',
                'combo_id',
                'combo_price',
                'price',
                'earnest',
                'number',
                'discount',
                'integral',
                'final_payment',
                'pay_type',
                'mark',
                'gathering_fund',
                'gathering_money',
                'order_number'
            ],
            'edit' => [
                'price',
                'earnest',
                'number',
                'discount',
                'integral',
                'final_payment',
                'combo_id',
                'pay_type',
                'order_type',
                'mark',
                'gathering_fund',
                'gathering_money',
                'order_number'
            ],
        ];
        return array_merge(parent::scenarios(), $newScenarios);
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $employee = $this->getEmployee();

            if ($this->login_name !== $employee->login_name) {
                $this->addError('login_name', '账号填写错误.');
            }

            if (!$employee || !$employee->validatePassword($this->password)) {
                $this->addError($attribute, '账号或密码错误.');
            }
            //是否是启用状态
            if (!$this->validateStatus($this->login_name)) {
                $this->addError($attribute, '账号已被禁用，请联系管理员.');
            }
            $result = AB::checkABInfo($this->login_name);
            if ($result['isLock']['status'] === true) {
                $this->addError($attribute, $result['isLock']['message']);
            }
            if ($result['isStart']['status'] === false) {
                $this->addError($attribute, $result['isStart']['message']);
            }
            if ($result['isEnd']['status'] === true) {
                $this->addError($attribute, $result['isEnd']['message']);
            }
            $isClose = Functions::getCommonByKey('web_close_on_off');
            if ($isClose == 2) {
                $this->addError($attribute, '因' . Functions::getCommonByKey('web_close_reason') . ', 网站关闭');
            }
        }
    }

    public function validateOrderNumber($attribute, $params)
    {
        $res = self::getOneByWhere(['order_number' => $this->order_number]);
        if ($res) {
            $this->addError($attribute, '订单号不能重复');
        }
    }

    public function getListData()
    {
        $returnData = [
            "draw" => intval(Yii::$app->request->post('draw')),
            "recordsTotal" => 0,
            "recordsFiltered" => 0,
            "data" => null
        ];
        //搜索条件
        $searchWhere = $this->getSearch(Yii::$app->request->post('extra_search'));//自定义搜索条件

        //得到文章的总数（但是还没有从数据库取数据）
        if (isset($searchWhere['andWhere'])) {
            $count = self::getCountByAndWhere($searchWhere['where'], $searchWhere['andWhere']);
        } else {
            $count = self::getCountByWhere($searchWhere);
        }
        $returnData["recordsTotal"] = $returnData['recordsFiltered'] = intval($count);

        //设置分页
        $this->setPagination();

        $selectField = "";
        $fields = $this->_getFields();
        foreach ($fields as $key => $value) {
            $selectField .= "," . $value;
        }
        $selectField = ltrim($selectField, ',');
        //排序 order
        $orderSql = $this->_Pagination['order'] ? $this->_Pagination['order'] : 'id ASC';
        if (isset($searchWhere['andWhere'])) {
            $returnData['data'] = static::getByAndWhere($searchWhere['where'], $searchWhere['andWhere'], $selectField,
                $orderSql, $this->_Pagination['offset'], $this->_Pagination['limit']);
        } else {
            $returnData['data'] = static::getByWhere($searchWhere, $selectField, $orderSql,
                $this->_Pagination['offset'], $this->_Pagination['limit']);
        }
        return $returnData;
    }

    /**
     * 自定义参数的搜索  搜索,搜索也分每一列
     * 这里要根据业务逻辑进行修改
     * @param $search
     * @return string
     */
    public function getSearch($search = [])
    {
        $where = [];
        $andWhere = [];
        $where['business_id'] = Common::getBusinessId();
        $where['is_delete'] = Status::MEMBER_ORDER_DELETE_NO;
        if (!empty($search)) {
            $memberName = $search['memberName'];
            $memberIdInfo = Member::getFormArray(['name' => $memberName], 'id', 'id');
            $orderNumber = $search['orderNumber'];
            $orderType = isset($search['orderType']) ? $search['orderType'] : '';
            $orderstate = isset($search['orderstate']) ? $search['orderstate'] : '1';
            $startTime = $search['startTime'];
            $endTime = $search['endTime'];
            if ($memberName) {
                $where['member_id'] = $memberIdInfo;
            }
            if ($orderNumber) {
                $where['order_number'] = $orderNumber;
            }
            if ($orderType) {
                $where['order_type'] = $orderType;
            }
            if ($orderstate) {
                $where['orderstate'] = $orderstate;
            }
            if (!empty($startTime) && empty($endTime)) {
                $andWhere = ['>', 'create_time', $startTime . ' 00:00:00'];
            } elseif (empty($startTime) && !empty($endTime)) {
                $andWhere = ['<', 'create_time', $endTime . ' 23:59:59'];
            } elseif (!empty($startTime) && !empty($endTime)) {
                $andWhere = ['between', 'create_time', $startTime . ' 00:00:00', $endTime . ' 23:59:59'];
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
        $memberInfo = Member::getFormArray(['business_id' => Common::getBusinessId()], 'id', 'name');
        $comboInfo = Combo::getFormArray(['business_id' => Common::getBusinessId()], 'id', 'combo_name');
        $abInfo = AB::getFormArray('', 'id', 'AB_name');
        foreach ($list['data'] as $key => $value) {
            $list['data'][$key]['create_time'] = date('Y-m-d',
                    strtotime($value['create_time'])) . '</br>' . date('H:i:s', strtotime($value['create_time']));
            $list['data'][$key]['member_id'] = isset($memberInfo[$value['member_id']]) ? $memberInfo[$value['member_id']] : '--';
//            $list['data'][$key]['discount'] = (isset($value['discount']) && !empty($value['discount'])) ? $value['discount'] : '--';
//            $list['data'][$key]['order_type'] = Status::memberOrderTypeMap()[$value['order_type']];
            $list['data'][$key]['combo_name'] = $comboInfo[$value['combo_id']];
            $list['data'][$key]['short_mark'] = (isset($value['mark']) && !empty($value['mark'])) ? Functions::chinese_str_cut(rtrim($value['mark'],
                ','), 50) : '--';
            $list['data'][$key]['discount'] = (isset($value['discount']) && !empty($value['discount'])) ? $value['discount'] . '折' : '--';

            //去除多余的0 floatval
            $list['data'][$key]['combo_price'] = (isset($value['combo_price']) && !empty($value['combo_price'])) ? floatval($value['combo_price']) : '--';
            $list['data'][$key]['price'] = (isset($value['price']) && !empty($value['price'])) ? floatval($value['price']) : '--';
            $list['data'][$key]['earnest'] = (isset($value['earnest']) && !empty($value['earnest'])) ? floatval($value['earnest']) : '--';
            $list['data'][$key]['final_payment'] = (isset($value['final_payment']) && !empty($value['final_payment'])) ? floatval($value['final_payment']) : '--';
            $list['data'][$key]['gathering_money'] = (isset($value['gathering_money']) && !empty($value['gathering_money'])) ? floatval($value['gathering_money']) : '--';
            $list['data'][$key]['total_money'] = (isset($value['total_money']) && !empty($value['total_money'])) ? floatval($value['total_money']) : '--';

            $list['data'][$key]['business_id'] = $value['business_id'] ? $abInfo[$value['business_id']] : '';
            $list['data'][$key]['pay_type'] = (isset($value['pay_type']) && !empty($value['pay_type'])) ? Status::memberOrderPayTypeMap()[$value['pay_type']] : '';
            $list['data'][$key]['order_type'] = $value['order_type'] ? Status::comboTypeMap()[$value['order_type']] : '--';
//            $list['data'][$key]['combo_id'] = $comboInfo[$value['combo_id']];

            $list['data'][$key]['combo_order_number'] = '';
            //排项相关
            if ($value['order_type'] == Status::MEMBER_ORDER_COMBO_TYPE_NORMAL) {
                $info = MemberOrderCombo::getOneByWhere(['order_number' => $value['order_number']],
                    'combo_order_number');
                $list['data'][$key]['combo_order_number'] = $info['combo_order_number'];
            }
        }
        return $list;
    }

    /**
     * 获取订单编号
     * @return string
     */
    public static function getMemberOrderNum()
    {
        do {
            $num = date('YmdHis') . 'MO' . Common::getBusinessId();
            $res = static::getOneByWhere(['order_number' => $num]);
        } while (!empty($res));
        return $num;
    }

    /**
     * 编辑添加操作
     * @return array
     */
    public function addEdit()
    {
        $trans = Yii::$app->db->beginTransaction();
        $post = Yii::$app->request->post('MemberOrder');
        $orderstate = Yii::$app->request->post('orderstate');
        $memberData = $data = $orderCombo = $orderDetail = [];
        try {
//            if (!$this->validate()) {
//                throw new Exception('数据填写有误');
//            }

            if (!$post['combo_id']) {
                throw new Exception('请选择套系');
            }

            $id = intval($post['id']);
            $data = [];
            if (!empty($id)) {
                $data = array_merge($data, $this->getSaveData('edit', $post));
                $data['update_time'] = date('Y-m-d H:i:s');
                $data['orderstate'] = $orderstate;
                $res = static::updateDataWithLog($data, ['id' => $id]);
                if ($res === false) {
                    throw new Exception('更新失败');
                }
            } else {
                //添加订单
                if (!$this->validate()) {
                    throw new Exception('数据填写有误');
                }
                if (!$post['member_id']) {
                    throw new Exception('请选择会员');
                }
                $data = array_merge($data, $this->getSaveData('add', $post));

                //更新会员表
                $memberInfo = Member::getOneInfoById($post['member_id'], 'total_consume,integral,valid_money');
                $memberData['update_time'] = date('Y-m-d H:i:s');
                $memberData['total_consume'] = bcadd($data['gathering_money'], $memberInfo['total_consume'],
                    4);//总消费是实收+原先的消费
                $memberData['integral'] = $data['integral'] + $memberInfo['integral'];
                //如果是余额支付的话，则需要扣除会员余额
                if ($post['pay_type'] == Status::MEMBER_ORDER_PAY_TYPE_VALID_MONEY) {
                    //判断余额是否充足
                    if ($memberInfo['valid_money'] < $data['gathering_money']) {
                        throw new Exception('余额不足，请换其他支付方式');
                    } else {
                        $memberData['valid_money'] = bcsub($memberInfo['valid_money'], $data['gathering_money'], 4);
                    }
                }
                $memberModel = new Member();
                $res = $memberModel->updateDataWithLog($memberData, ['id' => $post['member_id']]);
                if ($res === false) {
                    throw new Exception('更新会员失败');
                }

                $comboId = $post['combo_id'];
                $number = $post['number'] == 1;//默认数量为1
                $comboArray = Combo::getByWhere([
                    'is_delete' => Status::COMBO_NOT_DELETE,
                    'business_id' => Common::getBusinessId()
                ], [
                    'id',
                    'goods_content',
                    'combo_name',
                    'combo_type',
                    'combo_price',
                    'combo_integral',
                    'combo_discount',
                    'goods_content',
                    'combo_content'
                ]);
                $comboInfo = Functions::extractKey($comboArray, 'id');

                $goodsArray = AbGoods::getByWhere(['AB_id' => Common::getBusinessId()], [
                    'id',
                    'goods_p',
                    'goods_code',
                    'goods_name',
                    'goods_category',
                    'goods_price',
                    'goods_cost',
                    'goods_discount',
                    'goods_color',
                    'goods_size',
                    'goods_texture',
                    'goods_style',
                    'goods_type'
                ]);
                $goodsInfo = Functions::extractKey($goodsArray, 'id', '');

                $comboType = $comboInfo[$comboId]['combo_type'];
                //添加订单member_order
                $data['order_number'] = $this->getMemberOrderNum();
                $data['create_time'] = date('Y-m-d H:i:s');
                $data['business_id'] = Common::getBusinessId();
                $data['pay_time'] = $data['create_time'];
                $data['total_money'] = $post['gathering_money'];
                $data['combo_id'] = $post['combo_id'];
                $data['order_type'] = $comboType;
                $data['orderstate'] = $orderstate;

                //判断收的全款  还是定金
                switch ($post['gathering_fund']) {
                    case Status::MEMBER_ORDER_GATHERING_FUND_FULL : //全款
                        $data['final_payment'] = 0;//尾款为0
                        $data['total_money'] = $post['price'];//实收是总金额
                        break;
                    case Status::MEMBER_ORDER_GATHERING_FUND_EARNEST ://定金
                        $data['final_payment'] = bcsub($post['price'], $post['earnest'], 4);//总收款-定金
                        $data['total_money'] = $post['earnest'];//实收是定金
                        break;
                }
                $res = static::insertDataWithLog($data);
                if ($res === false) {
                    throw new Exception('添加订单失败');
                }
                $res = ABStatement::recordStatement(Status::AB_STATEMENT_TYPE_ORDER_FIRST_PAY, $post['gathering_money'],
                    $data['order_number'] . '的订单首付');
                if ($res !== true) {
                    throw new Exception('添加流水失败');
                }
                //添加订单combo表  member_order_combo 根据数量和套系类型来判断 添加的数据
                $orderCombo['order_number'] = $data['order_number'];
                $orderCombo['create_time'] = date('Y-m-d H:i:s');
                $orderCombo['mark'] = $data['mark'];
                $orderCombo['member_id'] = $post['member_id'];

                $orderComboModel = new MemberOrderCombo();
                $orderDetailModel = new MemberOrderDetail();
                if ($comboType == Status::COMBO_TYPE_GENERAL) {
                    //普通套系列表
                    for ($i = 1; $i <= (int)$number; $i++) {
                        $orderCombo['combo_order_number'] = $orderComboModel->getMemberOrderComboNum();
                        $orderCombo['business_id'] = static::getBusinessId();
                        $orderCombo['combo_name'] = $comboInfo[$comboId]['combo_name'];
                        $orderCombo['combo_id'] = $comboInfo[$comboId]['id'];
                        $orderCombo['price'] = $comboInfo[$comboId]['combo_price'];
                        $orderCombo['integral'] = $comboInfo[$comboId]['combo_integral'];
                        $orderCombo['discount'] = $comboInfo[$comboId]['combo_discount'];
                        $res = $orderComboModel->insertDataWithLog($orderCombo);
                        if ($res === false) {
                            throw new Exception('添加订单套系失败');
                        }
                        //商品列表
                        $goodsContent = $comboInfo[$comboId]['goods_content'];
                        $goodsContentArray = explode(',', $goodsContent);

                        $orderDetail['combo_order_number'] = $orderCombo['combo_order_number'];
                        $orderDetail['create_time'] = date('Y-m-d H:i:s');
                        $orderDetail['member_id'] = $post['member_id'];
                        //添加订单order_detail表  member_order_detail 添加详细的商品信息
                        foreach ($goodsContentArray as $k => $v) {
                            $orderDetail['goods_code'] = $goodsInfo[$v]['goods_code'];
                            $orderDetail['goods_name'] = $goodsInfo[$v]['goods_name'];
                            $orderDetail['goods_category'] = $goodsInfo[$v]['goods_category'];
                            $orderDetail['business_id'] = static::getBusinessId();
                            $orderDetail['goods_price'] = $goodsInfo[$v]['goods_price'];
                            $orderDetail['goods_cost'] = $goodsInfo[$v]['goods_cost'];
                            $orderDetail['goods_discount'] = $goodsInfo[$v]['goods_discount'];
                            $orderDetail['goods_color'] = $goodsInfo[$v]['goods_color'];
                            $orderDetail['goods_size'] = $goodsInfo[$v]['goods_size'];
                            $orderDetail['goods_texture'] = $goodsInfo[$v]['goods_texture'];
                            $orderDetail['goods_style'] = $goodsInfo[$v]['goods_style'];
                            $orderDetail['goods_type'] = $goodsInfo[$v]['goods_type'];
                            $orderDetail['goods_p'] = $goodsInfo[$v]['goods_p'];
                            $res = $orderDetailModel->insertDataWithLog($orderDetail);
                            if ($res === false) {
                                throw new Exception('添加订单详情失败');
                            }
                        }

                    }
                } else {
                    //成长套系
                    $comboContent = $comboInfo[$comboId]['combo_content'];
                    $comboContentArray = explode(',', $comboContent);

                    foreach ($comboContentArray as $k => $v) {
                        for ($i = 1; $i <= (int)$number; $i++) {
                            $orderCombo['combo_order_number'] = $orderComboModel->getMemberOrderComboNum();
                            $orderCombo['combo_name'] = $comboInfo[$v]['combo_name'];
                            $orderCombo['combo_id'] = $comboInfo[$v]['id'];
                            $orderCombo['business_id'] = static::getBusinessId();
                            $orderCombo['price'] = $comboInfo[$v]['combo_price'];
                            $orderCombo['integral'] = $comboInfo[$v]['combo_integral'];
                            $orderCombo['discount'] = $comboInfo[$v]['combo_discount'];
                            $res = $orderComboModel->insertDataWithLog($orderCombo);
                            if ($res === false) {
                                throw new Exception('添加失败');
                            }

                            //添加订单order_detail表  member_order_detail 添加详细的商品信息
                            $goodsContent = $comboInfo[$v]['goods_content'];
                            $goodsContentArray = explode(',', $goodsContent);
                            $orderDetail['combo_order_number'] = $orderCombo['combo_order_number'];
                            $orderDetail['create_time'] = date('Y-m-d H:i:s');

                            foreach ($goodsContentArray as $kk => $vv) {
                                $orderDetail['business_id'] = static::getBusinessId();
                                $orderDetail['goods_code'] = $goodsInfo[$vv]['goods_code'];
                                $orderDetail['goods_name'] = $goodsInfo[$vv]['goods_name'];
                                $orderDetail['goods_category'] = $goodsInfo[$vv]['goods_category'];
                                $orderDetail['goods_price'] = $goodsInfo[$vv]['goods_price'];
                                $orderDetail['goods_cost'] = $goodsInfo[$vv]['goods_cost'];
                                $orderDetail['goods_discount'] = $goodsInfo[$vv]['goods_discount'];
                                $orderDetail['goods_color'] = $goodsInfo[$vv]['goods_color'];
                                $orderDetail['goods_size'] = $goodsInfo[$vv]['goods_size'];
                                $orderDetail['goods_texture'] = $goodsInfo[$vv]['goods_texture'];
                                $orderDetail['goods_style'] = $goodsInfo[$vv]['goods_style'];
                                $orderDetail['goods_type'] = $goodsInfo[$vv]['goods_type'];
                                $res = $orderDetailModel->insertDataWithLog($orderDetail);
                                if ($res === false) {
                                    throw new Exception('添加订单详情失败');
                                }
                            }
                        }
                    }
                }
            }
            $trans->commit();
            return Functions::formatJson(1000, '操作成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(1001, $e->getMessage());
        }
    }

    public function getInfo($id = 0)
    {
        //获取订单信息
        $memberOrderInfo = self::getOneInfoById($id,
            'order_number,member_id,price,number,id,member_id,total_money,final_payment,earnest');
        $memberOrderInfo['price'] = floatval($memberOrderInfo['price']);
        $memberOrderInfo['total_money'] = floatval($memberOrderInfo['total_money']);
        $memberOrderInfo['final_payment'] = floatval($memberOrderInfo['final_payment']);
        $memberOrderInfo['earnest'] = floatval($memberOrderInfo['earnest']);
        //获取会员信息
        $memberInfo = Member::getOneInfoById($memberOrderInfo['member_id'], 'name,integral,valid_money');
        $memberInfo['valid_money'] = floatval($memberInfo['valid_money']);
        $info = [
            'order' => $memberOrderInfo,
            'member' => $memberInfo,
        ];
        return $info;
    }

    public function getInfoByorderID($order_number = 0)
    {
        //获取订单信息
        $memberOrderInfo = self::getOneInfoByOrderId($order_number,
            'order_number,member_id,price,number,id,member_id,total_money,final_payment,earnest');
        $memberOrderInfo['price'] = floatval($memberOrderInfo['price']);
        $memberOrderInfo['total_money'] = floatval($memberOrderInfo['total_money']);
        $memberOrderInfo['final_payment'] = floatval($memberOrderInfo['final_payment']);
        $memberOrderInfo['earnest'] = floatval($memberOrderInfo['earnest']);
        //获取会员信息
        $memberInfo = Member::getOneInfoById($memberOrderInfo['member_id'], 'name,integral,valid_money');
        $memberInfo['valid_money'] = floatval($memberInfo['valid_money']);
        $info = [
            'order' => $memberOrderInfo,
            'member' => $memberInfo,
        ];
        return $info;
    }

    /**
     * 添加商品
     * @return array
     */
    public function addShop()
    {
        $trans = Yii::$app->db->beginTransaction();
        $post = Yii::$app->request->post();
        $goodsInfo = AbGoods::getByWhere(['goods_code' => $post['goods_code']], [
            'id',
            'goods_p',
            'goods_code',
            'goods_name',
            'goods_category',
            'goods_price',
            'goods_cost',
            'goods_discount',
            'goods_color',
            'goods_size',
            'goods_texture',
            'goods_style',
            'goods_type'
        ]);
        //添加订单order_detail表  member_order_detail 添加详细的商品信息
        $orderDetail['combo_order_number'] = $post['combo_order_number'];
        $orderDetail['create_time'] = date('Y-m-d H:i:s');
        $orderDetail['member_id'] = $post['member_id'];
        $orderDetail['goods_code'] = $goodsInfo[0]['goods_code'];
        $orderDetail['goods_name'] = $goodsInfo[0]['goods_name'];
        $orderDetail['goods_category'] = $goodsInfo[0]['goods_category'];
        $orderDetail['business_id'] = static::getBusinessId();
        $orderDetail['goods_price'] = $goodsInfo[0]['goods_price'];
        $orderDetail['goods_cost'] = $goodsInfo[0]['goods_cost'];
        $orderDetail['goods_discount'] = $goodsInfo[0]['goods_discount'];
        $orderDetail['goods_color'] = $goodsInfo[0]['goods_color'];
        $orderDetail['goods_size'] = $goodsInfo[0]['goods_size'];
        $orderDetail['goods_texture'] = $goodsInfo[0]['goods_texture'];
        $orderDetail['goods_style'] = $goodsInfo[0]['goods_style'];
        $orderDetail['goods_type'] = $goodsInfo[0]['goods_type'];
        $orderDetail['goods_p'] = $goodsInfo[0]['goods_p'];
        $orderDetailModel = new MemberOrderDetail();
        $res = $orderDetailModel->insertDataWithLog($orderDetail);
        if ($res === false) {
            throw new Exception('添加订单详情失败');
        }
        $trans->commit();
        return Functions::formatJson(1000, '操作成功');
    }

    /**
     * 删除订单商品
     * @return array
     */
    public function deleteShop()
    {
        $trans = Yii::$app->db->beginTransaction();
        try {
            $post = Yii::$app->request->post();
            $id = intval($post['id']);
            if (!empty($id)) {
                $orderDetailModel = new MemberOrderDetail();
                $res = $orderDetailModel->delByWhere(['id' => $id]);
                if ($res === false) {
                    throw new Exception('删除失败');
                }
            } else {
                throw new Exception('选择要删除的商品');
            }
            $trans->commit();
            return Functions::formatJson(1000, '删除成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(2000, $e->getMessage());
        }
    }

    /**
     * 编辑订单商品
     * @return array
     */
    public function saveEditShop()
    {
        $trans = Yii::$app->db->beginTransaction();
        $post = Yii::$app->request->post();

        if (isset($post) && count($post) > 0) {
            $order_detail_idArr = $post['order_detail_id'];
            unset($post['order_detail_id']);
            if (isset($post) && count($post) > 0) {
                $orderDetail = array();
                foreach ($post as $key => $value) {
                    foreach ($value as $k => $v) {
                        $orderDetail[$k][$key] = $v;
                    }
                }
            }
        }
        $orderDetailModel = new MemberOrderDetail();
        foreach ($orderDetail as $key => $value) {
            $res = $orderDetailModel->updateData($value, ['id' => $order_detail_idArr[$key]]);
        }

        $trans->commit();
        return Functions::formatJson(1000, '操作成功');
    }


    /**
     * 逻辑删除订单
     * @return array
     */
    public function doDelete()
    {
        $trans = Yii::$app->db->beginTransaction();
        try {
            $post = Yii::$app->request->post();
            $id = intval($post['id']);
            $order_number = $post['order_number'];
            $data = [];
            if (!empty($id)) {
                $memberOrderInfo = self::getOneInfoById($id, 'member_id,integral,total_money,order_type');
                $memberId = $memberOrderInfo['member_id'];
                //删除订单需要把该订单对应的用户总消费，积分和 余额 相应的变化
                //还没判断
                //更新用户表
                $memberModel = new Member();
                $memberInfo = $memberModel->getOneInfoById($memberId, 'total_consume,integral,valid_money');
                $memberData['total_consume'] = bcsub($memberInfo['total_consume'], $memberOrderInfo['total_money'], 4);
                $memberData['valid_money'] = bcadd($memberInfo['valid_money'], $memberOrderInfo['total_money'], 4);
                $memberData['integral'] = bcsub($memberInfo['integral'], $memberOrderInfo['integral'], 4);
                $res = $memberModel->updateDataWithLog($memberData, ['id' => $memberId]);
                if ($res === false) {
                    throw new Exception('删除失败');
                }
                $data['update_time'] = date('Y-m-d H:i:s');
                $data['is_delete'] = Status::MEMBER_ORDER_DELETE_YES;
                $res = static::updateDataWithLog($data, ['id' => $id]);
                if ($res === false) {
                    throw new Exception('删除失败');
                }
                //成长套餐删除member_order_comb表
                $memberModelCom = new MemberOrderCombo();
                if ($memberOrderInfo['order_type'] == '2') {
                    $res = $memberModelCom->updateDataWithLog($data, ['order_number' => $order_number]);
                }
                $comboOrderInfo = $memberModelCom->getByWhere(['order_number' => $order_number]);
                if (count($comboOrderInfo) > 0) {
                    $abCalendarPlan = new AbCalendarPlan();
                    foreach ($comboOrderInfo as $k => $v) {
                        $res = $abCalendarPlan->delByWhere(['AB_order_number' => $v['combo_order_number']]);
                    }
                }
            } else {
                throw new Exception('选择要删除的订单');
            }
            $trans->commit();
            return Functions::formatJson(1000, '删除成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(2000, $e->getMessage());
        }
    }


    /**
     * 逻辑删除订单member_order_comb
     * @return array
     */
    public function comDelete()
    {
        $trans = Yii::$app->db->beginTransaction();
        try {
            $post = Yii::$app->request->post();
            $combo_order_number = $post['combo_order_number'];
            $data = [];
            if (!empty($combo_order_number)) {
//                $memberOrderInfo = self::getByWhere($id,'member_id,integral,total_money,order_type');
//                $memberId = $memberOrderInfo['member_id'];
//                //删除订单需要把该订单对应的用户总消费，积分和 余额 相应的变化
//                //还没判断
//                //更新用户表
//                $memberModel = new Member();
//                $memberInfo = $memberModel->getOneInfoById($memberId,'total_consume,integral,valid_money');
//                $memberData['total_consume'] = bcsub($memberInfo['total_consume'],$memberOrderInfo['total_money'], 4);
//                $memberData['valid_money'] = bcadd($memberInfo['valid_money'],$memberOrderInfo['total_money'], 4);
//                $memberData['integral'] = bcsub($memberInfo['integral'],$memberOrderInfo['integral'], 4);
//                $res = $memberModel->updateDataWithLog($memberData,['id' => $memberId]);
//                if ($res === false) {
//                    throw new Exception('删除失败');
//                }
                $data['update_time'] = date('Y-m-d H:i:s');
                $data['is_delete'] = Status::MEMBER_ORDER_DELETE_YES;
//                $res = static::updateDataWithLog($data, ['id' => $id]);
//                if ($res === false) {
//                    throw new Exception('删除失败');
//                }
                //成长套餐删除member_order_comb表
                $memberModelCom = new MemberOrderCombo();
                $res = $memberModelCom->updateDataWithLog($data, ['combo_order_number' => $combo_order_number]);
                if ($res === false) {
                    throw new Exception('删除失败');
                }

                if ($combo_order_number) {
                    $abCalendarPlan = new AbCalendarPlan();
                    $res = $abCalendarPlan->delByWhere(['AB_order_number' => $combo_order_number]);
                }

            } else {
                throw new Exception('选择要删除的订单');
            }
            $trans->commit();
            return Functions::formatJson(1000, '删除成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(2000, $e->getMessage());
        }
    }


    /**
     * 订单退款
     * @return array
     */
    public function refund()
    {
        $post = Yii::$app->request->post();
        $memberId = $post['memberId'];
        $orderId = $post['orderId'];
        $orderData = $memberData = [];
        $trans = Yii::$app->db->beginTransaction();
        try {
            if (!$memberId) {
                throw new Exception('未找到用户');
            }

            if (!$orderId) {
                throw new Exception('未找到订单');
            }
            $memberOrderInfo = self::getOneInfoById($orderId, 'total_money,order_number');
            if ($post['refund_money'] > $memberOrderInfo['total_money']) {
                throw new Exception('退款金额不能大于收款金额');
            }

            $orderInfo = self::getOneInfoById($orderId, 'total_money,order_number');
            $type = intval($post['type']);

            //member_order表
            $orderData['is_delete'] = Status::MEMBER_ORDER_DELETE_YES;//退款相当于删除本次订单
            $orderData['update_time'] = date('Y-m-d H:i:s');
            $orderData['refund_money'] = trim($post['refund_money']);
            $orderData['refund_type'] = $type;
            $orderData['refund_time'] = date('Y-m-d H:i:s');
            $orderData['refund_reason'] = trim($post['refund_reason']);
            $orderData['total_money'] = bcsub($orderInfo['total_money'], $post['refund_money'], 4);

            $memberInfo = Member::getOneInfoById($memberId, 'integral,valid_money,total_consume');
            $memberData['integral'] = $memberInfo['integral'] - $post['refund_integral'];
            $memberData['total_consume'] = bcsub($memberInfo['total_consume'], $post['refund_money'], 4);
            if ($type == Status::MEMBER_ORDER_REFUND_TYPE_VALID_MONEY) {
                $memberData['valid_money'] = bcadd($memberInfo['valid_money'], $post['refund_money'], 4);
            }
            $res = static::updateDataWithLog($orderData, ['id' => $orderId]);
            if ($res === false) {
                throw new Exception('订单退款失败');
            }

            //添加退款订单记录
            $orderRefundLog['business_id'] = Common::getBusinessId();
            $orderRefundLog['member_id'] = $memberId;
            $orderRefundLog['order_number'] = $orderInfo['order_number'];
            $orderRefundLog['order_number'] = $orderInfo['order_number'];
            $orderRefundLog['order_id'] = $orderId;
            $orderRefundLog['refund_type'] = $type;
            $orderRefundLog['refund_money'] = trim($post['refund_money']);
            $orderRefundLog['refund_integral'] = trim($post['refund_integral']);
            $orderRefundLog['refund_reason'] = trim($post['refund_reason']);
            $orderRefundLog['create_time'] = date('Y-m-d H:i:s');

            $memberOrderRefundLogModel = new MemberOrderRefund();
            $res = $memberOrderRefundLogModel->insertDataWithLog($orderRefundLog);
            if ($res === false) {
                throw new Exception('添加退款记录失败');
            }

            //更新用户表
            $memberModel = new Member();
            $res = $memberModel->updateDataWithLog($memberData, ['id' => $memberId]);
            if ($res === false) {
                throw new Exception('会员退款失败');
            }

            $res = ABStatement::recordStatement(Status::AB_STATEMENT_TYPE_ORDER_REFUND, $post['refund_money'],
                $memberOrderInfo['order_number'] . '的订单退款');
            if ($res !== true) {
                throw new Exception('添加流水失败');
            }

            $trans->commit();
            return Functions::formatJson(1000, '操作成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(1001, $e->getMessage());
        }
    }

    /**
     * 二销收款
     * @return array
     */
    public function secondGathering()
    {
        $post = Yii::$app->request->post();
        $memberId = $post['memberId'];
        $orderId = $post['orderId'];
        $orderSecondData = $memberData = [];
        $trans = Yii::$app->db->beginTransaction();
        if (isset($post['weikuan']) && $post['weikuan'] == 'weikuan') {
            //4代表尾款
            $gathering_fund = 4;
        } else {
            $gathering_fund = $post['gathering_fund'];
        }
        try {
            if (!$memberId) {
                throw new Exception('未找到用户');
            }

            if (!$orderId) {
                throw new Exception('未找到订单');
            }
            if (floatval($post['second_money']) < 0) {
                throw new Exception('金额必须为正数');
            }
            $second_money = $post['second_money'];
            //用户表更新总消费
            $memberInfo = Member::getOneInfoById($memberId, 'total_consume,valid_money');
            $memberData['total_consume'] = bcadd($memberInfo['total_consume'], $post['second_money'], 4);
            $memberData['update_time'] = date('Y-m-d H:i:s');

            //如果是余额支付的话，则需要扣除会员余额
            if ($post['pay_type'] == Status::MEMBER_ORDER_PAY_TYPE_VALID_MONEY) {
                //判断余额是否充足
                if ($memberInfo['valid_money'] < $post['second_money']) {
                    throw new Exception('余额不足，请换其他支付方式');
                } else {
                    $memberData['valid_money'] = bcsub($memberInfo['valid_money'], $post['second_money'], 4);
                }
            }
            $memberModel = new Member();
            $res = $memberModel->updateDataWithLog($memberData, ['id' => $memberId]);
            if ($res === false) {
                throw new Exception('收款失败');
            }

            //订单order表
            $orderInfo = self::getOneInfoById($orderId, 'total_money,order_number,final_payment,order_number');
            $orderData['total_money'] = bcadd($orderInfo['total_money'], $post['second_money'], 4);
            $orderData['update_time'] = date('Y-m-d H:i:s');

            //判断收的是否是尾款
            if ($gathering_fund == Status::MEMBER_ORDER_SECOND_GATHERING_FUND_FINAL_PAYMENT) {
                $orderData['final_payment'] = bcsub($orderInfo['final_payment'], $post['second_money'], 4);
            }
            $res = static::updateDataWithLog($orderData, ['id' => $orderId]);
            if ($res === false) {
                throw new Exception('收款失败');
            }

            //订单second表
            $orderSecondData['business_id'] = Common::getBusinessId();
            $orderSecondData['order_id'] = $orderId;
            $orderSecondData['create_time'] = date('Y-m-d H:i:s');
            $orderSecondData['second_gathering_fund'] = $gathering_fund;
            $orderSecondData['second_gathering_money'] = $second_money;
            $orderSecondData['second_pay_type'] = $post['pay_type'];;
            $orderSecondData['mark'] = $post['mark'];
            //$orderSecondData['order_number'] = trim($orderInfo['order_number']);
            //$orderSecondData['member_id'] = intval($memberId);
            $memberOrderSecondModel = new MemberOrderSecond();
            $res = $memberOrderSecondModel->insertDataWithLog($orderSecondData);
            if ($res === false) {
                throw new Exception('收款失败');
            }
            $res = ABStatement::recordStatement(Status::AB_STATEMENT_TYPE_SECOND_SALE, $post['second_money'],
                $orderInfo['order_number'] . '的订单二销收款');
            if ($res !== true) {
                throw new Exception('添加流水失败');
            }
            $trans->commit();
            return Functions::formatJson(1000, '操作成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(1001, $e->getMessage());
        }
    }

    /**
     * 日历排项获取信息
     * @return array
     */
    public function quoteOrderInfo()
    {
        try {
            $orderNumber = Yii::$app->request->post('orderNumber');
            if (empty($orderNumber)) {
                throw new Exception('订单信息错误');
            }
            //11位是手机号否则是订单号
            if (strlen($orderNumber) == '11') {
                $memberModel = new Member();
                $wh['tel'] = $orderNumber;
                $memberInfo = $memberModel->getMemberInfoByWhere($wh);
                if ($memberInfo['code'] == 1000 && !empty($memberInfo['data'])) {
                    $where['member_id'] = $memberInfo['data']['id'];
                } else {
                    $where['combo_order_number'] = $orderNumber;
                }
            } else {
                $where['combo_order_number'] = $orderNumber;
            }
            $where['business_id'] = static::getBusinessId();
            $comboOrderInfo = MemberOrderCombo::getByWhere($where,
                ['combo_name', 'price', 'order_number', 'combo_order_number', 'combo_id']);
            if (empty($comboOrderInfo)) {
                throw new Exception('订单信息不存在');
            }
            foreach ($comboOrderInfo as $key => $value) {
                //获取套系信息
                $comboInfo = Combo::getOneByWhere(['id' => $value['combo_id']]);
                $comboTypeMap = Status::comboTypeMap();
                $goodsWhere['id'] = explode(",", $comboInfo['goods_content']);
                $goodsInfo = AbGoods::getByWhere($goodsWhere, ['id', 'goods_name']);
                $goodsInfo = Functions::extractKey($goodsInfo, 'id', 'goods_name');

                //获取总套餐信息
                $orderWhere['order_number'] = $value['order_number'];
                $orderInfo = MemberOrder::getOneByWhere($orderWhere);
                $memberWhere['id'] = $orderInfo['member_id'];
                $memberInfo = Member::getOneByWhere($memberWhere);
                $comboOrderInfo[$key]['name'] = $memberInfo['name'];
                $comboOrderInfo[$key]['age'] = $memberInfo['age'];
                $comboOrderInfo[$key]['sex'] = $memberInfo['sex'] == 1 ? '男' : '女';
                $comboOrderInfo[$key]['clothe'] = $comboInfo['combo_clothing'];
            }

            return Functions::formatJson(1000, '', $comboOrderInfo);
        } catch (Exception $e) {
            return Functions::formatJson(2000, $e->getMessage());
        }
    }

    /**
     * 导出excel
     */
    public function exportExcel()
    {
        $list = [];
        $where['business_id'] = Common::getBusinessId();
        $where['is_delete'] = Status::MEMBER_ORDER_DELETE_NO;
        $list['data'] = self::find()->where($where)->asArray()->all();
        $list = $this->handelInit($list);
        $list = $list['data'];
        Excel::export([
            'models' => $list,
            'fileName' => date('Ymd') . '导出会员订单信息',
            'columns' => [
                'member_id',
                'combo_name',
                'business_id',
                'order_number',
                'price',
                'earnest',
                'number',
                'discount',
                'integral',
                'final_payment',
                'create_time',
                'pay_type',
                'gathering_fund',
                'gathering_money',
                'order_type',
                'mark',
            ], //没有头工作,因为头会得到标签的属性标签
            'headers' => [
                'member_id' => '会员名称',
                'combo_name' => '套系名称',
                'business_id' => '商户号',
                'order_number' => '订单编号',
                'price' => '价格',
                'earnest' => '定金',
                'number' => '数量',
                'discount' => '折扣',
                'integral' => '积分',
                'final_payment' => '尾款',
                'create_time' => '创建时间',
                'pay_type' => '支付方式',
                'gathering_fund' => '收款款项',
                'gathering_money' => '收款金额',
                'order_type' => '订单类型',
                'mark' => '备注',
            ],
        ]);
    }

    public function getRegisterCount()
    {
        $sum = 0;
        if (count($this->combos)) {
            foreach ($this->combos as $combo) {
                $sum += $combo->register_count;
            }
        }
        return $sum;
    }

    public function getCombos()
    {
        return $this->hasMany(Combo::class, ['id' => 'combo_id'])->via('comboOrders');
    }

    public function getComboOrders()
    {
        return $this->hasMany(MemberOrderCombo::class, ['order_number' => 'order_number']);
    }
}
