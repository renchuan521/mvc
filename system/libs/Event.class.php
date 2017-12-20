<?php

namespace libs;

class Event
{
    public $name = '';// 事件名称

    public $data; // 消息

    public function __construct($name,$data){
        $this->name = $name;
        $this->data = $data;
    }
}
