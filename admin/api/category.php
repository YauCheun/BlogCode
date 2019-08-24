<?php 
  
require_once '../../functions.php';


$categories=xiu_fetch_all('select categories.*,
	count(posts.category_id) as count 
 from categories
 inner join posts on categories.id=posts.category_id
 group by categories.id;');

$hot_posts=xiu_fetch_all('select *
 from posts 
 order by posts.views desc 
 limit 0 ,5 ;');
 
$posts_detail=xiu_fetch_all("SELECT posts.*,t1.count FROM
posts
LEFT OUTER JOIN
(
SELECT post_id ,COUNT(*) as count
FROM comments
GROUP BY post_id
) t1
ON t1.post_id = posts.id
where status='published'
order by posts.created desc;");

for ($i=0; $i < count($posts_detail); $i++) 
{ 
 $posts_detail[$i]['content']= mb_substr(strip_tags(trimall($posts_detail[$i]['content'])),0,150,'utf-8');
}


$json=json_encode(array(
    'category' => $categories,
    'hot_post' =>$hot_posts,
    'posts_detail' =>$posts_detail
  ));
//设置响应体类型
header('Content-Type: application/json');
 echo $json;
