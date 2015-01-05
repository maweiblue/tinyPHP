<?php
defined('CORE_PATH') or die('access deney');
class View{

  protected $tvar=array();
  protected $theme='';
  public function assign($name,$value=''){
    if(is_array($name)){
      $this->tvar=array_merge($this->tvar,$name);
    }else{
      $this->tvar[$name]=$value;
    }
  }
  /*取得模板的值*/
  public function get($name=''){
    if(''===$name){
      return $this->tvar;
    }
    return isset($this->tvar[$name])?$this->tvar[$name]:false;
  }
  public function display($template='',$content=''){
    $content=$this->fetch($template,$content);
    $this->render($content);
  }
  private function render($content){
    header('Content-Type:text/html;charset=utf-8');
    echo $content;
  }
  public function fetch($template='',$content=''){
   
        $template=$this->getTrueTemplate($template);
        if(!is_file($template)) die("模板找不到");
    ob_start();
    ob_implicit_flush(0);
    extract($this->tvar,EXTR_OVERWRITE);
    empty($content)?include_once $template:eval('?>'.$content);
    $content=ob_get_clean();
    return $content;
  }
  public function getTrueTemplate($template){
      $template=str_replace(':','/',$template);
       return APP_PATH.'template/'.MODULE_NAME."/".$template.".php";
  }
  public function getTheme(){
    return $this->theme;
  }
  public function setTheme($theme){
     $this->theme=$theme;
  }
}
?>