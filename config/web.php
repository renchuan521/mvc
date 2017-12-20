<?php

return[
    'filter_vars' => 'htmlspecialchars',
    'html_cache'=>false,
    'memcache'=>[
        ['host'=>'127.0.0.1','port'=>'11211'],
    ],
    'cache_storage'=>'memcache',
    'session_save_handler'=>'file',

];
