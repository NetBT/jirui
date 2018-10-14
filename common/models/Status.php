<?php
namespace common\models;

use backend\models\AbGoods;
use backend\models\Goods;

class Status
{

    #菜单表
    const MODULE_LIST_SUCCESS = 1;      #启用
    const MODULE_LIST_DISABLED = 2;     #禁用

    const MODULE_TYPE_HEADQUARTERS = 'headquarters'; #总部
    const MODULE_TYPE_FRANCHISEE = 'franchisee';  #加盟商
    const MODULE_TYPE_GUEST = 'guest';   #游客

    const MODULE_EXPORT_EXCEL_NO = 1; #不导出excel
    const MODULE_EXPORT_EXCEL_YES = 2;#导出excel

    #employee_info
    const EMPLOYEE_STATUS_DELETED = 2;     #禁用
    const EMPLOYEE_STATUS_ACTIVE = 1;      #启用

    #user_info
    const USER_INFO_STATUS_NORMAL = 1;  #启用
    const USER_INFO_STATUS_DISABLED = 2;#禁用

    #employee_post
    const EMPLOYEE_POST_SUCCESS = 1;       #启用
    const EMPLOYEE_POST_DISABLED = 2;      #禁用
    public static function employeePostStatusMap () {
        return [
            static::EMPLOYEE_POST_SUCCESS => '启用',
            static::EMPLOYEE_POST_DISABLED => '禁用',
        ];
    }

    #employee_post
    const AB_POST_SUCCESS = 1;       #启用
    const AB_POST_DISABLED = 2;      #禁用
    public static function abPostStatusMap () {
        return [
            static::AB_POST_SUCCESS => '启用',
            static::AB_POST_DISABLED => '禁用',
        ];
    }
    #通用的性别
    const SEXY_MAN = 1;       #男
    const SEXY_WOMAN = 2;     #女
    public static function sexyMap () {
        return [
            static::SEXY_MAN => '男',
            static::SEXY_WOMAN => '女',
        ];
    }

    #消息状态
//    const MESSAGE_STATUS_WD = 1;
//    const MESSAGE_STATUS_YD = 2;
//    const MESSAGE_STATUS_YHF = 3;
//    const MESSAGE_STATUS_SL = 4;
//    public static function messageStatusMap() {
//        return [
//            static::MESSAGE_STATUS_WD => '未读',
//            static::MESSAGE_STATUS_YD => '已读',
//            static::MESSAGE_STATUS_YHF => '已回复',
//            static::MESSAGE_STATUS_SL => '受理',
//        ];
//    }
    const MESSAGE_STATUS_WHF = 1;
    const MESSAGE_STATUS_YHF = 2;
    public static function messageStatusMap() {
        return [
            static::MESSAGE_STATUS_WHF => '未回复',
            static::MESSAGE_STATUS_YHF => '已回复',
        ];
    }

    #消息类型
    const MESSAGE_TYPE_XTJY = 1;
    const MESSAGE_TYPE_CPTS = 2;
    const MESSAGE_TYPE_GZBX = 3;
    const MESSAGE_TYPE_YQSQ = 4;
    const MESSAGE_TYPE_XTCZ = 5;
    public static function messageTypeMap() {
        return [
            static::MESSAGE_TYPE_XTJY => '系统建议',
            static::MESSAGE_TYPE_CPTS => '产品投诉',
            static::MESSAGE_TYPE_GZBX => '故障报修',
        ];
    }
    public static function messageTypeCommonMap() {
        return [
            static::MESSAGE_TYPE_XTJY => '系统建议',
            static::MESSAGE_TYPE_CPTS => '产品投诉',
            static::MESSAGE_TYPE_GZBX => '故障报修',
            static::MESSAGE_TYPE_YQSQ => '延期申请',
            static::MESSAGE_TYPE_XTCZ => '系统充值',
        ];
    }

    #会员状态
    const MEMBER_NOT_DELETE = 1;
    const MEMBER_IS_DELETE = 2;
    public static function memberDeleteMap() {
        return [
            static::MEMBER_NOT_DELETE => '已删除',
            static::MEMBER_IS_DELETE => '未删除',
        ];
    }

    #会员积分
    const MEMBER_INTEGRAL_ADD = 1;
    const MEMBER_INTEGRAL_SUBTRACT = 2;
    public static function memberIntegralMap() {
        return [
            static::MEMBER_INTEGRAL_ADD => '增加积分',
            static::MEMBER_INTEGRAL_SUBTRACT => '扣除积分',
        ];
    }

    #会员来源
    const MEMBER_SOURCE_LGKTJ = 1;
    const MEMBER_SOURCE_ZRJD = 2;
    const MEMBER_SOURCE_DHZX = 3;
    const MEMBER_SOURCE_DZDP = 4;
    const MEMBER_SOURCE_TGS = 5;
    const MEMBER_SOURCE_QT = 6;
    public static function memberSourceMap() {
        return [
            static::MEMBER_SOURCE_LGKTJ => '老顾客推荐',
            static::MEMBER_SOURCE_ZRJD => '自然进店',
            static::MEMBER_SOURCE_DHZX => '电话咨询',
            static::MEMBER_SOURCE_DZDP => '大众点评',
            static::MEMBER_SOURCE_TGS => '推广商',
            static::MEMBER_SOURCE_QT => '其他',
        ];
    }

    #套系类型
    const COMBO_TYPE_GENERAL = 1;
    const COMBO_TYPE_GROW = 2;
    public static function comboTypeMap() {
        return [
            static::COMBO_TYPE_GENERAL => '普通套系',
            static::COMBO_TYPE_GROW => '成长套系',
        ];
    }

    #套系删除
    const COMBO_NOT_DELETE = 1;
    const COMBO_IS_DELETE = 2;
    public static function comboDeleteMap() {
        return [
            static::COMBO_NOT_DELETE => '未删除',
            static::COMBO_IS_DELETE => '已删除',
        ];
    }
    #会员订单删除状态
    const MEMBER_ORDER_DELETE_NO = 1;#未删除
    const MEMBER_ORDER_DELETE_YES = 2;#已删除

    #会员套系订单删除状态
    const MEMBER_COMBO_ORDER_DELETE_NO = 1;#未删除
    const MEMBER_COMBO_ORDER_DELETE_YES = 2;#已删除

    #会员订单套系类型
    const MEMBER_ORDER_COMBO_TYPE_NORMAL = 1;
    const MEMBER_ORDER_COMBO_TYPE_GROW_UP = 2;
    public static function memberOrderTypeMap() {
        return [
            static::MEMBER_ORDER_COMBO_TYPE_NORMAL => '普通套系',
            static::MEMBER_ORDER_COMBO_TYPE_GROW_UP => '成长套系',
        ];
    }


    #会员订单收款款项
    const MEMBER_ORDER_GATHERING_FUND_EARNEST = 1;
    const MEMBER_ORDER_GATHERING_FUND_FULL = 2;
    const MEMBER_ORDER_GATHERING_FUND_TAIL = 3;
    const MEMBER_ORDER_GATHERING_FUND_SELF = 4;
    public static function memberOrderGatheringFundMap() {
        return [
            static::MEMBER_ORDER_GATHERING_FUND_EARNEST => '定金',
            static::MEMBER_ORDER_GATHERING_FUND_FULL => '全款',
//            static::MEMBER_ORDER_GATHERING_FUND_TAIL => '尾款',
//            static::MEMBER_ORDER_GATHERING_FUND_SELF => '自定义',
        ];
    }

    #选择订单编码方式
    const MEMBER_ORDER_NUMBER_TYPE_AUTO = 1;
    const MEMBER_ORDER_NUMBER_TYPE_HAND = 2;
    public static function memberOrderNumberTypeMap() {
        return [
            static::MEMBER_ORDER_NUMBER_TYPE_AUTO => '系统生成',
            static::MEMBER_ORDER_NUMBER_TYPE_HAND => '自动填写',
        ];
    }

    #会员订单支付方式
    const MEMBER_ORDER_PAY_TYPE_CASH = 1;
    const MEMBER_ORDER_PAY_TYPE_BANK = 2;
    const MEMBER_ORDER_PAY_TYPE_WECHAT = 3;
    const MEMBER_ORDER_PAY_TYPE_ALIPAY = 4;
    const MEMBER_ORDER_PAY_TYPE_VALID_MONEY = 4;
    public static function memberOrderPayTypeMap() {
        return [
            static::MEMBER_ORDER_PAY_TYPE_CASH => '现金',
            static::MEMBER_ORDER_PAY_TYPE_BANK => '银行卡',
            static::MEMBER_ORDER_PAY_TYPE_WECHAT => '微信',
            static::MEMBER_ORDER_PAY_TYPE_ALIPAY => '支付宝',
            static::MEMBER_ORDER_PAY_TYPE_VALID_MONEY => '余额',
        ];
    }

    #会员订单退款类型
    const MEMBER_ORDER_REFUND_TYPE_CASH = 1;
    const MEMBER_ORDER_REFUND_TYPE_VALID_MONEY = 2;
    public static function memberOrderRefundTypeMap() {
        return [
            static::MEMBER_ORDER_REFUND_TYPE_CASH => '现金退款',
            static::MEMBER_ORDER_REFUND_TYPE_VALID_MONEY => '余额退款',
        ];
    }

    #会员订单二次收款款项
    const MEMBER_ORDER_SECOND_GATHERING_FUND_ADD_PHOTO = 1;
    const MEMBER_ORDER_SECOND_GATHERING_FUND_PACKING_FILM = 2;
    const MEMBER_ORDER_SECOND_GATHERING_FUND_CLOTHING = 3;
    const MEMBER_ORDER_SECOND_GATHERING_FUND_FINAL_PAYMENT = 4;
    public static function memberOrderSecondGatheringFundMap() {
        return [
            static::MEMBER_ORDER_SECOND_GATHERING_FUND_ADD_PHOTO => '加片',
            static::MEMBER_ORDER_SECOND_GATHERING_FUND_PACKING_FILM => '打包底片',
            static::MEMBER_ORDER_SECOND_GATHERING_FUND_CLOTHING => '服装造型',
            /*static::MEMBER_ORDER_SECOND_GATHERING_FUND_FINAL_PAYMENT => '尾款',*/
        ];
    }

    #会员套系订单流程状态
    const MEMBER_ORDER_COMBO_NOT_SHOOT = 1;     #未拍摄
    const MEMBER_ORDER_COMBO_NOT_SELECT = 2;    #未选片
    const MEMBER_ORDER_COMBO_NOT_COMPOSITE = 3; #未后期制作
    const MEMBER_ORDER_COMBO_NOT_DEAL = 4;      #未成品处理
    const MEMBER_ORDER_COMBO_NOT_TAKE_PARK = 5; #未通知
    const MEMBER_ORDER_COMBO_NOT_SHOOT_FINISHED = 6;  #未拍完

    #会员订单是否排项
    const MEMBER_ORDER_PLAN_STATUS_YES = 2;
    const MEMBER_ORDER_PLAN_STATUS_NO = 1;
    public static function memberOrderComboPlanStatusMap() {
        return [
            static::MEMBER_ORDER_PLAN_STATUS_NO => '未排项',
            static::MEMBER_ORDER_PLAN_STATUS_YES => '已排项',
        ];
    }
    #会员订单拍摄
    const MEMBER_ORDER_SHOOT_STATUS_NO = 1;
    const MEMBER_ORDER_SHOOT_STATUS_ING = 2;
    const MEMBER_ORDER_SHOOT_STATUS_YES = 3;
    const MEMBER_ORDER_SHOOT_STATUS_NOT_FINISH = 4;
    public static function memberOrderComboShootStatusMap() {
        return [
            static::MEMBER_ORDER_SHOOT_STATUS_NO => '未拍摄',
            static::MEMBER_ORDER_SHOOT_STATUS_ING => '拍摄中',
            static::MEMBER_ORDER_SHOOT_STATUS_YES => '已拍摄',
            static::MEMBER_ORDER_SHOOT_STATUS_NOT_FINISH => '未拍完'
        ];
    }

    #会员订单选片
    const MEMBER_ORDER_SELECT_STATUS_NO = 1;
    const MEMBER_ORDER_SELECT_STATUS_YES = 3;
    const MEMBER_ORDER_SELECT_STATUS_ING = 2;
    public static function memberOrderComboSelectStatusMap() {
        return [
            static::MEMBER_ORDER_SELECT_STATUS_NO => '未选片',
            static::MEMBER_ORDER_SELECT_STATUS_YES => '已选片',
            static::MEMBER_ORDER_SELECT_STATUS_ING => '选片中',
        ];
    }

    #会员订单后期处理
    const MEMBER_ORDER_COMPOSITE_STATUS_WCL = 1;
    const MEMBER_ORDER_COMPOSITE_STATUS_JX = 2;
    const MEMBER_ORDER_COMPOSITE_STATUS_SJ = 3;
    const MEMBER_ORDER_COMPOSITE_STATUS_YFCJ = 4;
    const MEMBER_ORDER_COMPOSITE_STATUS_DONE = 5;
    public static function memberOrderComboCompositeStatusMap() {
        return [
            static::MEMBER_ORDER_COMPOSITE_STATUS_WCL => '未处理',
            static::MEMBER_ORDER_COMPOSITE_STATUS_JX => '精修',
            static::MEMBER_ORDER_COMPOSITE_STATUS_SJ => '设计',
            static::MEMBER_ORDER_COMPOSITE_STATUS_YFCJ => '已发厂家',
            static::MEMBER_ORDER_COMPOSITE_STATUS_DONE => '后期完成',
        ];
    }

    #会员订单理件
    const MEMBER_ORDER_DEAL_STATUS_NO = 1;
    const MEMBER_ORDER_DEAL_STATUS_ING = 2;
    const MEMBER_ORDER_DEAL_STATUS_YES = 3;
    public static function memberOrderComboDealStatusMap() {
        return [
            static::MEMBER_ORDER_DEAL_STATUS_NO => '未处理',
            static::MEMBER_ORDER_DEAL_STATUS_ING => '理件中',
            static::MEMBER_ORDER_DEAL_STATUS_YES => '已理件',
        ];
    }
    #会员订单order_detail详情理件状态
    const MEMBER_ORDER_DETAIL_DEAL_STATUS_WCL = 1;
    const MEMBER_ORDER_DETAIL_DEAL_STATUS_FC = 2;
    const MEMBER_ORDER_DETAIL_DEAL_STATUS_WC = 3;
    public static function memberOrderDetailDealStatusMap() {
        return [
            static::MEMBER_ORDER_DETAIL_DEAL_STATUS_WCL => '未理件',
            static::MEMBER_ORDER_DETAIL_DEAL_STATUS_FC => '返厂',
            static::MEMBER_ORDER_DETAIL_DEAL_STATUS_WC => '完成',
        ];
    }

    #会员订单取件
    const MEMBER_ORDER_TAKE_PARK_STATUS_NO = 1;
    const MEMBER_ORDER_TAKE_PARK_STATUS_ING = 2;
    const MEMBER_ORDER_TAKE_PARK_STATUS_YES = 3;
    public static function memberOrderComboTakeParkStatusMap() {
        return [
            static::MEMBER_ORDER_TAKE_PARK_STATUS_NO => '未通知',
            static::MEMBER_ORDER_TAKE_PARK_STATUS_ING => '通知(未取件)',
            static::MEMBER_ORDER_TAKE_PARK_STATUS_YES => '取件',
        ];
    }

    #员工提成类型
    const EMPLOYEE_RATE_TYPE_SELL = 1;
    const EMPLOYEE_RATE_TYPE_SHOOT= 2;
    const EMPLOYEE_RATE_TYPE_MAKE_UP = 3;
    public static function employeeRateTypeMap() {
        return [
            static::EMPLOYEE_RATE_TYPE_SELL => '销售提成',
            static::EMPLOYEE_RATE_TYPE_SHOOT => '拍摄提成',
            static::EMPLOYEE_RATE_TYPE_MAKE_UP => '化妆提成',
        ];
    }

    const USER_INFO_BLOOD_TYPE_A = 'A';
    const USER_INFO_BLOOD_TYPE_B = 'B';
    const USER_INFO_BLOOD_TYPE_O = 'O';
    const USER_INFO_BLOOD_TYPE_AB = 'AB';
    public static function userInfoBloodTypeMap() {
        return [
            static::USER_INFO_BLOOD_TYPE_A => 'A型',
            static::USER_INFO_BLOOD_TYPE_B => 'B型',
            static::USER_INFO_BLOOD_TYPE_O => 'O型',
            static::USER_INFO_BLOOD_TYPE_AB => 'AB型'
        ];
    }
    const USER_INFO_ACCOUNT_PHONE = 1;  #手机账户
    const USER_INFO_ACCOUNT_MAIL = 2;   #邮箱账户

    const USER_INFO_IS_ONLINE = 1;  #在线
    const USER_INFO_IS_OFFLINE = 2; #离线

    #user_login
    const USER_LOGIN_STATUS_NORMAL = 1; #正常
    const USER_LOGIN_STATUS_LOSS = 2;   #失效


    #jr_ab_info  -- 加盟商信息表
    const AB_STORE_STATUS_LOCK = 1;
    const AB_STORE_STATUS_UNLOCK = 2;

    public static function ABInfoStatusMap() {
        return [
            static::AB_STORE_STATUS_LOCK => '是',
            static::AB_STORE_STATUS_UNLOCK => '否',
        ];
    }
    const AB_STORE_TYPE_COMMON = 1;
    const AB_STORE_TYPE_ADVANCED = 2;

    public static function ABInfoTypeMap() {
        return [
            static::AB_STORE_TYPE_COMMON => '普通版',
            static::AB_STORE_TYPE_ADVANCED => '高级版',
        ];
    }

    const AB_IS_DELETE = 2;
    const AB_ID_NOT_DELETE = 1;

    #jr_ab_recharge
    const AB_RECHARGE_PAY_TYPE_WECHAT = 1;
    const AB_RECHARGE_PAY_TYPE_ALIPAY = 2;
    public static function AbRechargePayWayMap() {
        return [
            static::AB_RECHARGE_PAY_TYPE_WECHAT => '微信',
            static::AB_RECHARGE_PAY_TYPE_ALIPAY => '支付宝',
        ];
    }

    #jr_ab_ponepost
    const AB_POSTPONE_PAY_WAY_WECHAT = 1;
    const AB_POSTPONE_PAY_ALIPAY = 2;
    const AB_POSTPONE_PAY_BANK_TRANSFER = 3;
    const AB_POSTPONE_PAY_WAY_BALANCE = 4;
    public static function AbPostponePayWayMap() {
        return [
            static::AB_POSTPONE_PAY_WAY_WECHAT => '微信',
            static::AB_POSTPONE_PAY_ALIPAY => '支付宝',
            static::AB_POSTPONE_PAY_BANK_TRANSFER => '银行转账',
            static::AB_POSTPONE_PAY_WAY_BALANCE => '余额',
        ];
    }

    #jr_refund_money_log
    const HEAD_REFUND_TYPE_WECHAT = 1;
    const HEAD_REFUND_TYPE_ALIPAY = 2;
    const HEAD_REFUND_TYPE_BANK_TRANSFER = 3;
    const HEAD_REFUND_TYPE_BALANCE = 4;
    public static function HeadRefundMoneyTypeMap() {
        return [
            static::HEAD_REFUND_TYPE_WECHAT => '微信',
            static::HEAD_REFUND_TYPE_ALIPAY => '支付宝',
            static::HEAD_REFUND_TYPE_BANK_TRANSFER => '银行转账',
            static::HEAD_REFUND_TYPE_BALANCE => '余额',
        ];
    }

    const HEAD_REFUND_LOG_TYPE_MONEY = 1;
    const HEAD_REFUND_LOG_TYPE_GOODS = 2;


    const AB_POSTPONE_TIME_UNIT_DAY = 1;
    const AB_POSTPONE_TIME_UNIT_WEEK = 2;
    const AB_POSTPONE_TIME_UNIT_MONTH = 3;
    const AB_POSTPONE_TIME_UNIT_YEAR = 4;
    public static function AbPostponeTimeUnitMap() {
        return [
            static::AB_POSTPONE_TIME_UNIT_DAY => '天',
            static::AB_POSTPONE_TIME_UNIT_WEEK => '周',
            static::AB_POSTPONE_TIME_UNIT_MONTH => '月',
            static::AB_POSTPONE_TIME_UNIT_YEAR => '年'
        ];
    }

    #学历
    const DEGREE_GZYX = 1;
    const DEGREE_GZ = 2;
    const DEGREE_ZZ = 3;
    const DEGREE_BK = 4;
    const DEGREE_SS = 5;
    const DEGREE_BS = 6;
    public static function degreeMap() {
        return [
            static::DEGREE_GZYX => '高中以下',
            static::DEGREE_GZ => '高中',
            static::DEGREE_ZZ => '中专',
            static::DEGREE_BK => '本科',
            static::DEGREE_SS => '硕士',
            static::DEGREE_BS => '博士',
        ];
    }

    #职位情况
    const WORKING_STATUS_LZ = 1;
    const WORKING_STATUS_ZZ = 2;
    public static function workingStatusMap() {
        return [
            static::WORKING_STATUS_LZ => '离职',
            static::WORKING_STATUS_ZZ => '在职',
        ];
    }
    #婚姻状况
    const MARRIAGE_YES = 1;
    const MARRIAGE_NO = 2;
    public static function marriageMap() {
        return [
            static::MARRIAGE_YES => '已婚',
            static::MARRIAGE_NO => '未婚',
        ];
    }
    #期望薪资
    const EXPECTED_SALARY_LABEL_1 = 1;
    const EXPECTED_SALARY_LABEL_2 = 2;
    const EXPECTED_SALARY_LABEL_3 = 3;
    const EXPECTED_SALARY_LABEL_4 = 4;
    const EXPECTED_SALARY_LABEL_5 = 5;
    const EXPECTED_SALARY_LABEL_6 = 6;
    const EXPECTED_SALARY_LABEL_7 = 7;
    const EXPECTED_SALARY_LABEL_8 = 8;
    const EXPECTED_SALARY_LABEL_9 = 9;
    const EXPECTED_SALARY_LABEL_10 = 10;
    public static function expectedSalaryLabelMap() {
        return [
            static::EXPECTED_SALARY_LABEL_1 => '面议',
            static::EXPECTED_SALARY_LABEL_2 => '1000以下',
            static::EXPECTED_SALARY_LABEL_3 => '1000-2000',
            static::EXPECTED_SALARY_LABEL_4 => '2000-3000',
            static::EXPECTED_SALARY_LABEL_5 => '3000-5000',
            static::EXPECTED_SALARY_LABEL_6 => '5000-8000',
            static::EXPECTED_SALARY_LABEL_7 => '8000-12000',
            static::EXPECTED_SALARY_LABEL_8 => '12000-20000',
            static::EXPECTED_SALARY_LABEL_9 => '20000-25000',
            static::EXPECTED_SALARY_LABEL_10 => '25000以上',
        ];
    }

    #工作年限
    const WORKING_DURATION_LABEL_1 = 1;
    const WORKING_DURATION_LABEL_2 = 2;
    const WORKING_DURATION_LABEL_3 = 3;
    const WORKING_DURATION_LABEL_4 = 4;
    const WORKING_DURATION_LABEL_5 = 5;
    const WORKING_DURATION_LABEL_6 = 6;
    const WORKING_DURATION_LABEL_7 = 7;
    public static function workingDurationLabelMap() {
        return [
            static::WORKING_DURATION_LABEL_1 => '无经验',
            static::WORKING_DURATION_LABEL_2 => '应届生',
            static::WORKING_DURATION_LABEL_3 => '1年以下',
            static::WORKING_DURATION_LABEL_4 => '1-2年',
            static::WORKING_DURATION_LABEL_5 => '3-5年',
            static::WORKING_DURATION_LABEL_6 => '6-10年',
            static::WORKING_DURATION_LABEL_7 => '10年以上',
        ];
    }

    #简历审核状态
    const RESUME_CHECK_STATUS_NO = 1;
    const RESUME_CHECK_STATUS_ING = 2;
    const RESUME_CHECK_STATUS_YES = 3;
    const RESUME_CHECK_STATUS_WTG = 4;
    const RESUME_CHECK_STATUS_BCBB = 5;
    public static function resumeCheckStatusMap() {
        return [
            static::RESUME_CHECK_STATUS_NO => '未审核',
            static::RESUME_CHECK_STATUS_ING => '审核中',
            static::RESUME_CHECK_STATUS_YES => '通过',
            static::RESUME_CHECK_STATUS_WTG => '未通过',
//            static::RESUME_CHECK_STATUS_BCBB => '保持不变',
        ];
    }

    #简历是否默认
    const RESUME_DEFAULT_YES = 2;
    const RESUME_DEFAULT_NO = 1;
    public static function resumeDefaultMap() {
        return [
            static::RESUME_DEFAULT_YES => '是',
            static::RESUME_DEFAULT_NO => '否',
        ];
    }

    #简历是否删除
    const RESUME_DELETE_NO = 1;
    const RESUME_DELETE_YES = 2;
    public static function resumeDeleteMap() {
        return [
            static::RESUME_DELETE_NO => '否',
            static::RESUME_DELETE_YES => '是',
        ];
    }

    #招聘职位审核状态
    const RECRUIT_CHECK_STATUS_NO = 1;
    const RECRUIT_CHECK_STATUS_ING = 2;
    const RECRUIT_CHECK_STATUS_YES = 3;
    const RECRUIT_CHECK_STATUS_WTG = 4;
    const RECRUIT_CHECK_STATUS_BCBB = 5;
    public static function recruitCheckStatusMap() {
        return [
            static::RECRUIT_CHECK_STATUS_NO => '未审核',
            static::RECRUIT_CHECK_STATUS_ING => '审核中',
            static::RECRUIT_CHECK_STATUS_YES => '通过',
            static::RECRUIT_CHECK_STATUS_WTG => '未通过',
//            static::RECRUIT_CHECK_STATUS_BCBB => '保持不变',
        ];
    }

    #招聘职位是否结束
    const RECRUIT_POST_END_NO = 1;
    const RECRUIT_POST_END_YES = 2;

    #是否投递简历
    const RESUME_SEND_YES = 1;
    const RESUME_SEND_NO = 2;
    public static function resumeSendMap() {
        return [
            static::RESUME_SEND_YES => '已投递',
            static::RESUME_SEND_NO => '未投递',
        ];
    }

    #是否邀请面试
    const RESUME_INVITATION_YES = 2;
    const RESUME_INVITATION_NO = 1;
    public static function resumeInvitationMap() {
        return [
            static::RESUME_INVITATION_NO => '未邀请',
            static::RESUME_INVITATION_YES => '邀请',
        ];
    }

    #是否下载简历
    const RESUME_DOWNLOAD_YES = 2;
    const RESUME_DOWNLOAD_NO = 1;
    public static function resumeDownloadMap() {
        return [
            static::RESUME_DOWNLOAD_NO => '未下载',
            static::RESUME_DOWNLOAD_YES => '下载',
        ];
    }



    public static function getGoodsDefaultColor($id = null) {
        $default = ['红色' => '红色', '黑色' => '黑色'];
        if (!empty($id)) {
            $info = Goods::getOneByWhere(['id' => $id], ['goods_color']);
            if (!in_array($info['goods_color'], $default)) {
                $default = array_merge($default, [$info['goods_color'] => $info['goods_color']]);
            }
        }
        return $default;
    }
    public static function getGoodsDefaultSize($id = null) {
        $default = ['XL' => 'XL', 'XXL' => 'XXL'];
        if (!empty($id)) {
            $info = Goods::getOneByWhere(['id' => $id], ['goods_size']);
            if (!in_array($info['goods_size'], $default)) {
                $default = array_merge($default, [$info['goods_size'] => $info['goods_size']]);
            }
        }
        return $default;
    }

    #goods_order 订单状态 缩写 GO
    const G_O_STATUS_NOT_PAID = 1;      #未支付
    const G_O_STATUS_SETTLEMENT = 2;    #已结算
    const G_O_STATUS_PART_SHIPPED = 3;  #部分发货
    const G_O_STATUS_ALL_SHIPPED = 4;   #全部发货
    const G_O_STATUS_CANCEL = 5;        #交易取消
    const G_O_STATUS_COMPLETE = 6;      #交易完成
    const G_O_STATUS_ALL_REFUND = 7;        #全部退款

    public static function getGOStatusMap() {
        return [
            static::G_O_STATUS_NOT_PAID => '未支付',
            static::G_O_STATUS_SETTLEMENT => '已支付',
            static::G_O_STATUS_PART_SHIPPED => '部分发货',
            static::G_O_STATUS_ALL_SHIPPED => '全部发货',
            static::G_O_STATUS_CANCEL => '交易取消',
            static::G_O_STATUS_COMPLETE => '交易完成',
            static::G_O_STATUS_ALL_REFUND => '全部退款',
        ];
    }
    #goods --商品表
    const GOODS_STATUS_PUT_OFF_SHELVES = 1;     #上架
    const GOODS_STATUS_PUT_ON_SHELVES = 2;      #下架
    const GOODS_STATUS_DELETE = 3;              #删除

    #goods_order_detail 订单商品状态
    const G_O_D_STATUS_WAIT_SHIPMENT = 1;   #等待发货
    const G_O_D_STATUS_STOCK_OUT = 2;       #断货
    const G_O_D_STATUS_ALL_SHIPPED = 3;     #全部发货
    const G_O_D_STATUS_REFUND = 4;          #退货/款
    public static function getGODStatusMap() {
        return [
            static::G_O_D_STATUS_WAIT_SHIPMENT => '等待发货',
            static::G_O_D_STATUS_STOCK_OUT => '断货',
            static::G_O_D_STATUS_ALL_SHIPPED => '全部发货',
            static::G_O_D_STATUS_REFUND => '退货/款',
        ];
    }

    #goods_order_detail 订单入库状态
    const G_O_D_STATUS_IMPORT_NO = 1;   #未入库
    const G_O_D_STATUS_IMPORT_YES = 2;   #已入库
    public static function getGODImportStatusMap() {
        return [
            static::G_O_D_STATUS_IMPORT_NO => '未入库',
            static::G_O_D_STATUS_IMPORT_YES => '已入库',
        ];
    }

    #ab_goods_stock_log 入库类型
    const GOODS_STOCK_TYPE_IMPORT = 1;
    const GOODS_STOCK_TYPE_EXPORT = 2;
    public static function goodsStockTypeMap() {
        return [
            static::GOODS_STOCK_TYPE_IMPORT => '入库',
            static::GOODS_STOCK_TYPE_EXPORT => '出库',
        ];
    }

    #advert
    const ADVERT_POSITION_RIGHT = 1;
    const ADVERT_POSITION_MODAL = 2;
    public static function getPositionMap() {
        return [
            static::ADVERT_POSITION_RIGHT => '右边',
            static::ADVERT_POSITION_MODAL => '弹窗'
        ];
    }

    const ADVERT_STATUS_NORMAL = 1;
    const ADVERT_STATUS_STOP = 2;
    const ADVERT_STATUS_DELETE = 3;
    public static function advertStatusMap() {
        return [
            static::ADVERT_STATUS_NORMAL => '正常',
            static::ADVERT_STATUS_STOP => '停用',
            static::ADVERT_STATUS_DELETE => '删除'
        ];
    }

    #ab_goods_stock_log
    const AB_G_STOCK_TYPE_IN = 1;       #入库
    const AB_G_STOCK_TYPE_OUT = 2;      #出库

    #ab_coin_change_log
    const AB_CC_TYPE_RECHARGE = 1;     #总部充值
    const AB_CC_TYPE_HEADQUARTERS_PURCHASE = 2;  #总部直购消费
    const AB_CC_TYPE_HEADQUARTERS_REFUND = 3;#总部直购退款
    const AB_CC_TYPE_POSTPONE_CONSUME = 4; #延期消费

    public static function getABCoinChangeIncomeType(){
        return [
            self::AB_CC_TYPE_HEADQUARTERS_REFUND,
        ];
    }

    public static function getABCoinChangeOutcomeType(){
        return [
            self::AB_CC_TYPE_HEADQUARTERS_PURCHASE,
            self::AB_CC_TYPE_POSTPONE_CONSUME,
        ];
    }

    public static function getABCoinChangeAllType() {
        return [
            self::AB_CC_TYPE_RECHARGE => '总部充值',
            self::AB_CC_TYPE_HEADQUARTERS_PURCHASE => '总部直购消费',
            self::AB_CC_TYPE_HEADQUARTERS_REFUND => '总部直购退款',
            self::AB_CC_TYPE_POSTPONE_CONSUME => '延期消费',
        ];
    }
    #ab_goods
    const AB_GOODS_TYPE_COMMON = 1; #普通成品
    const AB_GOODS_TYPE_GIFT = 2;   #活动礼品

    const AB_GOODS_STATUS_PUT_OFF_SHELVES = 1;     #上架
    const AB_GOODS_STATUS_PUT_ON_SHELVES = 2;      #下架
    const AB_GOODS_STATUS_DELETE = 3;              #删除
    public static function getABGoodsTypeMap () {
        return [
            self::AB_GOODS_TYPE_COMMON => '普通成品',
            self::AB_GOODS_TYPE_GIFT => '活动礼品'
        ];
    }

    public static function getABGoodsDefaultColor($id = null) {
        $default = ['红色' => '红色', '黑色' => '黑色'];
        if (!empty($id)) {
            $info = AbGoods::getOneByWhere(['id' => $id], ['goods_color']);
            if (!in_array($info['goods_color'], $default)) {
                $default = array_merge($default, [$info['goods_color'] => $info['goods_color']]);
            }
        }
        return $default;
    }
    public static function getABGoodsDefaultSize($id = null) {
        $default = ['XL' => 'XL', 'XXL' => 'XXL'];
        if (!empty($id)) {
            $info = AbGoods::getOneByWhere(['id' => $id], ['goods_size']);
            if (!in_array($info['goods_size'], $default)) {
                $default = array_merge($default, [$info['goods_size'] => $info['goods_size']]);
            }
        }
        return $default;
    }

//    #calendar-plan
//    const CALENDAR_PLAN_TYPE_ALL_DAY = 1;       #全天
//    const CALENDAR_PLAN_TYPE_NOT_ALL_DAY = 2;   #非全天
//    public static function getPlanTypeMap() {
//        return  [
//            self::CALENDAR_PLAN_TYPE_ALL_DAY => '全天',
//            self::CALENDAR_PLAN_TYPE_NOT_ALL_DAY => '非全天'
//        ];
//    }

    #statement
    const AB_STATEMENT_TYPE_DEPOSIT = 1; #销售套系 - 定金
    const AB_STATEMENT_TYPE_FULL_MONEY = 2;   #销售套系 - 全款
    const AB_STATEMENT_TYPE_TAIL_MONEY = 3; #销售套系 - 尾款
    const AB_STATEMENT_TYPE_SECOND_SALE = 4; #二销收款
    const AB_STATEMENT_TYPE_ORDER_REFUND = 5; #订单退款
    const AB_STATEMENT_TYPE_ORDER_FIRST_PAY = 5; #订单首付
    const AB_STATEMENT_TYPE_OTHER = 6;
    public static function getABStatementTypeMap() {
        return [
            static::AB_STATEMENT_TYPE_ORDER_FIRST_PAY => '订单首付',
            static::AB_STATEMENT_TYPE_DEPOSIT => '定金',
            static::AB_STATEMENT_TYPE_FULL_MONEY => '全款',
            static::AB_STATEMENT_TYPE_TAIL_MONEY => '尾款',
            static::AB_STATEMENT_TYPE_SECOND_SALE => '二销收款',
            static::AB_STATEMENT_TYPE_ORDER_REFUND => '订单退款',
            static::AB_STATEMENT_TYPE_OTHER => '其他',
        ];
    }

    #notice
    const NOTICE_STATUS_NOT_RELEASE = 1;    #未发布
    const NOTICE_STATUS_RELEASING = 2;     #发布中
    const NOTICE_STATUS_DELETE = 3;     #删除
    public static function getNoticeStatusMap() {
        return [
            static::NOTICE_STATUS_NOT_RELEASE => '未发布',
            static::NOTICE_STATUS_RELEASING => '发布中',
            static::NOTICE_STATUS_DELETE => '删除'
        ];
    }

    #recruit_combo
    const RECRUIT_COMBO_SHOW_STATUS_NORMAL = 1;
    const RECRUIT_COMBO_SHOW_STATUS_SHIELD = 2;
    public static function getRecruitComboShowStatusMap() {
        return [
            static::RECRUIT_COMBO_SHOW_STATUS_NORMAL => '正常',
            static::RECRUIT_COMBO_SHOW_STATUS_SHIELD => '屏蔽'
        ];
    }

    const RECRUIT_COMBO_ALLOW_MEMBER_APPLY = 1;
    const RECRUIT_COMBO_DENY_MEMBER_APPLY = 2;
    public static function getRecruitComboMemberApplyMap() {
        return [
            static::RECRUIT_COMBO_ALLOW_MEMBER_APPLY => '是',
            static::RECRUIT_COMBO_DENY_MEMBER_APPLY => '否'
        ];
    }

    #预留员工职位
//    const EMPLOYEE_POST_TYPE_FUZEREN = 1;
    const EMPLOYEE_POST_TYPE_SHEYING = 2;
    const EMPLOYEE_POST_TYPE_ZHULI = 3;
    const EMPLOYEE_POST_TYPE_HUAZUANG = 4;
    const EMPLOYEE_POST_TYPE_XUANPIAN = 5;
    const EMPLOYEE_POST_TYPE_HOUQI = 6;
    const EMPLOYEE_POST_TYPE_LIJIAN = 7;
    const EMPLOYEE_POST_TYPE_SHOUYIN = 8;
    const EMPLOYEE_POST_TYPE_OTHER = 9;

    public static function employeePostTypeMap()
    {
        return [
            //            static::EMPLOYEE_POST_TYPE_FUZEREN => '负责人',
            static::EMPLOYEE_POST_TYPE_SHEYING => '摄影师',
            static::EMPLOYEE_POST_TYPE_ZHULI => '助理',
            static::EMPLOYEE_POST_TYPE_HUAZUANG => '化妆师',
            static::EMPLOYEE_POST_TYPE_XUANPIAN => '选片师',
            static::EMPLOYEE_POST_TYPE_HOUQI => '后期师',
            static::EMPLOYEE_POST_TYPE_LIJIAN => '理件师',
            static::EMPLOYEE_POST_TYPE_SHOUYIN => '收银员',
            static::EMPLOYEE_POST_TYPE_OTHER => '其他',
        ];
    }

    #员工是否在工作中
    const EMPLOYEE_IS_WORKING = 1;
    const EMPLOYEE_NO_WORKING = 2;
    public static function employeeIsWorkingMap()
    {
        return [
            static::EMPLOYEE_IS_WORKING => '在本加盟商工作中',
            static::EMPLOYEE_NO_WORKING => '在本加盟商离职',
        ];
    }
    #calendar_plan
    const CALENDAR_PLAN_STATUS_WAIT = 1;
    const CALENDAR_PLAN_STATUS_DOING = 2;
    const CALENDAR_PLAN_STATUS_FINISHED = 3;
    const CALENDAR_PLAN_STATUS_NOT_FINISHED = 4;
    public static function getPlanStatusMap() {
        return  [
            self::CALENDAR_PLAN_STATUS_FINISHED => '已完成',
            self::CALENDAR_PLAN_STATUS_NOT_FINISHED => '未完成'
        ];
    }
}
