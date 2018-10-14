<?php
namespace backend\models;

use common\models\Status;
use Yii;
use yii\base\Exception;
use common\models\Functions;

class Recruit extends Common
{
    public static function tableName()
    {
        return '{{%recruit_combo}}';
    }


    /**
     * 验证规则
     */
    public function rules()
    {
        return [
            #添加和修改规则
            [[
                'combo_name', 'vaild_days', 'origin_price', 'discount_price', 'max_concurr', 'refresh_time_span', 'max_refresh_pre_day',
                'download_resume_num', 'invite_candidate_num', 'headquarter_recommend_num', 'recommend_days', 'top_num', 'top_days',
                'urgent_post_num', 'urgent_days', 'recruit_order', 'show_status', 'allow_member_apply',
            ], 'required','message' => '不能为空','on' => ['add', 'edit']],

            //验证数字
            [['vaild_days', 'origin_price', 'discount_price', 'max_concurr', 'refresh_time_span', 'max_refresh_pre_day',
                'download_resume_num', 'invite_candidate_num', 'headquarter_recommend_num', 'recommend_days', 'top_num', 'top_days',
                'urgent_post_num', 'urgent_days', 'recruit_order', 'show_status', 'allow_member_apply'], 'number', 'message' => '必须为数字','on' => ['add', 'edit']],
        ];
    }

    /**
     * 设置属性名称
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'combo_name' => '套餐名称',
            'vaild_days' => '有效天数',
            'origin_price' => '原价',
            'discount_price' => '折扣价',
            'max_concurr' => '职位最大并发数',
            'refresh_time_span' => '刷新普通职位时间间隔',
            'max_refresh_pre_day' => '每天刷新普通职位次数限制',
            'download_resume_num' => '下载人才简历',
            'invite_candidate_num' => '邀请人才面试数',
            'headquarter_recommend_num' => '总部推荐职位',
            'recommend_days' => '推荐天数设置',
            'top_num' => '允许置顶职位',
            'top_days' => '置顶天数设置',
            'urgent_post_num' => '允许紧急职位',
            'urgent_days' => '紧急天数设置',
            'recruit_order' => '显示顺序',
            'show_status' => '显示状态',
            'allow_member_apply' => '允许会员申请此服务',
            'mark' => '其他说明'
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
                'combo_name', 'vaild_days', 'origin_price', 'discount_price', 'max_concurr', 'refresh_time_span', 'max_refresh_pre_day',
                'download_resume_num', 'invite_candidate_num', 'headquarter_recommend_num', 'recommend_days', 'top_num', 'top_days',
                'urgent_post_num', 'urgent_days', 'recruit_order', 'show_status', 'allow_member_apply', 'mark',
            ],
            'edit' => [
                'combo_name', 'vaild_days', 'origin_price', 'discount_price', 'max_concurr', 'refresh_time_span', 'max_refresh_pre_day',
                'download_resume_num', 'invite_candidate_num', 'headquarter_recommend_num', 'recommend_days', 'top_num', 'top_days',
                'urgent_post_num', 'urgent_days', 'recruit_order', 'show_status', 'allow_member_apply', 'mark',
            ],
        ];
        return array_merge(parent::scenarios(), $newScenarios);
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
        $count = self::getCountByWhere();
        $returnData["recordsTotal"] = $returnData['recordsFiltered'] = intval($count);

        //设置分页
        $this->setPagination();

        //排序 order
        $orderSql = 'recruit_order desc';
        $list =  static::getByWhere([], [
            'combo_name', 'vaild_days', 'origin_price', 'discount_price','id', 'mark'
        ], $orderSql, $this->_Pagination['offset'], $this->_Pagination['limit']);
        foreach ($list as $k => $v) {
            $list[$k]['mark'] = empty($v['mark']) ? '' : $v['mark'];
        }
        $returnData['data'] = $list;
        return $returnData;
    }

    /**
     * 保存信息
     * @return array
     */
    public function saveData() {
        $trans = Yii::$app->db->beginTransaction();
        try {
            if (!$this->validate()) {
                throw new Exception('校验失败');
            }
            $post = \Yii::$app->request->post('Recruit');
            $data = [];
            if (isset($post['id']) && !empty($post['id'])) {
                $data = array_merge($data, $this->getSaveData('edit', $post));
                $data['update_time'] = date("Y-m-d H:i:s");
                $res = static::updateDataWithLog($data, ['id' => $post['id']]);
                if ($res === false) {
                    throw new Exception('数据库更新失败');
                }
            } else {
                $data['create_user'] = \Yii::$app->user->getId();
                $data['create_time'] = date("Y-m-d H:i:s");
                $data = array_merge($data, $this->getSaveData('add', $post));
                $res = static::insertDataWithLog($data);
                if ($res === false) {
                    throw new Exception('数据插入失败');
                }
            }
            $trans->commit();
            return Functions::formatJson(1000, '操作成功');
        } catch (Exception $e) {
            $trans->rollBack();
            return Functions::formatJson(2000, $e->getMessage());
        }
    }
}
