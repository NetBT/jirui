<?php
namespace common\models;

class Patterns
{
    const PHONE = '';

    /**
     * 验证字符串是否只含数字与英文，字符串长度并在n~m个字符之间
     * @param int $minLength  最小长度
     * @param int $maxLength  最大长度
     *
     * @return string
     */
    public static function strNumberLetter($minLength = 4, $maxLength = 16) {
        return "^[a-zA-Z0-9]{" . $minLength . "," . $maxLength. "}$";
    }

    /**
     * 匹配中文
     * @return string
     */
    public static function chineseMatch() {
        return '[\u4e00-\u9fa5]';
    }

    /**
     * 匹配账号-只能是字母开头，长度n~m之间，允许字母数字下划线
     * @param int $minLength
     * @param int $maxLength
     *
     * @return string
     */
    public static function matchStrNumberLetterOnderline($minLength = 5, $maxLength = 16){
        return '^[a-zA-Z][a-zA-Z0-9_]{'. $minLength .',' .$maxLength. '}$';
    }

    /**
     * 匹配邮政编码
     * @return string
     */
    public static function matchPostCode() {
        return '[1-9]\d{5}(?!\d)';
    }

    /**
     * 匹配邮箱
     * @return string
     */
    public static function matchEmail() {
        return '^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$';
    }

    /**
     * 匹配年-月-日
     * @return string
     */
    public static function matchDate1() {
        return '/^(d{2}|d{4})-((0([1-9]{1}))|(1[1|2]))-(([0-2]([1-9]{1}))|(3[0|1]))$/';
    }

    /**
     * 匹配年/月/日
     * @return string
     */
    public static function matchDate2() {
        return '/^((0([1-9]{1}))|(1[1|2]))/(([0-2]([1-9]{1}))|(3[0|1]))/(d{2}|d{4})$/';
    }

    /**
     * 匹配国内座机号
     * @return string
     */
    public static function matchTelephone() {
        return '/^((\+?[0-9]{2,4}\-[0-9]{3,4}\-)|([0-9]{3,4}\-))?([0-9]{7,8})(\-[0-9]+)?$/';
    }
}
