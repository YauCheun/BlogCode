<?php 
require_once '../../config.php';
//根据用户邮箱获取用户头像
//
//
//
//
//1.接受传递过来的邮箱
if (empty($_GET['email'])) {
	exit('<h1>缺少必要参数</h1>');
}
$email=$_GET['email'];
//2.查询对应的头像地址
$conn=mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
if (!$conn) {
	exit('<h1>连接数据库失败</h1>');
}
$res=mysqli_query($conn,"select avatar from users where email='{$email}' limit 1;");
if (!$res) {
	exit('<h1>查询失败</h1>');
}
$row=mysqli_fetch_assoc($res);
//3.传头像地址
echo $row['avatar'];
