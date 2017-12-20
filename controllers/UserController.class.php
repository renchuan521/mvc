<?php

namespace controllers;

use libs\Controller;
use libs\Configure;
use libs\Response;

class UserController extends Controller
{

    public function actionInfo(){
        if(!isset($_COOKIE['uid'])){
            return $this->redirect('?r=index/index');
        }
        $member = M('MemberModel');
        $mem = $member->find(['u_id'=>$_COOKIE['uid']]);
        if(IS_AJAX){
            Response::$format=Response::FORMAT_JSON;
            $data=$_POST;
            $res = $member->save($data);
            if($res){
                return ['code'=>200,'msg'=>'编辑成功!!'];
            }else{
                return ['code'=>0,'msg'=>'编辑失败!!，请检查是否是网络原因！或稍后重试'];
            }
        }else{
            $region = M('RegionModel');
            $area = $region->where(['parent_id'=>1])->select();
            return $this->render('info',['area'=>$area,'uid'=>$_COOKIE['uid'],'mem'=>$mem]);
        }

    }

    public function actionPerson(){
        $page = isset($_GET['page'])?$_GET['page']:1;
        $limit=($page-1)*5;
        $user = M('UserModel');
        $uid = isset($_COOKIE['uid']) ? $_COOKIE['uid'] : '';
        $users = $user->find(['u_id' => $uid]);
        $article = M('ArticleModel');
        $res = $article->find(['user_id'=>$uid]);
        $data=[];
        if($res){
            $data = $article->join(['user', 'article.user_id', 'user.u_id'])->where(['user_id'=>$uid])->limit($limit,5)->select();
            $page_num = floor(count($data)/5);
            if(IS_AJAX){
                Response::$format=Response::FORMAT_JSON;
                return ['code'=>200,'data'=>$data,'page_num'=>$page_num];
            }else{
                return $this->render('person', ['data' => $data,'user'=>$users,'page_num'=>$page_num]);
            }
        }else{
            return $this->render('person', ['data' => $data,'user'=>$users,'page_num'=>0]);
        }
    }

    public function actionRegion(){
        if(IS_AJAX){
            $parent_id = $_GET['parent_id'];
            Response::$format=Response::FORMAT_JSON;
            $region = M('RegionModel');
            $area = $region->where(['parent_id'=>$parent_id])->select();
            if($area){
                return ['code'=>200,'area'=>$area];
            }else{
                return ['code'=>0];
            }
        }
    }

    public function actionLogin(){
        if(IS_AJAX){
            Response::$format=Response::FORMAT_JSON;
            $data = $_POST;
            $user = M('UserModel');
            $password = md5($data['username'].md5($data['password']));
            $res = $user->find(['username'=>$data['username'],'password'=>$password]);
            if($res){
                setcookie('uid',$res['u_id'],0,'/index.php');
                if($res) return ['code'=>200];
            }else{
                return ['code'=>0,'msg'=>'用户名或密码错误！！！'];
            }
        }
    }

    public function actionLgout(){
        if(IS_AJAX){
            Response::$format=Response::FORMAT_JSON;
            setcookie('uid','',time()-1,'/index.php');
            return ['code'=>200];
        }
    }

    public function actionSignup(){
        if(IS_AJAX){
            Response::$format=Response::FORMAT_JSON;
            $data = $_POST;
            if(empty($data['nickname'])){
                return ['code'=>'0','msg'=>'请务必输入昵称!!!'];
            }
            $password = md5($data['username'].md5($data['password']));
            $user = M('UserModel');
            $once = $user->find(['username'=>$data['username']]);
            if($once){
                return ['code'=>0,'msg'=>'该用户名已被注册'];
            }
            $imgpath= './public/static/images/person/';
            $num= intval(rand(1, 92));
            $img = $imgpath.$num.'.png';
            $res = $user->save(['username'=>$data['username'],'password'=>$password,'img'=>$img,'nickname'=>$data['nickname']]);
            if($res){
                $data = $user->find(['username'=>$data['username'],'password'=>$password]);
                setcookie('loginid',$data['u_id'],0,'/index.php');
                return ['code'=>200];
            }
        }else{
            return ['code'=>0,'msg'=>'失败了，好好反思反思！'];
        }

    }

}
