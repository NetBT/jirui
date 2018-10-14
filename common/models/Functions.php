<?php
namespace common\models;

use backend\models\AB;
use backend\models\ABCommon;
use Yii;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

class Functions extends ActiveRecord
{

    const SECRET_KEY = 'dsio19238IJOHERFG980Y23482390u4n12oi3h';

    const RAND_LETTER = 'ABCDEFGHIJKLMNPQRSTUVWXYZ0123456789';

    /**
     * 生成签名
     * @param $data
     *
     * @return string
     */
    public function makeSercetMd5($data)
    {
        $_md5String = '';
        ksort($data);
        foreach ($data as $k => $v) {
            $_md5String .= $k . '-' . $v;
        }
        return md5(static::SECRET_KEY . md5($_md5String . static::SECRET_KEY));
    }

    /**
     * 随机一串字母
     * @param int $length
     *
     * @return string
     */
    public static function randLetterNumber($length = 5)
    {
        // 密码字符集，可任意添加你需要的字符
        $chars = static::RAND_LETTER;
        $randStr = '';
        for ($i = 0; $i < $length; $i ++) {
            // 这里提供两种字符获取方式
            // 第一种是使用 substr 截取$chars中的任意一位字符；
            // 第二种是取字符数组 $chars 的任意元素
            // $password .= substr($chars, mt_rand(0, strlen($chars) – 1), 1);
            $randStr .= $chars[ mt_rand(0, strlen($chars) - 1) ];
        }

        return $randStr;
    }

    public static function filterForTable($data = [], $all = true) {

    }

    /**
     * 将指定数据的某个字段做KEY 指定字段做值 形成键值对
     * @param array  $data
     * @param string $fields
     * @param string $valueFields
     *
     * @return array
     */
    public static function extractKey($data = [], $fields = '', $valueFields = '')
    {
        $result = [];
        foreach ($data as $v) {
            if (empty($fields)) {
                $result[] = empty($valueFields) ? $v : $v[ $valueFields ];;
            } else {
                $result[ $v [ $fields ] ] = empty($valueFields) ? $v : $v[ $valueFields ];
            }
        }

        return $result;
    }

    /**
     * 以指定数组的key作为排序依据进行排序
     * @param        $arr
     * @param        $keys
     * @param string $type
     *
     * @return array
     */
    public static function array_sort($arr, $keys, $type = 'asc')
    {
        $keysvalue = $new_array = [];
        foreach ($arr as $k => $v) {
            $keysvalue[ $k ] = $v[ $keys ];
        }
        if ($type == 'asc') {
            asort($keysvalue);
        } else {
            arsort($keysvalue);
        }
        reset($keysvalue);
        foreach ($keysvalue as $k => $v) {
            $new_array[ $k ] = $arr[ $k ];
        }

        return $new_array;
    }

    /**
     * 获取访问IP
     * @return array|false|string
     */
    public function GetIp()
    {
        $realip = '';
        $unknown = 'unknown';
        if (isset($_SERVER)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown)) {
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                foreach ($arr as $ip) {
                    $ip = trim($ip);
                    if ($ip != 'unknown') {
                        $realip = $ip;
                        break;
                    }
                }
            } else if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP']) && strcasecmp($_SERVER['HTTP_CLIENT_IP'], $unknown)) {
                $realip = $_SERVER['HTTP_CLIENT_IP'];
            } else if (isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR']) && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown)) {
                $realip = $_SERVER['REMOTE_ADDR'];
            } else {
                $realip = $unknown;
            }
        } else {
            if (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), $unknown)) {
                $realip = getenv("HTTP_X_FORWARDED_FOR");
            } else if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), $unknown)) {
                $realip = getenv("HTTP_CLIENT_IP");
            } else if (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), $unknown)) {
                $realip = getenv("REMOTE_ADDR");
            } else {
                $realip = $unknown;
            }
        }
        $realip = preg_match("/[\\d\\.]{7,15}/", $realip, $matches) ? $matches[0] : $unknown;

        return $realip;
    }

    /**
     * 获取IP地址 对应地区
     * @param string $ip
     *
     * @return bool|mixed
     */
    public function GetIpLookup($ip = '')
    {
        if (empty($ip)) {
            $ip = $this->GetIp();
        }
        $res = @file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' . $ip);
        if (empty($res)) {
            return false;
        }
        $jsonMatches = [];

        preg_match('#\{.+?\}#', $res, $jsonMatches);
        if (!isset($jsonMatches[0])) {
            return false;
        }
        $json = json_decode($jsonMatches[0], true);
        if (isset($json['ret']) && $json['ret'] == 1) {
            $json['ip'] = $ip;
            unset($json['ret']);
        } else {
            return false;
        }

        return $json;
    }

    /**
     * 获取配置信息  如果指定name 则返回对应值  如果没有指定 则返回数组name => value 的集合  未找到集合 则返回false
     *
     * @param string $name
     *
     * @return array|bool|mixed
     */
    public static function getCommonByKey($name)
    {
        if (empty($name)) {
            $list = Common::getByWhere([], ["name", "value"]);
            if (empty($list)) {
                return false;
            }

            return static::extractKey($list, "name", "value");
        }
        if (is_array($name)) {
            $where["name"] = $name;
            $list = Common::getByWhere($where, ["name", "value"]);
            if (empty($list)) {
                return false;
            }

            return static::extractKey($list, "name", "value");

        }
        if (is_string($name)) {
            $value = Common::getOneByWhere(["name" => $name], ["name", "value"]);
            if (empty($value)) {
                return false;
            }

            return $value["value"];
        }

    }

    /**
     * 获取配置信息  如果指定name 则返回对应值  如果没有指定 则返回数组name => value 的集合  未找到集合 则返回false
     *
     * @param string $name
     *
     * @return array|bool|mixed
     */
    public static function getABCommonByKey($name)
    {
        $where['business_id'] = AB::getBusinessId();
        if (empty($name)) {
            $list = ABCommon::getByWhere($where, ["name", "value"]);
            if (empty($list)) {
                return false;
            }

            return static::extractKey($list, "name", "value");
        }
        if (is_array($name)) {
            $where["name"] = $name;
            $list = ABCommon::getByWhere($where, ["name", "value"]);
            if (empty($list)) {
                return false;
            }

            return static::extractKey($list, "name", "value");

        }
        if (is_string($name)) {
            $where["name"] = $name;
            $value = ABCommon::getOneByWhere($where, ["name", "value"]);
            if (empty($value)) {
                return false;
            }

            return $value["value"];
        }
    }

    /**
     * 字符截取 支持UTF8/GBK
     * @param $string
     * @param $length
     * @param string $dot
     * @return mixed|string
     */
    public function str_cut($string, $length, $dot = '...') {
        $strlen = strlen($string);
        if($strlen <= $length) return $string;
        $string = str_replace(array(' ',' ', '&amp;', '"', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '<', '>', '&middot;', '&hellip;'), array('∵',' ', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), $string);
        $strcut = '';
        $dotlen = strlen($dot);
        $maxi = $length - $dotlen - 1;
        $current_str = '';
        $search_arr = array('&',' ', '"', "'", '“', '”', '—', '<', '>', '·', '…','∵');
        $replace_arr = array('&amp;',' ', '"', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '<', '>', '&middot;', '&hellip;',' ');
        $search_flip = array_flip($search_arr);
        for ($i = 0; $i < $maxi; $i++) {
            $current_str = ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
            if (in_array($current_str, $search_arr)) {
                $key = $search_flip[$current_str];
                $current_str = str_replace($search_arr[$key], $replace_arr[$key], $current_str);
            }
            $strcut .= $current_str;
        }
        return $strcut.$dot;
    }

    /**
     * 中文字符截取 支持UTF8/GBK
     * @param $string
     * @param $length
     * @param string $charset
     * @param string $dot
     * @return mixed|string
     */
    public static function  chinese_str_cut($string, $length, $charset = 'utf-8', $dot = '...')
    {
        $strlen = strlen($string);
        if ($strlen <= $length) return $string;
        $string = str_replace([' ', ' ', '&', '"', '\'', '“', '”', '—', '<', '>', '·', '…'], ['∵', ' ', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'], $string);
        $strcut = '';
        if (strtolower($charset) == 'utf-8') {
            $length = intval($length - strlen($dot) - $length / 3);
            $n = $tn = $noc = 0;
            while ($n < strlen($string)) {
                $t = ord($string[ $n ]);
                if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                    $tn = 1;
                    $n ++;
                    $noc ++;
                } elseif (194 <= $t && $t <= 223) {
                    $tn = 2;
                    $n += 2;
                    $noc += 2;
                } elseif (224 <= $t && $t <= 239) {
                    $tn = 3;
                    $n += 3;
                    $noc += 2;
                } elseif (240 <= $t && $t <= 247) {
                    $tn = 4;
                    $n += 4;
                    $noc += 2;
                } elseif (248 <= $t && $t <= 251) {
                    $tn = 5;
                    $n += 5;
                    $noc += 2;
                } elseif ($t == 252 || $t == 253) {
                    $tn = 6;
                    $n += 6;
                    $noc += 2;
                } else {
                    $n ++;
                }
                if ($noc >= $length) {
                    break;
                }
            }
            if ($noc > $length) {
                $n -= $tn;
            }
            $strcut = substr($string, 0, $n);
            $strcut = str_replace(['∵', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'], [' ', '&', '"', '\'', '“', '”', '—', '<', '>', '·', '…'], $strcut);
        } else {
            $dotlen = strlen($dot);
            $maxi = $length - $dotlen - 1;
            $current_str = '';
            $search_arr = ['&', ' ', '"', "'", '“', '”', '—', '<', '>', '·', '…', '∵'];
            $replace_arr = ['&', ' ', '"', '\'', '“', '”', '—', '<', '>', '·', '…', ' '];
            $search_flip = array_flip($search_arr);
            for ($i = 0; $i < $maxi; $i ++) {
                $current_str = ord($string[ $i ]) > 127 ? $string[ $i ] . $string[ ++ $i ] : $string[ $i ];
                if (in_array($current_str, $search_arr)) {
                    $key = $search_flip[ $current_str ];
                    $current_str = str_replace($search_arr[ $key ], $replace_arr[ $key ], $current_str);
                }
                $strcut .= $current_str;
            }
        }

        return $strcut . $dot;
    }

    /**
     * @param int $backPoint 0.xxxx  小于1
     * @param int $award
     *
     * @return int
     */
    public static function  computeTrueAward($backPoint = 0, $award = 0) {
        $baseAward = $award * 0.85;
        return $backPoint * $award + $baseAward;
    }

    /**
     * 判断是否是手机端
     * @return bool
     */
    public static function is_mobile()
    {
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        $is_pc = (strpos($agent, 'windows nt')) ? true : false;
        $is_mac = (strpos($agent, 'intel mac os')) ? true : false;
        $is_iphone = (strpos($agent, 'iphone')) ? true : false;
        $is_android = (strpos($agent, 'android')) ? true : false;
        $is_ipad = (strpos($agent, 'ipad')) ? true : false;

        if($is_pc){
            return  false;
        }

        if($is_mac){
            return  false;
        }

        if($is_iphone){
            return  true;
        }

        if($is_android){
            return  true;
        }

        if($is_ipad){
            return  true;
        }
    }

    public static function is_mobile1()
    {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
            return true;

        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset ($_SERVER['HTTP_VIA'])) {
            // 找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        }
//        // 脑残法，判断手机发送的客户端标志,兼容性有待提高
//        if (isset ($_SERVER['HTTP_USER_AGENT'])) {
//            $clientkeywords = array('nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp', 'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu', 'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi', 'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile');
//            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
//            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
//                return true;
//        }
        // 协议法，因为有可能不准确，放到最后判断
        if (isset ($_SERVER['HTTP_ACCEPT'])) {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                return true;
            }
        }
        return false;
    }

    /**
     * 格式化日期
     * @param string $format
     * @param        $date
     *
     * @return false|string
     */
    public static function formatDate($format = "Y-m-d H:i:s", $date) {
        if ($date == '') {
            return '';
        }
        if (is_numeric($date)) {
            return date($format, $date);
        }
        return date($format, strtotime($date));
    }

    /**
     * 格式化ajax 返回格式
     * @param int    $code
     * @param string $message
     * @param        $data
     *
     * @return array
     */
    public static function formatJson($code = 1000, $message = '', $data = []){
        return ["code" => $code, "message" => $message, "data" => $data];
    }

    /**
     * @name php获取中文字符拼音首字母
     * @param $str
     * @return null|string
     * @author 潘军伟<panjunwei@ruiec.cn>
     * @time 2015-09-14 17:58:14
     */
    public static function getFirstCharter($str)
    {
        if (empty($str)) {
            return '';
        }
        $fchar = ord($str{0});
        if ($fchar >= ord('A') && $fchar <= ord('z')) return strtoupper($str{0});
        $s1 = iconv('UTF-8', 'gb2312', $str);
        $s2 = iconv('gb2312', 'UTF-8', $s1);
        $s = $s2 == $str ? $s1 : $str;
        $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
        if ($asc >= -20319 && $asc <= -20284) return 'A';
        if ($asc >= -20283 && $asc <= -19776) return 'B';
        if ($asc >= -19775 && $asc <= -19219) return 'C';
        if ($asc >= -19218 && $asc <= -18711) return 'D';
        if ($asc >= -18710 && $asc <= -18527) return 'E';
        if ($asc >= -18526 && $asc <= -18240) return 'F';
        if ($asc >= -18239 && $asc <= -17923) return 'G';
        if ($asc >= -17922 && $asc <= -17418) return 'H';
        if ($asc >= -17417 && $asc <= -16475) return 'J';
        if ($asc >= -16474 && $asc <= -16213) return 'K';
        if ($asc >= -16212 && $asc <= -15641) return 'L';
        if ($asc >= -15640 && $asc <= -15166) return 'M';
        if ($asc >= -15165 && $asc <= -14923) return 'N';
        if ($asc >= -14922 && $asc <= -14915) return 'O';
        if ($asc >= -14914 && $asc <= -14631) return 'P';
        if ($asc >= -14630 && $asc <= -14150) return 'Q';
        if ($asc >= -14149 && $asc <= -14091) return 'R';
        if ($asc >= -14090 && $asc <= -13319) return 'S';
        if ($asc >= -13318 && $asc <= -12839) return 'T';
        if ($asc >= -12838 && $asc <= -12557) return 'W';
        if ($asc >= -12556 && $asc <= -11848) return 'X';
        if ($asc >= -11847 && $asc <= -11056) return 'Y';
        if ($asc >= -11055 && $asc <= -10247) return 'Z';
        return null;
    }

    public static function getCurrUser () {
        return Yii::$app->user->identity;
    }

    public static function getProvince() {
        return Address::getByWhere(['level' => 1]);
    }

    public static function getCity($pid = 0) {
        return Address::getByWhere(['pid' => $pid]);
    }

    /**
     * 文件上传方法
     * @param string $formFields    form表单字段名称
     * @param string $oldFilePath   旧图片路径
     *
     * @return bool|string  上传成功返回新路径 否则返回假
     */
    public static function uploadFile($formFields = '', $oldFilePath = '') {
        //这个文件要创建到web的目录下,文件的绝对路径
        $rootDir = Yii::$app-> basePath.'/web/';
        $dir = 'uploads/';
        $dateDir = date("Ymd")."/";
        //不为空则上传了新图片，需要删除就图片
        $imgPath = $rootDir . $dir . $dateDir;
        try {
            if(!Yii::$app->request->isPost) {
                throw new Exception(false);
            }
            $image = UploadedFile::getInstanceByName($formFields);
            if (empty($image)) {
                throw new Exception(false);
            }
            //创建目录并给与777权限
            if (!file_exists($imgPath))
            {
                mkdir($imgPath);
                chmod($imgPath, 0777);
            }
            //获取上传图片后缀名
            $ext = $image->getExtension();
            //生成新图片名称
            $newImageName = md5(time().rand(10000,99999)).'.'.$ext;
            //生成新的图片相对web路径
            $imageName = $imgPath . $newImageName;
            //保存文件函数，在手册上有，将图片保存到本地
            $status = $image->saveAs($imageName,true);
            //如果保存成功
            if ($status !== true) {
                throw new Exception(false);
            }

            chmod($imageName, 0777);

            $oldFileFullPath = $rootDir . $dir . $oldFilePath;
            //删除旧图片
            if (!empty($oldFilePath) && $oldFilePath != '*' && file_exists($oldFileFullPath))
            {
                @unlink($oldFileFullPath);
            }
            return $dateDir . $newImageName;

        } catch (Exception $e) {
            return false;
        }
    }


    /**
     * 文件上传方法,支付多字段文件上传
     * @param string $formFields
     * @param string $oldFilePath
     * @return array
     * @throws Exception
     */
    public static function uploadMultiFile($formFields = '', $oldFilePath = '')
    {
        //这个文件要创建到web的目录下,文件的绝对路径
        $rootDir = Yii::$app->basePath . '/web/';
        $dir = 'uploads/';
        $dateDir = date("Ymd") . "/";
        //不为空则上传了新图片，需要删除就图片
        $imgPath = $rootDir . $dir . $dateDir;
        $field = [];
        if (is_array($formFields) && !empty($formFields)) {
            foreach ($formFields as $key => $value) {
                $singleField = [
                    'field' => $key,
                    'file_path' => !empty($value) ? $value : '',
                ];
                array_push($field, $singleField);
            }
        } else {
            $field[] = [
                'field' => $formFields,
                'file_path' => empty($oldFilePath) ? $oldFilePath : '',
            ];
        }
        if (Yii::$app->request->isPost) {
            foreach ($field as $key => $value) {
                $image = UploadedFile::getInstanceByName($value['field']);
                if (!empty($image)) {
                    //创建目录并给与777权限
                    if (!file_exists($imgPath)) {
                        mkdir($imgPath);
                        chmod($imgPath, 0777);
                    }
                    //获取上传图片后缀名
                    $ext = $image->getExtension();
                    //生成新图片名称
                    $newImageName = md5(time() . rand(10000, 99999)) . '.' . $ext;
                    //生成新的图片相对web路径
                    $imageName = $imgPath . $newImageName;
                    //保存文件函数，在手册上有，将图片保存到本地
                    $status = $image->saveAs($imageName, true);
                    //如果保存成功
                    if ($status !== true) {
                        throw new Exception(false);
                    }

                    chmod($imageName, 0777);

                    $oldFileFullPath = $rootDir . $dir . $value['file_path'];
                    //删除旧图片
                    if (!empty($value['file_path']) && $value['file_path'] != '*' && file_exists($oldFileFullPath)) {
                        @unlink($oldFileFullPath);
                    }
                    $field[$key] = [
                        'field' => $value['field'],
                        'file_path' => $dateDir . $newImageName,
                    ];
                }
            }
        }
        if (is_array($formFields)) {
            return $field;
        } else {
            return $field[0]['file_path'];
        }
    }

    public static function deleteUploadFile($fileName = null) {
        if (empty($fileName) || $fileName == '*') {
            return false;
        }
        //这个文件要创建到web的目录下,文件的绝对路径
        $rootDir = Yii::$app-> basePath.'/web/';
        $dir = 'uploads/';
        $fullName = $rootDir . $dir . $fileName;
        //删除旧图片
        if (!empty($fullName) && file_exists($fullName))
        {
            @unlink($fullName);
        }
        return true;
    }

    public static function datetimePattern() {
        return '/^\d{4}-\d{2}-\d{2}\s{1}[0-2]{1}[0-9]{1}:[0-5]{1}[0-9]{1}:[0-5]{1}[0-9]{1}/';
    }

    /**
     * 字符自动填充到指定长度
     * @param int    $numbers   被填充的字符
     * @param int    $addLength 指定长度
     * @param string $addString 填充字符
     *
     * @return string
     */
    public static function zeroFill($numbers = 0, $addLength = 3, $addString = '0') {
        $numbers = strval($numbers);
        $diff = $addLength - strlen($numbers);
        if($diff > 0) {
            return str_repeat($addString, $diff). $numbers;
        }
        return $numbers;
    }

    /**
     * 获取月份的开始和结束时间
     * @param null $yearMonth
     *
     * @return array
     */
    public static function getMonthStartEnd($yearMonth = null) {
        if (empty($yearMonth)) {
            $yearMonth = date('Y-m');
        }

        $start_date = date('Y-m-01 00:00:00', strtotime($yearMonth));
        $end_date = date('Y-m-t 23:59:59', strtotime($yearMonth));

        return [
            'month' => date('Y-m', strtotime($yearMonth)),
            'startDate' => $start_date,
            'endDate' => $end_date
        ];
    }

    /**
     * 根据开始和结束时间获取时间表 -- Echarts
     * @param null $start
     * @param null $end
     *
     * @return array
     */
    public static function getTimeForEchart($start = null, $end = null)
    {
        if (empty($start)) {
            $start = date('Y-m-d 00:00:00');
        }
        if (empty($end)) {
            $end = date('Y-m-d 23:59:59');
        }
        $startTimestamp = strtotime($start);
        $endTimestamp = strtotime($end);
        $result = [];
        //如果查询日期超过一天
        if ($endTimestamp - $startTimestamp > 24 * 3600) {
            $days = ceil(($endTimestamp - $startTimestamp) / (24 * 60 * 60));
            for ($i = 0; $i < $days; $i ++) {
                $xAxis = date('Y-m-d', $startTimestamp + 24 * 3600 * $i);
                $startTime = date('Y-m-d 00:00:00', $startTimestamp + 24 * 3600 * $i);
                $endTime = date('Y-m-d 23:59:59', strtotime($startTime));
                $result[] = ['xAxis' => $xAxis, 'start' => $startTime, 'end' => $endTime];
            }
        }
        //如果查询日期小于一天则每小时获取一次
        if ($endTimestamp - $startTimestamp <= 24 * 3600) {
            for ($i = 0; $i < 24; $i ++) {
                $xAxis = date('H', $startTimestamp + 3600 * $i);
                $startTime = date('Y-m-d H:00:00', $startTimestamp + 3600 * $i);
                $endTime = date('Y-m-d H:59:59', strtotime($startTime));
                $result[] = ['xAxis' => $xAxis, 'start' => $startTime, 'end' => $endTime];
            }
        }
        return $result;
    }
}
