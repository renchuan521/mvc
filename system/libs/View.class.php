<?php

namespace libs;

class View
{
    private $template;

    private $values=[];

    public function assign($arr=[]){
        $this->values = array_merge($this->values,$arr);    
    }

    public function display($template='',$params=[]){
        if(is_array($params)){
            $this->assign($params);
        }
        extract($this->values);
        $filename = $template;
        $filename = str_replace('\\','/',$filename);
        if($template==''){
            $template = lcfirst(_CONTROLLER_).DIRECTORY_SEPARATOR.lcfirst(_METHOD_).'.html';
            $filename = VIEW_PATH.DIRECTORY_SEPARATOR.$template;
        }elseif(strrpos($filename,'/')===false){
            $template = lcfirst(_CONTROLLER_).DIRECTORY_SEPARATOR.$template.'.html';
            $filename = VIEW_PATH.DIRECTORY_SEPARATOR.$template;
        }
        $filename = str_replace('\\','/',$filename);
        $runtime_filename = RUNTIME_PATH.DIRECTORY_SEPARATOR.md5($filename);
        if(!file_exists($runtime_filename) || time()-filemtime($runtime_filename) > C('tpl_expire_time')){

            $content = file_get_contents($filename);
            $content = ParseTemplate::parse($content);

            file_put_contents($runtime_filename,$content);
        }
        ob_start();
        include $runtime_filename;
        $result = ob_get_contents();
        ob_end_clean();
        return $result;

    }


}
