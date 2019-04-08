<?php 
  
require_once '../../functions.php';

header('Content-Type: application/json');

if (empty($_GET['id'])) {
	exit('缺少必要参数');
}

$id=$_GET['id'];

$rows=xiu_execute("update posts set views=views+1  where id={$id};");

$post_word=xiu_fetch_one("SELECT * FROM posts where id={$id};");


$post_comments=xiu_fetch_all("SELECT * FROM comments where post_id={$id} and status='approved';");






$json=json_encode(array(
   'post_word' => $post_word ,
    'post_comments' => $post_comments
    ));
//设置响应体类型
header('Content-Type: application/json');
echo $json;





