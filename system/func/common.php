<?php

function C($name,$value=''){
    return libs\Configure::config($name,$value); 
}

function M($name){
    $objname = str_replace(APP_PATH,'',MODEL_PATH);
    $objname = str_replace('/','\\',$objname).'\\'.$name;
    return libs\BaseTree::get($objname);
}

function U($url,$params=[]){
    $url_mode = C('url_mode');
    $controller = '';
    $action = '';

    $url = explode('/',$url);
    if(count($url)==1){
        $action = $url[0];
        $controller = strtolower(_CONTROLLER_);
    }elseif(count($url) == 2){
        $action = $url[1];
        $controller = $url[0];
    }
    if($url_mode == 0){
        $createUrl = __ROOT__.'/index.php?r='.$controller.'/'.$action;
    }elseif($url_mode == 1){
        $createUrl = __ROOT__.'/index.php/'.$controller.'/'.$action;
    }
    $param ='';
    if($params){
        $sep = '=';
        $link = '&';
        if($url_mode == 1 ) { $sep = '/'; $link = '/';}
        $arr = [];
        foreach($params as $key=>$v){
            $arr[] = $key.$sep.$v;    
        }
        $params = implode($link,$arr);
    }
    return $createUrl.$param;
}

function I($name=''){
    libs\BaseTree::get('libs\Request');
    $data = libs\Request::$data;
    if(!empty($name) && isset($data[$name])){
        return $data[$name];    
    }else{
        return $data;    
    }
}

function logs($message=''){
    if(!is_dir(LOG_PATH)){
        mkdir(LOG_PATH,0777,true);
    }
    file_put_contents(LOG_PATH.DS.'test',$message.'\r\n',FILE_APPEND);
}
