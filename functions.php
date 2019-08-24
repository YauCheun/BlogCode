<?php 

//封装公用的函数
require_once 'config.php';
session_start();

/* 获取当前登录用户信息，如果没有获取到则自动跳转到登录页面
@return[type]  [description]
*/
function baixiu_get_current_user(){
   if (empty($_SESSION['current_login_user'])) {
   	//没有当前用户信息，说明没有登录
   	header('Location: /admin/login.php');
   	exit();
   }
   return $_SESSION['current_login_user'];
}


/**
*
*通过一个数据库查询获取数据
**/
function xiu_fetch_all($sql){
   $conn=mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
   if (!$conn) {
   	exit('连接失败');
   }
   $query=mysqli_query($conn,$sql);
   if (!$query) {
   return false;
   }
   $result=array();
   while ($row=mysqli_fetch_assoc($query) ){
   	   $result[]=$row;
   }
   mysqli_free_result($query);
   mysqli_close($conn);
   return $result;
}


function xiu_fetch_one($sql){
     $res=xiu_fetch_all($sql);
	 return isset($res['0']) ? $res['0'] : null; 
   // $conn=mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
   // if (!$conn) {
   // 	exit('连接失败');
   // }
   // $query=mysqli_query($conn,$sql);
   // if (!$query) {
   // return false;
   // }
   // return mysqli_fetch_assoc($query);
}


//执行增删改语句
function xiu_execute($sql){
   $conn=mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
   if (!$conn) {
   	exit('连接失败');
   }
   $query=mysqli_query($conn,$sql);
   if (!$query) {
   return false;
   }

   //获取受影响行数
   $affected_rows=mysqli_affected_rows($conn);
   mysqli_close($conn);
   return $affected_rows;
}

function trimall($str)//删除空格
{
 $search = array(" ","　","\n","\r","\t");
 $replace = array("","","","","");
 return str_replace($search, $replace, $str);
}