<?php

namespace libs\sessions;

interface ISessionHandler
{
    public function create_sid();//创建唯一的sid 返回字符串
    public function open($save_path,$name);
    public function read($session_id);
    public function write($session_id,$session_data);
    public function close();
    public function destroy($session_id);
    public function gc($maxlifetime);
}