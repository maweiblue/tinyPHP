<?php
/**
 * 
 * 生成Page页面的简单类，其他框架也可以使用，惮需要简单修改，这里的$path只适合用于我的框架
 * @author maweiblue
 *
 */
class Page{
	
public $firstRow;
public $listRows=10;
public $nowPage;
public $totalPage;
public function __construct($count,$listRows){
  global $param;
  $this->listRows=$listRows;
  $this->nowPage=isset($param["p"])?intval($param["p"]):1;
  $this->totalPage=ceil($count/$listRows);
  $this->firstRow=$this->listRows*($this->nowPage-1);
}
public function show(){
  global $path;
  
  $url=trim(U($path[0],$path[1],isset($path[2])&&$path[2]!='p'?$path[2]."/".$path[3]:''),'/');
  $show='';
  for($i=1;$i<=$this->totalPage;$i++){
    if($i==$this->nowPage){
      $show.="<li class='active'><a href='{$url}/p/{$i}'>{$i}</a></li>";
    }else{
      $show.="<li><a  href='{$url}/p/{$i}'>{$i}</a></li>";
     }
  }
  return $show;
} 
}


