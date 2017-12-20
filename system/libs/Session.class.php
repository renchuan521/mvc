<?php

namespace libs;

use libs\sessions\FileSessionHandler;
use libs\sessions\MemcacheSessionHandler;

class Session
{
    protected $storage;

    public function __construct($param='file')
    {
        //如果$param是空的，则加载配置文件中的session_save_handler
        if(empty($param)){
            $param = C('session_save_handler');
        }

        switch($param)
        {
            case 'file':
                $this->storage = new FileSessionHandler();
                break;
            case 'memcache':
                $this->storage = new MemcacheSessionHandler();
                break;
            default:
                break;
        }
        // 根据handler 进行类的选择并注册
        session_set_save_handler(
            [$this->storage,'open'], [$this->storage,'close'],
            [$this->storage,'read'],[$this->storage,'write'],
            [$this->storage,'destroy'],[$this->storage,'gc']);
    }

    public function start(){
        session_start();
    }

    public function add(){
        session_destroy();
    }

    public function destroy(){

    }
}
