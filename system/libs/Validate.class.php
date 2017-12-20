<?php
namespace libs;

//验证字段和返回错误类
class Validate
{
    public static $validates = [
        //定义方法
        'required'=>'_empty',
        'string'=>'_string',
    ];

    public static $errorMsg = [
        'required'=>'字段值不能为空',
        'string'=>'字段值长度不正确',
    ];

    public static function _empty($field,$params=[]){
        return !empty($field);
    }

    public static function _string($field,$params=[]){
        $min = isset($params['min']) ? intval($params['min']) : 0;
        $max = isset($params['max']) ? intval($params['max']) : 100000000000;
        $len = mb_strlen($field);
        if($len < $min || $len > $max) return false;
        return true;
    }
}
