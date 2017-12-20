<?php

namespace libs;

class Controller
{
    public $view;

    public function __construct(){
        if(method_exists($this,'init')){
            $this->init();
        }

        $this->view = new View();
    }

    public function assign($params=[]){
        $this->view->assign($params);
    }

    public function render($templateFile='',$params=[]){
        return $this->view->display($templateFile,$params);
    }

    public function redirect($url,$param=[]){
        if($param){
            if(strrpos($url,'?')!==false){
                $url .= '&'.http_build_query($param);
            }else{
                $url .= '?'.http_build_query($param);
            }
        }
        return $this->render(C('redirect'),['url'=>$url]);
    }

    public function go($message='',$url='',$params=[]){
        if($url==''){
            $url = $_SERVER['HTTP_REFERER'];
        }
        if($params){
            if(strrpos($url,'?')!==false){
                $url .= '&'.http_build_query($params);
            }else{
                $url .= '&'.http_build_query($params);
            }
        }
        return $this->render(C('redirect'),['url'=>$url,'message'=>$message]);
    }

    public function goBack(){
        header('Location:'.$_SERVER['HTTP_REFERER']);
        exit();
    }
}
