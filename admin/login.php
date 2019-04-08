<?php 
//载入配置文件
require_once '../config.php';

//给用户找一个箱子，如果之前有，就用之前的，没有就给个新的
session_start();
function login(){
  //接受并校验
  //持久化
  //响应
  if (empty($_POST['email'])) {
    $GLOBALS['message']='请填写邮箱';
    return;
  }
  if (empty($_POST['password'])) {
    $GLOBALS['message']='请填写密码';
    return;
  }
  $email=$_POST['email'];
  $password=$_POST['password'];



  //连接数据库校验
 $conn=mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
  if (!$conn) {
    exit('<h1>数据库连接失败</h1>');
  }
  $query=mysqli_query($conn,"select * from users where email = '{$email}' limit 1; ");

  if ( !$query) {
     $GLOBALS['message']='登录失败，请重试';
    return;
  }
  $user=mysqli_fetch_assoc($query);
  if (!$user) {
    //用户名不存在，但是提示邮箱密码不匹配
    $GLOBALS['message']='邮箱与密码不匹配1';
    return;
  }
  if ($user['password']!==$password) {
    //密码不正确
    $GLOBALS['message']='邮箱与密码不匹配2';
    return;
  }
  //存一个登录标识
  //$_SESSION['is_logged_in']=true;
  $_SESSION['current_login_user']=$user;


  //到这里了就可以跳转了
  if ($user['root']=='root') {
     header('Location: /admin/index.php');
  }else{
      $GLOBALS['message']='用户不存在！';
  }
 
}
if ($_SERVER['REQUEST_METHOD']==='POST') {
  login();
}
if ($_SERVER['REQUEST_METHOD']==='GET' && isset($_GET['action']) && $_GET['action']==='logout') {
   if (!empty($_SESSION['current_login_user'])) {
     session_unset($_SESSION['current_login_user']);
   } 
}

 ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Sign in &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <link rel="stylesheet" href="/static/assets/vendors/animate/animate.css">
</head>
<body>
  <div class="login">
    <!-- 可以通过在form上添加novalidate取消浏览器自带的校验功能 -->
    <!-- autocomplete="off" 关闭客户端的自动完成功能 -->
    <form class="login-wrap <?php echo isset($message) ? 'shake animated' : '' ?>" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" novalidate autocomplete="off">
      <img class="avatar" src="/static/assets/img/default.png">
      <!-- 有错误信息时展示 -->
      <?php if (isset($message)): ?>
         <div class="alert alert-danger">
        <strong>错误！<?php echo $message ?></strong> 
      </div>
      <?php endif ?>
     
      <div class="form-group">
        <label for="email" class="sr-only">邮箱</label>
        <input id="email" name="email" type="email" class="form-control" placeholder="邮箱" autofocus value="<?php echo isset($_POST['email'])? $_POST['email'] :''  ?>">
      </div>
      <div class="form-group">
        <label for="password" class="sr-only">密码</label>
        <input id="password-md"  type="password" class="form-control" placeholder="密码">
         <input type="hidden" id="password" name="password">
      </div>
      <button class="btn btn-primary btn-block" id="button">登 录</button>
    </form>
  </div>
  <script src="/static/assets/vendors/jquery/jquery.min.js"></script>
  <script src="/static/assets/vendors/jquery/md5.js"></script>
  <script>
    $(function($){
    //1.单独作用域
    //2.确保页面加载后执行
    //  blur 失去焦点
    $('#email').on('blur',function(){
         var emailFormat=/^[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[a-zA-Z0-9]+$/
         var value=$(this).val();
         if (!value || !emailFormat.test(value)) {
           return;
        }  
    //因为客户端的JS无法直接操作数据库，应该通过JS发送AJAX请求告诉服务端某个端口
    //让这个接口帮助客户端获取头像地址
    //{email:value} 传到接口的数据
    $.get('/admin/api/avator.php',{ email : value }, function(res) {
      //希望res能拿到邮箱对应的地址
      if (!res) return;
      //希望展示到上面的img元素上
      $('.avatar').fadeOut( function() {
        $(this).on('load',function() {
         $(this).fadeIn();
        }).attr('src',res);
      });;
      
    });

 });
    $('#button').on('click',function(){

    //var password_md=document.getElementById('password-md');

    // var password=document.getElementById('password');
    var passwordOld=document.getElementById('password-md');
    var passwordNew=document.getElementById('password');
    console.log(passwordOld.value);
    // set password
     passwordNew.value = hex_md5(passwordOld.value);
    });
   // var www=hex_md5("123")
   // console.log(www);

    //目标：用户输入自己的邮箱过后，页面上展示这个邮箱对应的头像
    //实现：
    //时机：邮箱文本框失去焦点，并且能够拿到文本框填写的邮箱时
    //事情：获取这个文本框填写的邮箱对应的头像地址
    });
  </script>
</body>
</html>
