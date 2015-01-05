<?php
//定义首页和模板路径
defined('APP_PATH') or define('APP_PATH',dirname($_SERVER['SCRIPT_FILENAME']).'/');
defined('TEML_PATH') or define('TEML_PATH',APP_PATH.'template/');
//定义核心路径
defined('CORE_PATH') or define('CORE_PATH',dirname(__FILE__).DIRECTORY_SEPARATOR);
defined('CORE_INCLUDE') or define('CORE_INCLUDE',CORE_PATH.'include'.DIRECTORY_SEPARATOR);
defined("APP_URL") or define('APP_URL','http://'.$_SERVER["HTTP_HOST"].$_SERVER["SCRIPT_NAME"]);
//包含内核类
include_once CORE_INCLUDE.'db.class.php';
include_once CORE_INCLUDE.'function.php';
include_once CORE_INCLUDE.'model.class.php';
include_once CORE_INCLUDE.'action.class.php';
include_once CORE_INCLUDE.'view.class.php';
$config=include_once CORE_INCLUDE.'config.php';
//include 'config.php';
/*关闭magic_quotes_gpc*/
if(version_compare(PHP_VERSION,'5.4.0','<')) {
    ini_set('magic_quotes_runtime',0);
    define('MAGIC_QUOTES_GPC',get_magic_quotes_gpc()?True:False);
}else{
    define('MAGIC_QUOTES_GPC',false);}
    /*这里进行URL路由,这里是最简单的路由功能*/
    //支持pathinfo
 if(isset($_SERVER["PATH_INFO"])){
    $pathinfo=trim($_SERVER["PATH_INFO"],'/');
    $path=explode('/',$pathinfo);
     $control=isset($path[0])?strip_tags($path[0]):'index';
     $action=isset($path[1])?strip_tags($path[1]):'index';
   
   
      isset($path[3])?$param[$path[2]]=intval($path[3]):null;
      isset($path[5])?$param[$path[4]]=intval($path[5]):null;
 }else{
//原始的解析
$control=isset($_GET['c'])?strip_tags($_GET['c']):'index';
$action=isset($_GET['a'])?strip_tags($_GET['a']):'index';
}
/*简单的路由*/
if(!in_array($control,array('user','index','node','page','admin','commit','comment','category','rule'))){
    die('unregistered control');
}else{
    if(file_exists(APP_PATH.'action/'.$control.'Action.class.php')){
         include APP_PATH.'action/'.$control.'Action.class.php';
        $nowaction=$control.'Action';
        $nowcontrol=new $nowaction();
         if(method_exists( $nowaction,$action)){
            $nowcontrol->{$action}();
         }else{
          die('Method not exists');
         }
    }else{
      die('Control class not exists');
    }
}

 
