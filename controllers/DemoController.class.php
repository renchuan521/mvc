<?php 

namespace controllers;

use libs\Controller;
use libs\Session;

class DemoController extends Controller
{
    public function actionIndex(){
    
        if(IS_POST){
            $data=I('name');
            print_r($data);
        }else{
            return $this->render('index');    
        }
    }

}
