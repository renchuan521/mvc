<?php

namespace libs\sessions;

use libs\sessions\ISessionHandler;

class FileSessionHandler implements ISessionHandler
{

    public $maxlife;
    public $dir;

    public function __construct()
    {
        //获取文件存储的路径
        $this->dir = C('session_save_path');
        //获取session的最大生存时间
        $maxlife = C('session_lifetime');
        if(!$maxlife){
            if(function_exists('ini_get')){
                $maxlife = ini_get('session.gc_maxlifetime');
            }else{
                $maxlife = 1440;
            }
        }
        $this->maxlife = $maxlife;
        //注册session_write_close方法
        register_shutdown_function('session_write_close');
    }

    public function create_sid(){
        $id = time().uniqid();
        echo $id;
        logs($id);
        return $id;
    }

    public function open($save_path,$name){
        if($save_path){
            $this->dir = $save_path;
        }
        if(!is_dir($this->dir)){
            mkdir($this->dir,0777,true);
        }
        logs('session_open');
        return true;
    }

    public function read($session_id){
        $filename = $this->dir.DS.$session_id.'.sess';
        if(file_exists($filename)) {
            return file_get_contents($filename);
        }
        logs('session_read');
        return true;
    }

    public function write($session_id,$data){
        $filename = $this->dir.DS.$session_id.'.sess';
        logs('session_write');
        return file_put_contents($filename,$data) ? true : false;

    }

    public function close(){
        logs('session_close');
        return true;
    }

    public function destroy($session_id){
        $filename = $this->dir.DS.$session_id.'sess';
        logs('session_destroy');
        return unlink($filename);
    }

    public function gc($maxlifetime){
        //搜索指定目录下 所有的文件 time()->filemtime() ->$maxlifetime 删掉

        return true;
    }

}
