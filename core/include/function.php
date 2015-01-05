<?php
defined('CORE_PATH') or die('access deney');
//sdfsd
function M($name=''){
   if($name=='')return new model();
  if(file_exists($curmodel='./model/'.$name.'Model.class.php')){
     include $curmodel;
     $class=$name.'Model';
     return new $class($name);
  }else{
    return new model($name);
  }
}


function U($module,$action,$addtion=''){
return APP_URL."/$module/$action/$addtion";
}

function import($classname){
$classfile=CORE_PATH."class".DIRECTORY_SEPARATOR.$classname.".class.php";
return is_file($classfile)?include $classfile: die($classname." not exists");
}

function jumpTo($url,$msg='',$time=3)
{
	$url=str_replace(array("\n", "\r"), '', $url);
    
        $msg    = $msg." \n系统将在{$time}秒之后自动跳转到{$url}！";
    if (!headers_sent()) {
        // redirect
        if (0 === $time) {
            header('Location: ' . $url);
        } else {
        	header("Content-type:text/html;charset=utf-8");
            header("refresh:{$time};url={$url}");
            echo($msg);
        }
        exit();
    } else {
        $str    = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
        if ($time != 0)
            $str .= $msg;
        exit($str);
	
	}

}
/**
 * 
 *获得子栏目数组
 * @param unknown_type $myid
 * @param unknown_type $arr
 */
function get_child($myid,$arr)
	{
		$a = $newarr = array();
		if(is_array($arr))
		{
			foreach($arr as $id => $a)
			{
				if($a['pid'] == $myid) $newarr[$id] = $a;
			}
		}
		
		return $newarr ? $newarr : false;
	}
/**
 * 
 * 无限级目录的select的生成
 	*  		1 => array('id'=>'1','pid'=>0,'name'=>'一级栏目一'),
	*      2 => array('id'=>'2','pid'=>0,'name'=>'一级栏目二'),
	*      3 => array('id'=>'3','pid'=>1,'name'=>'二级栏目一'),
	*      4 => array('id'=>'4','pid'=>1,'name'=>'二级栏目二'),
	*      5 => array('id'=>'5','pid'=>2,'name'=>'二级栏目三'),
	*      6 => array('id'=>'6','pid'=>3,'name'=>'三级栏目一'),
	*      7 => array('id'=>'7','pid'=>3,'name'=>'三级栏目二')
 * @param unknown_type $myid
 * @param unknown_type $arr
 * @param unknown_type $sid
 * @param unknown_type $adds
 */
function get_tree($myid,$arr,$sid=0,$adds=''){
	$number=1;
	$child=get_child($myid, $arr);
	$str='';
	if (is_array($child)){
		$total=count($child);
		foreach ($child as $id=>$a){
			$j=$k='';
			
			if ($number==$total){
				$j.='└';
			}else {
				$j.='├';
				$k=$adds?'│':'';
			}
			$spacer=$adds?$adds.$j:'';
			$selected= $id==$sid ?'selected':'';
			@extract($a);
			$str.="<option value='{$id}' {$selected}>{$spacer}{$title}</option>";
			$str.=get_tree($id, $arr,$sid,$adds.$k.'&nbsp');
			$number++;
		}

	}
		return $str;
} 
?>