<?php
defined('CORE_PATH') or die('access deney');
class db{
  /*布尔类型数据库是否连接dsd */
  protected $connected=false;
  /*数据库配置构造函数时候生成*/
  protected $config='';
  /*链接号，支持多链接使用*/
  protected $linkID=array();
  /*当前链接号*/
  protected $_linkID=null;
  /*查询结果缓存*/
  protected $result=null;
  /*操作影响条数*/
  protected $numrows=0;
  /*最近插入ID*/
  public $lastinsID=null;
  /*构造函数,如果没有传递配置从config.php中导入*/
  public function __construct($_config=''){
      if(!extension_loaded('mysql')){
        die('mysql server not found!');
      }
      if(!empty($_config)){
        $this->config=$_config;
      }else{
        $this->config=include 'config.php';
      }
  }
  
  /*数据库连接函数，这里支持多个链接*/
  public function connect($_config='',$linknum=0){
    if(!isset($this->linkID[$linknum])){
        if(empty($_config))$config=$this->config;
        $this->linkID[$linknum]=mysql_connect($config['host'].":".$config["hostport"],$config['username'],$config['password']);
        if(!$this->linkID[$linknum]||!mysql_select_db($config['dbname'],$this->linkID[$linknum])){
          die(mysql_error());
        }
        $mysqlversion=mysql_get_server_info($this->linkID[$linknum]);
        mysql_query("set names utf8",$this->linkID[$linknum]);
        if($mysqlversion>'5.0.1'){
         mysql_query("set sql_mode=''",$this->linkID[$linknum]);
        }
        $this->connected =true;
        $this->_linkID=$this->linkID[$linknum];
     }
  return $this;
  }
  /*执行返回数组的query*/
  public function query($str){
    if(!$this->_linkID) return false;
    $this->result=mysql_query($str,$this->_linkID);
    if(false===$this->result)die(mysql_error());
    $reslut=array();
    $this->numrows=mysql_num_rows($this->result);
    if($this->numrows>0){
      while($row=mysql_fetch_assoc($this->result)){
        $reslut[]=$row;
      }
        mysql_data_seek($this->result,0);
    }
    return $reslut;
  }
  /*执行无返回数据的query*/
  public function execute($str){
    if(!$this->_linkID) return false;
    if($this->result){
      mysql_free_result($this->result);
      $this->result=null;}
     $result=mysql_query($str,$this->_linkID);
     if(false===$result){
      die(mysql_error());
     }else{
      $this->numrows=mysql_affected_rows($this->_linkID);
      $this->lastinsID=mysql_insert_id($this->_linkID);
      return $this->numrows;
     }
  }
 
 /*插入替换*/
  public function insert($data,$table,$replace=false){
    if(!is_array($data)) return false;
    foreach($data as $key=>$val){
      if(is_scalar($val)){
          $values[]='\''.$val.'\'';
          $fields[]=mysql_real_escape_string($key,$this->_linkID);
      }
    }
    $sql=($replace?'replace':'insert').' into '.mysql_real_escape_string($table,$this->_linkID).' ('.implode(',',$fields).') values ('.implode(',',$values).')';
    
    return $this->execute($sql);
  }
  /*关闭链接*/
  public function close(){
    if($this->_linkID){
      mysql_close($this->_linkID);
    }
    $this->_linkID=null;
  }
}