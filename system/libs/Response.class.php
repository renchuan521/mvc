<?php 

namespace libs;

class Response
{
    
    const FORMAT_JSON = 1;
    const FORMAT_XML = 2;
    const FORMAT_HTML = 0;

    public static $data;

    public static $format = self::FORMAT_HTML;

    public static function json($data){
        header('content-type:application/json;charset="utf8"');
        return self::$data = json_encode($data);    
    }

    public static function xml(){
        header('content-type:application/xml');
    }

    public static function send($data){
        if(self::$format == self::FORMAT_JSON){
            echo self::json($data);    
        }elseif(self::$format == self::FORMAT_XML){
            echo self::xml($data);    
        }else{
            echo $data;    
        }
    }
}

