<?php

namespace libs\db;

interface InterfaceDb
{
    public function connect($config = []);

    public function select($sql = '');

    public function insert($sql = '');

    public function update($sql = '');

    public function delete($sql = '');
}

