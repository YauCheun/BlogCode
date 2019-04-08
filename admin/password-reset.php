<?php 
require_once '../functions.php';
function edit_password(){
    global $current_user_id;
   if (empty($_POST['old_password']) ||empty($_POST['new_password']) || empty($_POST['confirm_password'])) {
      $GLOBALS['message']='请完整填写表单';
       $GLOBALS['success']=false;
      return;
   }
   $old=$_POST['old_password'];
   $new=$_POST['new_password'];
   $confirm=$_POST['confirm_password'];
   $id=(int)$current_user_id['id'];
   // if (empty($id)) {
   //   $GLOBALS['message']='密码填写错误，请重新填写';
   //   $GLOBALS['success']=false;
   // }
   $password=xiu_fetch_one("select password from users where id={$id};")['password'];
   var_dump($password);
   if (md5($old) !== $password) {
      $GLOBALS['message']='密码填写错误，请重新填写';
      $GLOBALS['success']=false;
      return;
   }
    if ($new !== $confirm) {
      $GLOBALS['message']='两次密码不一致，请重新填写';
      $GLOBALS['success']=false;
      return;
   }
   $news=md5($new);
   $rows=xiu_execute("update users set password='{$news}' where id={$id};");
    $GLOBALS['success']= $rows <=0 ? false : true;
    $GLOBALS['message']= $rows <=0 ?'更新失败' : '更新成功';
}
$current_user=baixiu_get_current_user();
$email=$current_user['email'];
$current_user_id=xiu_fetch_one("select id from users where email='{$email}';");


if ($_SERVER['REQUEST_METHOD']==='POST') {
  edit_password();
}


$password=xiu_fetch_one("select password from users where id={$current_user_id['id']};")['password'];
var_dump($password);




 ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Password reset &laquo; Admin</title>
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
        <h1>修改密码</h1>
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
        <div class="form-group">
          <label for="old" class="col-sm-3 control-label">旧密码</label>
          <div class="col-sm-7">
            <input id="old" class="form-control" name="old_password" type="password" placeholder="旧密码">
          </div>
        </div>
        <div class="form-group">
          <label for="password" class="col-sm-3 control-label">新密码</label>
          <div class="col-sm-7">
            <input id="password" class="form-control" name="new_password" type="password" placeholder="新密码">
          </div>
        </div>
        <div class="form-group">
          <label for="confirm" class="col-sm-3 control-label">确认新密码</label>
          <div class="col-sm-7">
            <input id="confirm" class="form-control" name="confirm_password" type="password" placeholder="确认新密码">
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-3 col-sm-7">
            <button type="submit" class="btn btn-primary">修改密码</button>
          </div>
        </div>
      </form>
    </div>
  </div>
<?php $current_page='password-reset' ?>
   <?php include 'inc/sidebar.php'; ?>
  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>
</body>
</html>
