<?php

namespace models;

use libs\Model;

class ArticleModel extends Model
{
    public $tableName = 'article';

    public function rules(){
        return [
            [['art_type','content'],'required','message'=>'不能为空'],
        ];    
    }

    public function beforeSave(){
        file_put_contents('/demo.txt',date('Y-m-d H:i:s',time()).'修改');
    }
    public function afterSave(){
        file_put_contents('/demo.txt',date('Y-m-d H:i:s',time()).'数据库已修改');
    }
}
