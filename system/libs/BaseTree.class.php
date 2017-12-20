<?php

namespace libs;

class BaseTree
{
    public static $objs = [];

    public static function setAlias($name,$obj){
        self::$objs[$name] = $obj;    
    }

    public static function get($name,$config=''){
        if(!isset(self::$objs[$name])){
            $obj = Factory::getInstance($name,$config);
            self::$objs[$name] = $obj;
        }    
        return self::$objs[$name];
    }

    public static function _unset($name){
        unset(self::$objs[$name]);    
    }
}

