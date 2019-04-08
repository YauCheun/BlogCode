<?php 
require_once '../functions.php';

if (empty($_GET['id'])) {
	exit('缺少必要参数');
}

$id=$_GET['id'];


$rows=xiu_execute('delete from users where id in (' . $id . ');');


header('Location: /admin/users.php');

