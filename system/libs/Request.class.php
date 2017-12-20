<?php

namespace libs;

class Request
{

    public static $data = [];    
    public static $header = [];
    public static $post = [];
    public static $get = [];
    public static $cookie = [];    

    public function __construct(){
        self::getParams();
        self::getCookies();
        self::getHeaders();
        $func = 'trim,'.C('filter_vars');
        $func = explode(',',$func);
        foreach($func as $method){
            if($method){
                self::$data = array_map($method,self::$data);    
            }
        }
    }


    public static function getParams(){
        if(isset($_GET)){
            self::$data = array_merge(self::$data,$_GET);    
        }elseif($_POST){
            self::$data = array_merge(self::$data,$_POST);
        }
        if(!self::$data){
            self::$data = file_get_contents('php://input');    
        }
    }

    public static function getCookies(){
        self::$cookie = $_COOKIE;    
    }

    public static function getHeaders(){
        if(function_exists('getallheaders')){
            self::$headers = getallheaders();
        }else{
            foreach($_SERVER as $key=>$val){
                if('HTTP' == substr($key,0,5)){
                    self::$headers[str_replace('_','-',substr($key,5))] = $val;
                }    
            }    
        }
    }

    public static function getRemoteIp(){
        return $_SERVER['REMOTE_ADDR'];    
    }
}

