<?php 
//校验数据当前访问用
//session_start();
// if (empty($_SESSION['current_login_user'])) {
//   //没有用户登录信息，跳转回登录页
//   header('Location: /admin/login.php');
// }
require_once '../functions.php';
baixiu_get_current_user();
$posts_counts=xiu_fetch_one('select count(1) as num from posts;')['num'];
$posts_categories=xiu_fetch_one('select count(1) as num from categories;')['num'];
$posts_comments=xiu_fetch_one('select count(1) as num from comments;')['num'];
$comments_held=xiu_fetch_one("select count(1) as held from comments where status='held';")['held'];


 ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Dashboard &laquo; Admin</title>
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
      <div class="jumbotron text-center">
        <h1>One Belt, One Road</h1>
        <p>Thoughts, stories and ideas.</p>
        <p><a class="btn btn-primary btn-lg" href="/admin/post-add.php" role="button">写文章</a></p>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">站点内容统计：</h3>
            </div>
            <ul class="list-group">
              <li class="list-group-item"><strong><?php echo $posts_counts; ?></strong>篇文章（<strong>2</strong>篇草稿）</li>
              <li class="list-group-item"><strong><?php echo $posts_categories; ?></strong>个分类</li>
              <li class="list-group-item"><strong><?php echo $posts_comments; ?></strong>条评论（<strong><?php echo $comments_held; ?></strong>条待审核）</li>
            </ul>
          </div>
        </div>
        <div class="col-md-4">
          <canvas id="chart"></canvas> 
        </div>
        <div class="col-md-4"></div>
      </div>
    </div>
  </div>
<?php $current_page='index' ?>
   <?php include 'inc/sidebar.php'; ?>
  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="/static/assets/vendors/chart/Chart.js"></script>
  <script> 
var ctx = document.getElementById('chart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'pie',
    data: {
    datasets: [{
        data: [<?php echo $posts_counts; ?>, <?php echo $posts_categories; ?>,<?php echo $posts_comments; ?>],
        backgroundColor:
              [
                'hotpink',
                'pink',
                'deeppink'
              ]

    }],
    labels: [
        '文章',
        '分类',
        '评论'
    ]}
});
</script>
  <script>NProgress.done()</script>
</body>
</html>
