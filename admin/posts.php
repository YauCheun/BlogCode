<?php 
require_once '../functions.php';


//处理分页参数
$size=10;
$page=empty($_GET['page'])? 1 : (int)$_GET['page'];

// $page= $page < 1 ? 1 : $page;
if ($page<1) {
  header('Location: /admin/posts.php?page=1'.$search);
}
//接受筛选参数
//分类筛选
$where='1=1';
$search='';
if (!empty($_GET['category'])&& (int)$_GET['category'] !=-1) {
  $where = $where .' and posts.category_id='. $_GET['category'];
  $search.='&category='.$_GET['category'];
}
//状态筛选
if (!empty($_GET['status'])&& (int)$_GET['status'] !=-1) {
  $where = $where ." and posts.status='{$_GET['status']}'";
  $search.='&status='.$_GET['status'];
}
if ($page<1) {
  header('Location: /admin/posts.php?page=1'.$search);
}
  //计算出越过多少条
  $offset=($page-1)*$size;

  //处理分页页码
$total_count=(int)xiu_fetch_one("select count(1) as count from posts
inner join categories on posts.category_id=categories.id
inner join users on posts.user_id=users.id
where {$where};")['count'];
$total_pages=(int)ceil($total_count/$size);
if ($total_pages==0) {
  $total_pages=1;
  $page=1;
}

if ($page>$total_pages) {
  header("Location: /admin/posts.php?page={$total_pages}".$search);
}





// $page= $page > $total_pages ? $total_pages : $page;

$post=xiu_fetch_all("select 
  posts.id,
  posts.title,
users.nickname as user_name,
categories.name as category_name,
posts.created,
posts.status 
from posts
inner join categories on posts.category_id=categories.id
inner join users on posts.user_id=users.id
where {$where}
order by posts.created DESC
limit {$offset} ,{$size};");
if (sizeof($post)!=0) {
  $posts=$post;
}

//查询所有的分类
$categories=xiu_fetch_all('select * from categories;');
//计算最大最小页码
$visiables=5;
$begin=$page-($visiables-1)/2;
$end=$begin+$visiables-1;
//重点考虑合理性的问题
//begin>0 end<=  total_pages
$begin=$begin < 1 ? 1 : $begin;   //确保begin不会小于1
$end=$begin+$visiables-1;                 //同步两者关系
$end=$end > $total_pages ? $total_pages : $end;
$begin = $end - $visiables+1;
$begin=$begin < 1 ? 1 : $begin;  //确保不能小于1


function convert_status($status){
    $dict=array(
        'published'=>'已发布',
        'drafted'=>'草稿',
        'trashed'=>'回收站'
    );
    return isset($dict[$status]) ? $dict[$status] : '未知';
}
function convert_date($created){
   $timestamp=strtotime($created);
    return date('Y年m月d日<b\r> H:i:s',$timestamp);
}

// function get_category($category_id){
//    return xiu_fetch_one("select name from categories where id={$category_id}")['name'];
// }
// function get_user($user_id){
//    return xiu_fetch_one("select nickname from users where id={$user_id}")['nickname'];
// }





 ?>


<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Posts &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <script src="/static/assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
     <?php include 'inc/navbar.php'; ?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>所有文章</h1>
        <a href="/admin/post-add.php" class="btn btn-primary btn-xs">写文章</a>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <a id="btn_delete" class="btn btn-danger btn-sm" href="/admin/post-delete.php" style="display: none">批量删除</a>
        <form class="form-inline" action="<?php echo $_SERVER['PHP_SELF'] ?>">
          <select name="category" class="form-control input-sm">
            <option value="-1">所有分类</option>
            <?php foreach ($categories as $item): ?>
                <option value="<?php echo $item['id']; ?>" <?php echo isset($_GET['category'])&& $_GET['category']==$item['id'] ? 'selected' : '' ?>><?php echo $item['name'] ?></option>
            <?php endforeach ?>
          
          </select>
          <select name="status" class="form-control input-sm">
            <option value="-1">所有状态</option>
            <option value="drafted" <?php echo isset($_GET['status'])&& $_GET['status']=='drafted'? 'selected' : '' ?>>草稿</option>
            <option value="published" <?php echo isset($_GET['status'])&& $_GET['status']=='published'? 'selected' : '' ?>>已发布</option>
          </select>
          <button class="btn btn-default btn-sm">筛选</button>
        </form>
        <ul class="pagination pagination-sm pull-right">
          <li><a href="?page=<?php echo $page-1?><?php echo isset($search) ? "{$search}" : ''?>">上一页</a></li>
          <?php for ($i=$begin;$i<=$end;$i++): ?>
          <li <?php echo $i===$page ? 'class="active"' : ''; ?>><a href="?page=<?php echo $i?><?php echo isset($search) ? "{$search}" : ''?>"><?php echo $i ?></a></li>

        <?php endfor ?>
          <li><a href="?page=<?php echo $page+1?><?php echo isset($search) ? "{$search}" : ''?>">下一页</a></li>
        </ul>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox"></th>
            <th>标题</th>
            <th>作者</th>
            <th>分类</th>
            <th class="text-center">发表时间</th>
            <th class="text-center">状态</th>
            <th class="text-center" width="100">操作</th>
          </tr>
        </thead>
        <tbody>
          <?php if (isset($posts)): ?>        
          <?php foreach ($posts as $item): ?>
            <tr>
                <td class="text-center"><input type="checkbox" data-id="<?php echo $item['id']; ?>"></td>
                <td><?php echo $item['title']; ?></td>
                <td><?php echo $item['user_name']; ?></td>
                <td><?php echo $item['category_name']; ?></td>
                <td class="text-center"><?php echo convert_date($item['created']); ?></td>
                <td class="text-center"><?php echo convert_status($item['status']); ?></td>
                <td class="text-center">
                  <a href="/admin/post-add.php?id=<?php echo $item['id']; ?>" class="btn btn-default btn-xs">编辑</a>
                  <a href="/admin/post-delete.php?id=<?php echo $item['id']; ?>" class="btn btn-danger btn-xs">删除</a>
                </td>
            </tr>
         <?php endforeach ?>
         <?php else: ?>
           <div class="alert alert-warning">
              <strong>没有找到要筛选的数据</strong>
           </div>         
         <?php endif ?>

         

        </tbody>
      </table>
    </div>
  </div>
<?php $current_page='posts' ?>
   <?php include 'inc/sidebar.php'; ?>
  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>
    $(function($){
       var $btnDelete=$('#btn_delete');
       var $tbobyChecks=$('tbody input');
       var allChecks=[];
      $tbobyChecks.on('change', function(){
           var $this=$(this);
           var $id=$this.data('id');
           if ($this.prop('checked')) {
                  allChecks.includes($id) || allChecks.push($id);               
           }else {
             allChecks.splice(allChecks.indexOf($id),1);
           }
           allChecks.length ?  $btnDelete.fadeIn() : $btnDelete.fadeOut();
           $btnDelete.prop('search','?id='+allChecks);
       });
      $('thead input').on("change",function(){
            var checked=$(this).prop('checked');
            $tbobyChecks.prop('checked',checked).trigger('change');


      })





    })

  </script>
  <script>NProgress.done()</script>
</body>
</html>
