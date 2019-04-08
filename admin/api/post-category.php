<?php 
  
require_once '../../functions.php';

if (empty($_GET['id'])) {
	exit('缺少必要参数');
}

$id=$_GET['id'];

$posts_category=xiu_fetch_all("SELECT posts.*,t1.count FROM
posts
LEFT OUTER JOIN
(
SELECT post_id ,COUNT(*) as count
FROM comments
GROUP BY post_id
) t1
ON t1.post_id = posts.id
where category_id={$id} and status='published'
order by posts.created desc;");

$json=json_encode($posts_category);
//设置响应体类型
header('Content-Type: application/json');
echo $json;