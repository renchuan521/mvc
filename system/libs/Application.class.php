<?php
namespace avi;
if(!defined('APP_NAME')) die('deny access');

class Application
{
    private static  $instance;

    private static  $namespaceMap = [];
    private static $_logger;
    public static $app;

    private function __construct(){
        date_default_timezone_get('PRC');
        self::initMap();
        spl_autoload_register('self::_autoload');
        \libs\Configure::loadConfig();
        self::loadFun(); 
    }

    private static function loadFun(){
        foreach(glob(dirname(__DIR__).DIRECTORY_SEPARATOR.'func'.DIRECTORY_SEPARATOR.'*.php') as $filename){
            include $filename;    
        }
        foreach(glob(FUNCTION_PATH.DIRECTORY_SEPARATOR.'*.php') as $filename){
            include $filename;    
        }

    }

    private function __clone(){}

    public static function getInstance(){
        if(!self::$instance instanceof Application){
            self::$instance = new self();
        }
        return self::$instance;
    }

    /*
    *   自动加载
    **/
    private static function _autoload($className){
        $className = str_replace('\\',DIRECTORY_SEPARATOR,$className);
        $namespace = substr($className,0,strrpos($className,DIRECTORY_SEPARATOR));
        if(array_key_exists($namespace,self::$namespaceMap)){
            $className = str_replace($namespace,self::$namespaceMap[$namespace],$className);
        }
        $className = str_replace('\\',DIRECTORY_SEPARATOR,$className);
        $className .= PHP_EXT;
        include_once $className;

    }
    
    private static function initMap(){
        self::$namespaceMap = include SYSTEM_PATH.'/classMap.php';
    }

    public static function setNamespaceMap($arrNamespace){
        self::$namespaceMap += $arrNamespace;
    }

    public static function start(){
        $application = self::getInstance();
        $route = \libs\Route::getRoute();
        $response =(new $route['controller'])->{$route['method']}();
        \libs\Response::send($response);
    }

}


