<?php 
  
require_once '../../functions.php';
header('Content-Type: application/json');
 
if (empty($_POST['id']) || empty($_POST['nickname']) || empty($_POST['content']) || empty($_POST['time'])) {
	exit('缺少必要参数');
}

$id=$_POST['id'];

$author=$_POST['nickname'];

$content=$_POST['content'];

$time=$_POST['time'];

$comments_reviewed=xiu_fetch_one('select value from options where id=8')['value'];
if ($comments_reviewed == 1) {
	$rows=xiu_execute("insert into comments values(null,'{$author}','{$time}','{$content}','held',{$id},null);");
}else{
	$rows=xiu_execute("insert into comments values(null,'{$author}','{$time}','{$content}','approved',{$id},null);");
}

echo json_encode($rows);