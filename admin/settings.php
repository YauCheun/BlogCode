<?php 
require_once '../functions.php';
function blog_setting(){
   if (empty($_POST['site_name']) || empty($_POST['site_description']) || empty($_POST['site_keywords'])  ) {
       $GLOBALS['message']='请完整填写表单';
      $GLOBALS['success']=false;
      return;
   }
   if (isset($_POST['comment_reviewed'])) {
     $comment=1;
   }else{
    $comment=0;
   }
   $site_name=$_POST['site_name'];
   $site_description=$_POST['site_description'];
   $site_keywords=$_POST['site_keywords'];
    $rows1=xiu_execute("update options set value='{$site_name}' where id=3;");
    $rows2=xiu_execute("update options set value='{$site_description}' where id=4;");
    $rows3=xiu_execute("update options set value='{$site_keywords}' where id=5;");
    $rows4=xiu_execute("update options set value={$comment} where id=8;");
    $rows= $rows1+ $rows2+ $rows3+ $rows4;
    $GLOBALS['success']= $rows <=($rows-1) ? false : true;
    $GLOBALS['message']= $rows <=($rows-1) ?'更新失败' : '更新成功';

}





if ($_SERVER['REQUEST_METHOD']==='POST') {
  blog_setting();
}
$options=xiu_fetch_all('select value from options;');

 ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Settings &laquo; Admin</title>
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
        <h1>网站设置</h1>
      </div>
      <!-- 有错误信息时展示 -->
    <?php if (isset($message)): ?>
       <?php if ($success): ?>
          <div class="alert alert-success">
        <strong>成功！</strong><?php echo $message ?>
      </div>
      <?php else: ?>
          <div class="alert alert-danger">
        <strong>错误！</strong><?php echo $message ?>
      </div>
       <?php endif ?>
      <?php endif ?>
      <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <!-- <div class="form-group">
          <label for="site_logo" class="col-sm-2 control-label">网站图标</label>
          <div class="col-sm-6">
            <input id="site_logo" name="site_logo" type="hidden">
            <label class="form-image">
              <input id="logo" type="file">
              <img src="/static/assets/img/logo.png">
              <i class="mask fa fa-upload"></i>
            </label>
          </div>
        </div> -->
        <div class="form-group">
          <label for="site_name" class="col-sm-2 control-label">站点名称</label>
          <div class="col-sm-6">
            <input id="site_name" name="site_name" class="form-control" type="type" placeholder="站点名称" value="<?php echo $options['2']['value'] ?>">
          </div>
        </div>
        <div class="form-group">
          <label for="site_description" class="col-sm-2 control-label">站点描述</label>
          <div class="col-sm-6">
            <textarea id="site_description" name="site_description" class="form-control" placeholder="站点描述" cols="30" rows="6"><?php echo $options['3']['value'] ?></textarea>
          </div>
        </div>
        <div class="form-group">
          <label for="site_keywords" class="col-sm-2 control-label">站点关键词</label>
          <div class="col-sm-6">
            <input id="site_keywords" name="site_keywords" class="form-control" type="type" placeholder="站点关键词" value="<?php echo $options['4']['value'] ?>">
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">评论</label>
          <div class="col-sm-6">
            <div class="checkbox">
              <label><input id="comment_reviewed" name="comment_reviewed" type="checkbox" <?php echo $options['7']['value']=='1'? 'checked' : '';?>>评论必须经人工批准</label>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-6">
            <button type="submit" class="btn btn-primary">保存设置</button>
          </div>
        </div>
      </form>
    </div>
  </div>
<?php $current_page='settings' ?>
    <?php include 'inc/sidebar.php'; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>
</body>
</html>
