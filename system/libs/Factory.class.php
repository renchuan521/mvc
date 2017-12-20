<?php

namespace libs;

class Factory
{
    
    public static function getInstance($objname,$params=[]){
        if($params){
            return new $objname($params);    
        }    
        return new $objname;
    }
}
