<?php

namespace libs;


class Model extends \libs\Base
{
    const BEFORE_VALIDATE = 'beforeValidate';
    const AFTER_VALIDATE = 'afterValidate';
    const BEFORE_SAVE = 'beforeSave';
    const AFTER_SAVE = 'afterSave';

    public $tableName;
    public $config=[
        'type'=>'mysql',
    ];
    private $db;
    private $data=[];
    private $sql;
    private $join;
    private $where = '';
    private $limit = '';
    private $orderBy = '';
    private $groupBy = '';
    private $field = '*';
    private $fields =[];
    private $primaryKey;

    public function __construct($config = []){
        $this->config = array_merge($this->config,$config);
        $type = ucfirst($this->config['type']);
        $objname = 'libs\\db\\'.$type;
        $this->db = new $objname($this->config);
        $this->getFields();
    }

    public function setTable($tablename){
        $this->tableName = $tablename;    
    }

    public function table($table){
        $this->tableName = $table;
        return $this;
    }

    public function getFields(){
        $fields = $this->db->select('DESC '.$this->tableName);    
        if(is_array($fields)){
            foreach($fields as $row){
                $this->fields[$row['Field']] = $row;
                if($row['Key'] == 'PRI'){
                    $this->primaryKey = $row['Field'];    
                }
            }
        }
    }

    public function select(){
        $this->sql = 'SELECT '.$this->field.' FROM `'.$this->tableName.'`';
        if($this->join){
            $this->sql .= ' JOIN '.$this->join;    
        }
        if($this->where){
            $this->sql .= ' WHERE '.$this->where;    
        }
        if($this->orderBy){
            $this->sql .= ' order by '.$this->orderBy;    
        }
        if($this->limit){
            $this->sql .= ' LIMIT '.$this->limit;    
        }
        if($this->groupBy){
            $this->sql .= ' group by '.$this->groupBy;    
        }
        return $this->db->select($this->sql);
    }

    public function groupBy($mix){
        if(is_string($mix)){
            $this->groupBy = $mix;    
        }
        return $this;
    }

    public function orderBy($mix){
        if(is_string($mix)){
            $this->orderBy = $mix;    
        }elseif(is_array($mix)){
            $order = [];
            foreach($mix as $key=>$val){
                if(is_integer($key)){
                    $order[] = $val;    
                }else{
                    $order[] = $key.' '.$val;    
                }
            }
            $this->orderBy = implode(',',$order);
        }
        return $this;
    }

    public function limit(){
        $argmentsNum = func_num_args();
        if($argmentsNum == 1){
            $this->limit = func_get_arg(0); 
        }elseif($argmentsNum>1){
            $this->limit = func_get_arg(0).','.func_get_arg(1);
        }
        return $this;
    }

    public function where($mix){
        if(is_string($mix)){
            $this->where = $mix;    
        }elseif(is_array($mix)){
            $where = [];
            $separator = ' and ';
            if(isset($mix['_complex'])){
                $separator = ' '.$mix['_complex'].' ';
                unset($mix['_complex']);
            }
            foreach($mix as $key =>$val){
                if(is_array($val)){
                    $val = implode("'",$val)."'";
                    $where[] = '`'.$key.'`'.$val;
                }else{
                    $where[] = '`'.$key."`='" .$val."'"; 
                }
            }
            $this->where = implode($separator,$where);
        }
        return $this;
    }

    public function field($mix){
        if(is_string($mix)){
            $this->field = $mix;    
        }elseif(is_array($mix)){
            $this->field = implode(',',$mix);
        }
        return $this;
    }
    
    public function join($mix){
        $join = '';
        if(is_array($mix)){
            if(is_string($mix[0])){
                $join.=' `'.$mix[0].'` on';
            }
            if((!empty($mix[1]) && !empty($mix[2]))){
                $join .= ' '.$mix[1].'='.$mix[2];    
            }
        }
        $this->join = $join;
        return $this;
    }

    public function data($data){
        $this->data = $data;    
    }

    public function save($data=''){
        if(is_array($data)){
            $this->data = array_merge($this->data,$data);    
        }
        //验证
//        if(!$this->validate()){
//            return false;
//        }
        if(array_key_exists($this->primaryKey,$this->data)){
            //修改    
            $this->sql = 'REPLACE INTO '.$this->tableName.' SET ';
            $this->trigger(self::BEFORE_SAVE);
        }else{
            //增加
            $this->sql = 'INSERT INTO '.$this->tableName.' SET ';
        }
            $dt = [];
            foreach($this->data as $key=>$v){
                $dt[] = "`$key`='$v'";   
            }
            $this->sql .= implode(',',$dt);
        
        $res = $this->db->update($this->sql);
        $this->trigger(self::AFTER_SAVE);
        return $res;
    }
    
    public function find($where){
        $this->sql = "SELECT * FROM `$this->tableName`";
        $wh = [];
        if(is_array($where)){
            foreach($where as $key=>$val){
                $wh[] .= " `$key`='$val' ";
            }
            $where = ' WHERE ';
            $where .= implode(' and ',$wh);
        }
        $this->sql .= $where;
        return $this->db->find($this->sql);
    }

    public function getSql(){
        return $this->sql;    
    }

    public function delete($where){
        $this->sql = "DELETE FROM `$this->tableName`";
        $wh = [];
        if(is_array($where)){
            foreach($where as $key=>$val){
                $wh[] .= " `$key`='$val' ";
            }
            $where = ' WHERE ';
            $where .= implode(' and ',$wh);
        }
        $this->sql .= $where;
        return $this->db->delete($this->sql);
    }

    public function setInc($field,$status){
        $this->sql = "UPDATE `$this->tableName` SET `$field`=`$field`";
        if($status=='+'){
            $this->sql.='+1';
        }elseif($status=='-'){
            $this->sql.='-1';
        }
        if($this->where){
                $this->sql .= ' WHERE '.$this->where;
        }
        return $this->db->update($this->sql);
    }

    //进行字段验证
    public function validate(){
        //进行字段验证
        $rules = $this->rules();
        if(is_array($rules)){
            //循环进行验证
            //每一项错误进行记录
            foreach($rules as $rule){
                //定义好$rule一行的数据格式
                //['username','required',params]
                if(is_array($rule)){
                    //交给验证类使用
                    $funcname = $rule[1];//规则
                    //var_dump($funcname);
                    $field = $rule[0];
                    // echo $field;die;

                    $params = isset($rule['params']) ? $rule['params'] : [];
                    //var_dump($field);
                    // var_dump(isset(Validate::$validates[$funcname]));
                    // 判断数组的第二个规则是否存在
                    if(isset(Validate::$validates[$funcname])){
                        //将规则赋给$method变量，即为Validate中的方法
                        $method = Validate::$validates[$funcname];
                        //（字段,参数）不清楚？？？？？？
                        // var_dump(Validate::$method($this->data[$field]));die;
                        if(false === Validate::$method($this->data[$field],$params)){
                            //判断数组的第三个参数数组的下标是不是errormsg，有点话就把其值放到error数组中
                            if(isset($rule['errormsg'])){
                                $this->error[] = $rule['errormsg'];
                            }else{
                                //没写就将Validate中定义的值赋过来
                                $this->error[] = $field.Validate::$errorMsg[$funcname];
                            }
                        }
                        // print_r($this->error);die;
                    }elseif(method_exists($this,$funcname)){
                        if(false === $this->$funcname($this->data[$field],$params)){
                            if(isset($rule['errormsg'])){
                                $this->error[] = $rule['errormsg'];
                            }else{
                                $this->error[] = $field.'error';
                            }
                        }
                    }
                }
            }
        }
        // var_dump($this->error);
        //如果条数不为0则为真  返回false
        if(count($this->error)) return false;
        return true;
    }

}






