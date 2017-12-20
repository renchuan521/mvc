<?php

if(!defined('APP_PATH'))    define('APP_PATH',dirname(__DIR__));
if(!defined('CONTROLLER_PATH')) define('CONTROLLER_PATH',APP_PATH.DIRECTORY_SEPARATOR.'controllers');
if(!defined('MODEL_PATH')) define('MODEL_PATH',APP_PATH.DIRECTORY_SEPARATOR.'models');
if(!defined('VIEW_PATH')) define('VIEW_PATH',APP_PATH.DIRECTORY_SEPARATOR.'views');
if(!defined('RUNTIME_PATH')) define('RUNTIME_PATH',APP_PATH.DIRECTORY_SEPARATOR.'runtime');
if(!defined('CONFIG_PATH')) define('CONFIG_PATH',APP_PATH.DIRECTORY_SEPARATOR.'config');
if(!defined('FUNCTION_PATH')) define('FUNCTION_PATH',APP_PATH.DIRECTORY_SEPARATOR.'functions');
if(!defined('SYSTEM_PATH')) define('SYSTEM_PATH',APP_PATH.DIRECTORY_SEPARATOR.basename(__DIR__));
if(!defined('LOG_PATH')) define('LOG_PATH',RUNTIME_PATH.DIRECTORY_SEPARATOR.'logs');
if(!defined('PHP_EXT'))  define('PHP_EXT','.class.php');
define('DS',DIRECTORY_SEPARATOR);
if(phpversion() < '7.0.0'){
    exit('你的PHP版本过低！');    
}

define('NOW_TIME',      $_SERVER['REQUEST_TIME']);
define('REQUEST_METHOD',$_SERVER['REQUEST_METHOD']);
define('IS_GET',        REQUEST_METHOD =='GET' ? true : false);
define('IS_POST',       REQUEST_METHOD =='POST' ? true : false);
define('IS_PUT',        REQUEST_METHOD =='PUT' ? true : false);
define('IS_DELETE',     REQUEST_METHOD =='DELETE' ? true : false);
define('IS_AJAX',       ((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || !empty($_POST['ajax']) || !empty($_GET['ajax'])) ? true : false);

define('AB_ROOT',str_replace('\\','/',$_SERVER['DOCUMENT_ROOT']));
define('__ROOT__',str_replace(AB_ROOT,'',str_replace('\\','/',APP_PATH)));


if(!defined('ASSETS_PATH')) define('ASSETS_PATH',__ROOT__.'/public/static');
$GLOBALS['phpversion'] = phpversion();
$GLOBALS['env'] = php_sapi_name();
$GLOBALS['begintime'] = time();

if(!isset($GLOBALS['redirect_tpl'])) $GLOBALS['redirect_tpl'] = __DIR__.DIRECTORY_SEPARATOR.'tpl'.DIRECTORY_SEPARATOR.'redirect.html';

require 'libs/Application.class.php';

class App extends avi\Application
{
        
}
