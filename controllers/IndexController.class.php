<?php

namespace controllers;

use libs\Controller;
use libs\Configure;
use libs\Response;
use models\ArticleModel;
use models\UserModel;
use libs\Cache;
use libs\Session;

class IndexController extends Controller
{
    public function actionIndex()
    {
//        $cache = new Cache('File');
//        $cache->name = './a/txt';
//
//        $data = $cache->get();
//        $cache->add('abcd',$data);


        $page = isset($_GET['page'])?$_GET['page']:1;
        $limit=($page-1)*5;
        $user = M('UserModel');
        $uid = isset($_COOKIE['uid']) ? $_COOKIE['uid'] : '';
        $users=[];
        if ($uid) {
            $users = $user->find(['u_id' => $uid]);
        }
        $article = M('ArticleModel');
        $nums = $article->field('count(*) as nums')->join(['user', 'article.user_id', 'user.u_id'])->select();
        $article = M('ArticleModel');
        $data = $article->field('*')->join(['user', 'article.user_id', 'user.u_id'])->orderBy('add_time desc')->limit($limit,5)->select();
        $page_nums = $nums[0]['nums'];
        $page_num = floor($page_nums/5);
        if(IS_AJAX){
            Response::$format=Response::FORMAT_JSON;
            return ['code'=>200,'data'=>$data,'page_num'=>$page_num];
        }else{
            return $this->render('index', ['data' => $data,'user'=>$users,'page_num'=>$page_num]);
        }
    }

    public function actionSave(){
        if(IS_AJAX){
            Response::$format=Response::FORMAT_JSON;
            $data['art_type']=$_POST['art_type'];
            $data['content']=$_POST['content'];
            $data['user_id']=$_COOKIE['uid'];
            $data['add_time']=date('Y-m-d H:i:s',time());
            $article =  M('ArticleModel');
            $article->addObserve(ArticleModel::BEFORE_SAVE,[$article,'beforeSave']);
            $res = $article->save($data);
            if($res){
                return ['code'=>200];
            }
        }
    }

    public function actionPraise(){
        if(IS_AJAX){
            Response::$format=Response::FORMAT_JSON;
            $data=$_GET;
            if($data['val']=='+'){
                $v=1;
                $field='upnums';
            }else{
                $v=2;
                $field='downnums';
            }
            $uid = $_COOKIE['uid'];
            try{
                $article_user = M('Article_userModel');
                $art_user_res = $article_user->find(['a_id'=>$data['id'],'u_id'=>$uid]);
                if($art_user_res){
                    if($art_user_res['status']==1){
                        $msg = '你已经赞过了';
                    }else{
                        $msg = '你已经踩过了';
                    }
                    return ['code'=>1,'msg'=>$msg];
                }
                $res_art_user = $article_user->save(['a_id'=>$data['id'],'u_id'=>$uid,'status'=>$v]);
                $user =  M('UserModel');
                $res_user = $user->where(['u_id'=>$uid])->setInc('point',$data['val']);
                $article =  M('ArticleModel');
                $res_article = $article->where(['id'=>$data['id']])->setInc($field,$data['val']);
                if($res_art_user && $res_article && $res_user){
                    return ['code'=>200];
                }
            }catch(\Exception $e){
                return ['code'=>0,'msg'=>$e->getMessage()];
            }
        }
    }



}
