<?php

namespace libs;

class Configure
{
    public static $config=[];

    public static function loadConfig(){
        $sys_config = self::getConfigFromDir(dirname(__DIR__).DIRECTORY_SEPARATOR.'config');
        $app_config = self::getConfigFromDir(CONFIG_PATH);
        self::$config = array_merge($sys_config,$app_config);
    }

    public static function getConfigFromDir($dir=__DIR__){
        $config = [];
        foreach(glob($dir.DIRECTORY_SEPARATOR.'*.php') as $filename){
            $values = include $filename;
            if(is_array($values)){
                $config = array_merge($config,$values);    
            }
        }
        return $config;
    }

    public static function config($name,$value=''){
        if($value == ''){
            if(isset(self::$config[$name])){
                return self::$config[$name];
            }else{
                return null;
            }
        }else{
            self::$config[$name]=$value; 
        }
    }
}

