<?php

namespace libs\db;

class Mysql implements InterfaceDb
{
    private $dsn;
    private $config = [
    ];
    private $pdo;


    public function __construct($config = []){
        $this->config = array_merge(C('db'),$config);
        $this->dsn = "mysql:host={$this->config['host']}:{$this->config['port']};dbname={$this->config['dbname']};charset={$this->config['charset']}";
        $this->connect($this->config);

    }

    public function connect($config = []){
        if(!($this->pdo instanceof \PDO)){
            try{
                $this->pdo = new \PDO($this->dsn,$this->config['user'],$this->config['password']);
            }catch(\PDOException $e){
                die($e->getMessage());
            }
        }
    }

    public function select($sql = ''){
        $query = $this->pdo->query($sql);
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function insert($sql = ''){
        return $this->pdo->exec($sql);    
    }

    public function delete($sql = ''){
        return $this->pdo->exec($sql);    
    }

    public function update($sql = ''){
        return $this->pdo->exec($sql);    
    }

    public function find($sql = ''){
        $query = $this->pdo->query($sql);
        return $query->fetch(\PDO::FETCH_ASSOC);
    }

}













