<?php
namespace backend\models;

use common\models\Functions;
use common\models\Status;
use Yii;

class Module extends Common
{
    public static function tableName()
    {
        return '{{%module_info}}';
    }

    public function getListData($type = '')
    {
        $returnData = [
            "draw" => intval(Yii::$app->request->post('draw')),
            "recordsTotal" => 0,
            "recordsFiltered" => 0,
            "data" => null
        ];
        //搜索条件
        $searchWhere  = $this->getSearch(Yii::$app->request->post('extra_search'),$type);//自定义搜索条件

        //得到文章的总数（但是还没有从数据库取数据）
        if(isset($searchWhere['andWhere'])){
            $count = self::getCountByAndWhere($searchWhere['where'], $searchWhere['andWhere']);
        } else {
            $count = self::getCountByWhere($searchWhere);
        }
        $returnData["recordsTotal"] = $returnData['recordsFiltered'] = intval($count);

        //设置分页
        $this->setPagination();

        $selectField = "*";
        //排序 order
        $orderSql = 'id DESC';
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
     * @param $search
     * @param $type
     * @return string
     */
    public function getSearch ($search = [], $type = '')
    {
        $where = [];
        $andWhere = [];
        $where['status'] = Status::MODULE_LIST_SUCCESS;
        $where['module_type'] = $type;
        if(!empty($type)) {
            $where['export_excel'] = Status::MODULE_EXPORT_EXCEL_YES;
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
        //按照最后一级的来拼装数组
        $formatList = self::getByWhere(['status' => Status::MODULE_LIST_SUCCESS]);
        $formatListInfo = Functions::extractKey($formatList, "id");
        $returnList = [];
        foreach ($list['data'] as $k => $v)
        {
            $returnList[$k]['id'] = $v['id'];
            $returnList[$k]['export_excel_url'] = $v['export_excel_url'];
            $returnList[$k]['module_team'] = '--';
            $returnList[$k]['module_detail'] = '--';
            $returnList[$k]['module_team_repeat_num'] = 0;
            $returnList[$k]['path'] = '';

            if(intval($v['parent_id']) != 0 )
            {
                $returnList[$k]['module_detail'] = $v['module_title'];
                //上一级的title
                $returnList[$k]['module_team'] = $formatListInfo[$v['parent_id']]['module_title'];
                //查看重复的次数
                $returnList[$k]['module_team_repeat_num'] = $this->getRepeatNum($list['data'], $v['parent_id']);
                $returnList[$k]['path'] = $formatListInfo[$v['parent_id']]['parent_id'] .','. $v['parent_id'];
            }
        }
        unset($list['data']);
        $returnList = $this->multiArraySort($returnList, 'path');
        $list['data'] = $returnList;
        return $list;
    }

    /**
     * 获取菜单中顶级菜单和二级菜单相同的个数
     * @param array $list
     * @param int $parentId
     * @return int
     */
    public function getRepeatNum($list = [], $parentId = 0)
    {
        $count = 0;
        foreach ($list as $key => $value)
        {
            if($value['parent_id'] == $parentId)
            {
                $count = $count + 1;
            }
        }
        return $count;
    }

    /**
     * 对数组进行组装
     * @param array $moduleList
     * @return array
     */
    public function module($moduleList = [])
    {
        //使用递归方法遍历菜单
        return $this->recursionModule($moduleList,0);
    }

    /**
     * 递归获取子元素
     * @param $moduleList
     * @param int $parentId
     * @return array|mixed
     */
    private function recursionModule($moduleList, $parentId = 0)
    {
        $childrenInfo = $this->moduleChild($moduleList, $parentId);
        $return = $childrenInfo['list'];

        if (!empty($return)) {
            foreach ($return as $k => $v) {
                $children  = $this->moduleChild($moduleList, $v['id']);
                if (!empty($children)) {
                    $return[$k]['children'] = $this->recursionModule($moduleList, $v['id']);
                } else {
                    break;
                }
            }
        }
        return $return;
    }

    /**
     * 获取子元素
     * @param $moduleInfo
     * @param $parentId
     * @return array
     */
    public function moduleChild($moduleInfo, $parentId)
    {
        $return = ['list' => null, 'untreated' => 0];
        foreach ($moduleInfo as $k=> $v) {
            if ($v['parent_id'] == $parentId) {
                if (!empty($v["badge_call_function"])) {
                    $v['untreated'] = method_exists($this, $v['badge_call_function']) ? $this->$v['badge_call_function']() : 0;
                } else {
                    $v['untreated'] = 0;
                }
                $return['list'][] = $v;
                $return['untreated'] += $v['untreated'];
            }
        }
        return $return;
    }

    public function getIdByUrl($moduleList, $url = "")
    {
        $url = urldecode(substr($url, 2));
        if(!empty($url))
        {
            //先把总列表处理下
            $handelList = [];
            foreach ($moduleList as $key => $value)
            {
                $handelList[$value['id']] = $value;
            }
            $id = [];
            foreach($handelList as $key => $value)
            {
                //获取当前ID
                if($url == $value['module_url'])
                {
                    $id[] = $value['id'];
                    //获取父集ID
                    $id[] = $value['parent_id'];
                    //在接着获取父集ID，如果有的情况下
                    if($handelList[$value['parent_id']]['parent_id'] != '0')
                    {
                        $id[] = $handelList[$value['parent_id']]['parent_id'];
                    }
                }
            }
            //转为字符串
            return $id;
        }
    }

    /**
     * 菜单排序
     * @param string $type
     * @return bool
     */
    public function getOrderModule($type = Status::MODULE_TYPE_HEADQUARTERS)
    {
        $where['status'] = Status::MODULE_LIST_SUCCESS;
        $where['module_type'] = $type;
        $moduleList = self::getByWhere($where);
        return $this->orderArray($moduleList);
    }


    //获取菜单所有下级ID
    public function getAllChildId($list = [], $currentId = 0, $item = [])
    {
        foreach ($list as $key => $value)
        {
            if($value['parent_id'] == $currentId) {
                array_push($item,$value['id']);
                $item = $this->getAllChildId($list, $value['id'], $item);
            } else {
                continue;
            }
        }
        return $item;
    }

    //获取菜单所有上级ID
    public function getAllParentId($list = [], $currentId = 0, $item = [])
    {
        foreach ($list as $key => $value)
        {
            if($value['id'] == $currentId) {
                array_push($item,$value['id']);
                $item = $this->getAllParentId($list, $value['parent_id'], $item);
            } else {
                continue;
            }
        }
        return $item;
    }

    /**
     * 获取关联的菜单
     * @param $id
     * @return mixed
     */
    public function relChoose($id)
    {
        $moduleList = static::getByWhere(['status' => Status::MODULE_LIST_SUCCESS]);
        //获取当前菜单的上级和下级。递归方法拼数组
        $childrenInfo = $this->getAllChildId($moduleList,$id);
        $parentId = self::getInfoByField($id,'parent_id');
        $parentInfo = $this->getAllParentId($moduleList,$parentId);

        //返回数据
        $return['parentId'] = $parentInfo;
        $return['childId'] = $childrenInfo;
        return $return;
    }
}
