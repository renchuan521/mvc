<?php

namespace libs;

use libs\cache\FileCache;
use libs\cache\MemCache;

class Cache
{
    public $storageName;
    public $storage;
    public $name;

    public function __construct($storageName)
    {
        $this->storageName = $storageName;
        $this->storage = new $storageName();
    }

    public function setName($name){
        $this->name = $name;//对于文件缓存是文件名  ，对memcache 是键名
    }

    public function add($key,$data){
        if('file' == strtolower($this->storageName == ''))
        $this->storage->save($key,$data);
    }

    public function get($key){
        $this->storage->get($key);
    }

    public function flush(){
        $this->storage->flush();
    }
}