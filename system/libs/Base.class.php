<?php
namespace libs;

class Base
{
    public $observeList = [];
    public $name = '';

    // $name事件名称
    // $obj 对象->update  方法
    public function addObserve($name,$func){
        if(!isset($this->observeList[$name]) || false === array_search($func,$this->observeList[$name])){
            $this->observeList[$name][] = $func;
        }
    }

    public function delObserve($name,$func){
        $key = array_search($func,$this->observeList[$name]);
        if($key !== false){
            unset($this->observeList[$name][$key]);
        }
    }

    public function trigger($event,$obj=''){
        if(!($obj instanceof Event)){
            $obj = new Event($event,$this);
        }

        if(isset($this->observeList[$event])){
            foreach($this->observeList[$event] as $func){
                call_user_func($func,$obj);
            }
        }
    }
}
