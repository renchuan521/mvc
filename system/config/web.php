<?php

return [
    'app_name' => 'mvc',
    'version'=>'1.0 beta',
    'redirect'=>dirname(__DIR__).DIRECTORY_SEPARATOR.'tpl'.DIRECTORY_SEPARATOR.'redirect.html',
    'tpl_expire_time'=>1,
    'url_mode' =>1 ,//1表示pathinfo模式
    'filter_vars'=>'',

    'template_parse'=>[
        'JS_PATH'=>ASSETS_PATH.'/js',
        'CSS_PATH'=>ASSETS_PATH.'/css',
        'IMG_PATH'=>ASSETS_PATH.'/images',
    ],
    // 模板解析有效期
    'tpl_expire_time'=>0,
    //是否开启静态缓存
    'html_cache'=>true,
    //静态缓存时间
    'html_expire'=>3600,
    //配置缓存存储方式
    'cache_storage'=>'file',
    //session默认存储路径
    'session_save_path'=>APP_PATH.'/sessionFiles',
    //定义session声明周期
    'session_lifetime'=>3600,
];

