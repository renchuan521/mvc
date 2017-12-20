<?php

namespace libs\cache;

use libs\cache\ICache;

class FileCache implements ICache
{

    public function __construct()
    {

    }

    public function save($key,$data){
        //key 文件名 data 序列化后的数据
    }

    public function get($key){
        //key是文件名，根据文件名进行include
    }

    public function flush($key=''){
        //如果key为空，删除文件；不为空。就删除单个
    }
}