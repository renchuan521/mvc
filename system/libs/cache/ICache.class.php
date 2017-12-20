<?php

namespace libs\cache;

interface Icache
{
    
    public function save($key,$value);
    
    public function get($key);
    
    public function flush($key);
}