<?php
defined('CORE_PATH') or die('access deney');
class Action{
/*view实例*/
  protected $view=null;
  /*当前控制器名称*/
  public $name='';
  public function __construct(){
    $this->view=new View();
    if(method_exists($this,'_initialize')){
        $this->_initialize();
     }
     $this->name=substr(get_class($this),0,-6);
    define('MODULE_NAME',$this->name);
   	global $action;
    define('ACTION_NAME',$action);
   
  }
  /*得到当前类的名称*/
  /*页面输出*/
  protected function display($template='',$content=''){
 
    $this->view->display($template,$content);
  }
  protected function fetch($template='',$content=''){
    return $this->view->fetch($template,$content);
  }
  protected function buildHtml($html='',$htmlpath='',$template){
    $content=$this->fetch($template);
    $htmlpath=!empty($htmlpath)?$htmlpath:INDEX_PATH;
    $htmlfile=$htmlpath.$htmlfile.".html";
    if(!is_dir(dirname($htmlfile)))
    mkdir(dirname($htmlfile),0755,true);
    if(false===file_put_contents($htmlfile,$content)){
        die("文件写入失败");
    }
     return $content;
  }
  
  
  protected function theme($theme){
     $this->view->theme($theme);
     return $this;
  }
  protected function assign($name,$value=''){
      $this->view->assign($name,$value);
      return $this;
  }
  public function __set($name,$value){
        $this->assgin($name,$value);
  }
  public function __get($name){
        return $this->view->get($name);
  }
  public function error($message=''){
        $this->redirect("javascript:javascript:history.back(-1);",3,$message);
  } 
  /*跳转函数*/
  public function redirect($url,$delay,$msg){
      $url        = str_replace(array("\n", "\r"), '', $url);
    if (empty($msg))
        $msg    = "系统将在{$delay}秒之后自动跳转到{$url}！";
    if (!headers_sent()) {
        // redirect
        if (0 === $delay) {
            header('Location: ' . $url);
        } else {
            header("refresh:{$delay};url=$url");
            echo($msg);
        }
        exit();
    } else {
        $str    = "<meta http-equiv='Refresh' content='{$delay};URL={$url}'>";
        if ($delay != 0)
            $str .= $msg;
        exit($str);
    }
  }
}