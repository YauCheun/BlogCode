<?php 
require_once '../../functions.php';
//根据客户端传递过来的ID编辑对应数据
//
//// 设置响应类型为 JSON
header('Content-Type: application/json');
if (empty($_GET['id']) || empty($_POST['status'])) {
	// 缺少必要参数
  exit(json_encode(array(
    'success' => false,
    'message' => '缺少必要参数'
  )));
}
// 拼接 SQL 并执行
$id=$_GET['id'];
$status=$_POST['status'];
$affected_rows = xiu_execute(sprintf("update comments set status = '%s' where id in (%s)",$status,$id));

// 输出结果
echo json_encode(array(
  'success' => $affected_rows > 0
));
