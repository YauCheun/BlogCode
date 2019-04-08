<?php 
require_once '../functions.php';
function edit_user(){
   if (empty($_POST['slug']) ||empty($_POST['nickname']) || empty('bio')) {
      $GLOBALS['message']='请完整填写表单';
       $GLOBALS['success']=false;
      return;
   }
   $avatar=$_POST['avatar'];
   $email=$_POST['email'];
   $slug=$_POST['slug'];
   $nickname=$_POST['nickname'];
   $bio=$_POST['bio'];
   $rows=xiu_execute("update users set slug='{$slug}',nickname='{$nickname}',bio='{$bio}', avatar='{$avatar}' where email='{$email}';");
    $GLOBALS['success']= $rows <=0 ? false : true;
    $GLOBALS['message']= $rows <=0 ?'更新失败' : '更新成功';


}
if ($_SERVER['REQUEST_METHOD']==='POST') {
  edit_user();
}
$current_user=baixiu_get_current_user();
$email=$current_user['email'];
$avatar=xiu_fetch_one("select avatar from users where email='{$email}';")['avatar'];




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
      <div class="page-title">
        <h1>我的个人资料</h1>
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
      
      <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
        <div class="form-group">
          <label class="col-sm-3 control-label">头像</label>
          <div class="col-sm-6">
            <label class="form-image">
              <input id="avatar" type="file">
              <img src="<?php echo isset($avatar) ? $avatar : '/static/assets/img/default.png'; ?>">
              <input type="hidden" name="avatar" value="<?php echo isset($avatar) ? $avatar : '/static/assets/img/default.png'; ?>">
              <i class="mask fa fa-upload"></i>
            </label>
          </div>
        </div>
        <div class="form-group">
          <label for="email" class="col-sm-3 control-label">邮箱</label>
          <div class="col-sm-6">
            <input id="email" class="form-control" name="email" type="type" value="<?php echo $email; ?>" placeholder="邮箱" readonly>
            <p class="help-block">登录邮箱不允许修改</p>
          </div>
        </div>
        <div class="form-group">
          <label for="slug" class="col-sm-3 control-label">别名</label>
          <div class="col-sm-6">
            <input id="slug" class="form-control" name="slug" type="type" value="admin" placeholder="slug">
            <p class="help-block">https://chenyu.io/author/<strong>admin</strong></p>
          </div>
        </div>
        <div class="form-group">
          <label for="nickname" class="col-sm-3 control-label">昵称</label>
          <div class="col-sm-6">
            <input id="nickname" class="form-control" name="nickname" type="type" value="张友" placeholder="昵称">
            <p class="help-block">限制在 2-16 个字符</p>
          </div>
        </div>
        <div class="form-group">
          <label for="bio" class="col-sm-3 control-label">简介</label>
          <div class="col-sm-6">
            <textarea id="bio" class="form-control" name="bio" placeholder="Bio" cols="30" rows="6">MAKE IT BETTER!</textarea>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-3 col-sm-6">
            <button type="submit" class="btn btn-primary">更新</button>
            <a class="btn btn-link" href="password-reset.php">修改密码</a>
          </div>
        </div>
      </form>
    </div>
  </div>
<?php $current_page='profile' ?>
  <?php include 'inc/sidebar.php'; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>
    $('#avatar').on('change',function(){
      //当文件选择状态变化会执行这个事件处理函数
      //判断是否选中了文件
      var $this=$(this);
      var files=$this.prop('files');
      if (!files.length) return;

      var file=files[0];
      //FormDate  是HTML5 中新增的一个成员，专门配合ajax操作，用于在客户端与服务端之间传递二进制数据
    var data=new FormData();
    data.append('avatar',file);



    var xhr=new XMLHttpRequest();
    xhr.open('POST','/admin/api/upload.php');
    xhr.send(data);

    xhr.onload=function(){
     $this.siblings('img').attr('src',this.responseText);
     $this.siblings('input').attr('value',this.responseText);
    }



    })
  </script>
  <script>NProgress.done()</script>
</body>
</html>
