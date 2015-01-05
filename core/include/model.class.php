<?php
defined('CORE_PATH') or die('access deney');
/*MVC中的model库，主要用于和数据库的交互*/
class Model{
  /*这个是保持一些数值的*/
  protected $data=array();
  /*保存名称的*/
  protected $name='';
  /*设置这个初始函数的思路来自TP，用于*/

  /*构造函数，参数是操作的表*/
  public function __construct($name){
    $this->_initialize();
    if(!empty($name)){
      $this->name=$name;
      $this->truetable='my_'.$name;
    }
    $this->db(0);
  }
  
  public function db($linkNum='',$config=''){
    if(''===$linkNum&&$this->db){
      return $this->db;
    }
    static $_db=array();
    if(!isset($_db[$linkNum])){
      if(!empty($config)){
        $db=new db($config);
        $this->db=$db->connect($config,$linkNum);
      }else{
        $db=new db();
        $this->db=$db->connect();
      }
    }
  }
  public function _initialize(){
 
  }
  /*一些常用data操作*/
  public function __set($name,$value){
    $this->data[$name]=$value;
  }
  public function __get($name){
    return isset($this->data[$name])?$this->data[$name]:null;
  }
  public function __isset($name){
    return isset($this->data[$name]);
  }
  public function __unset($name){
    unset($this->data[$name]);
  }
  /*增删改查方法*/
  public function add($data,$replace=false){
    return $this->db->insert($data,$this->truetable,$replace);
  }
  public function query($sql){
    if(empty($sql))return false;
    $sql=mysql_real_escape_string($sql);
    return $this->db->query($sql);
  }
  public function update($data,$condition){
    $where='';
    if(empty($data)||empty($condition)) return false;
      if(is_string($condition)){
        $where='where '.mysql_real_escape_string($condition);
      }elseif(is_array($condition)){
      $join=array();
      foreach($condition as $k=>$v){
        $v=mysql_real_escape_string($v);
        $k=mysql_real_escape_string($k);
        $join[]="{$k} = '{$v}'";
      }
      $where="where ".implode("and",$join);
    }else{return false;}
    	if (is_string($data)){
    		$value=trim($data);
    	}else if (is_array($data)){
    		foreach($data as $k=>$v){
        		$value[]="$k='$v'";
      		 }
     		 $value=join(",",$value);
    	}
       
       $sql="update ".$this->truetable." set {$value} {$where}";
       return $this->db->execute($sql);
}

 public function delete($condition){
      $where='';
      if(is_string($condition)){
        $where='where '.mysql_real_escape_string($condition);
      }elseif(is_array($condition)){
           $join=array();
      foreach($condition as $k=>$v){
        $v=mysql_real_escape_string($v);
        $k=mysql_real_escape_string($k);
        $join[]="{$k} = '{$v}'";
      }
      $where="where ".implode("and",$join);
      }else{return false;}
      $sql="delete from ".$this->truetable." {$where}";
      return $this->db->execute($sql);
 }
 public function select($condition='',$limit=''){
    $where='';
     if(is_string($condition)&&$condition!=''){
        $where='where '.mysql_real_escape_string($condition);
      }elseif(is_array($condition)){
           $join=array();
      foreach($condition as $k=>$v){
        $v=mysql_real_escape_string($v);
        $k=mysql_real_escape_string($k);
        $join[]=" {$k} = '{$v}'";}
      $where="where ".implode("and",$join);
        }
        $limit=empty($limit)?'':mysql_real_escape_string($limit);
    $sql="select * from ".$this->truetable.' '. $where.' '.$limit;
    return $this->db->query($sql);
 }
 public function getLastId(){
  return $this->db->lastinsID;
 }
}