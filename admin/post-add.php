<?php 
require_once '../functions.php';
//此时时编辑
if (!empty($_GET['id'])) {
   $posts=xiu_fetch_all("select posts.*,
    categories.name as category_name
    from posts
    inner join categories on posts.category_id=categories.id
    where posts.id={$_GET['id']};")['0'];
}

function post_add(){
    global $current_user_id;
    if (empty($_POST['slug']) || empty($_POST['title']) || empty($_POST['created']) || empty($_POST['content']) || empty($_POST['status']) || empty($_POST['category'])) {
      $GLOBALS['success']= false ;
    $GLOBALS['message']='请完整填写所有内容';
      return;
    }
    $slug=xiu_fetch_one("select count(1) as count from posts where slug ='{$_POST['slug']}'");

    if ( $slug['count']>0) {
       $GLOBALS['success']= false ;
      $GLOBALS['message']= '别名已经存在，请修改别名' ;
      return;
    }

      $slug = $_POST['slug'];

      $title = $_POST['title'];

      $created = $_POST['created'];

      $content = $_POST['content'];

      $status = $_POST['status']; // 作者 ID 可以从当前登录用户信息中获取

      $user_id = (int)$current_user_id['id'];

     $category_id = $_POST['category'];

    // $affectd_rows=xiu_execute("insert into posts values (null,'{$_POST['slug']}','{$_POST['title']}','{$_POST['created']}','{$_POST['content']}',0,0,'{$_POST['status']}',{$current_user_id},{$_POST['category']});");
    // 
    $sql = sprintf(
        "insert into posts values (null, '%s','%s','%s','%s', 0, 0,'%s',1, %d)",
        $slug,
        $title,
        $created,
        $content,
        $status,
        $category_id
     );
    if (xiu_execute($sql)<=0) {
       $GLOBALS['success']= false ;
      $GLOBALS['message']= '添加失败';
    }else{
       $GLOBALS['success']= true ;
      $GLOBALS['message']= '添加成功';
    }
}
function post_edit(){
    global $posts;
     if (empty($_POST['slug']) || empty($_POST['title']) || empty($_POST['created']) || empty($_POST['content']) || empty($_POST['status']) || empty($_POST['category'])) {
      $GLOBALS['success']= false ;
    $GLOBALS['message']='请完整填写所有内容';
      return;
    }
    $id = $posts['id'];
   $title=empty($_POST['title']) ? $posts['title'] : $_POST['title'];
   $posts['title']=$title;

   $content=empty($_POST['content']) ? $posts['content'] : $_POST['content'];
   $posts['content']=$content;

    $slug=empty($_POST['slug']) ? $posts['slug'] : $_POST['slug'];
   $posts['slug']=$slug;

    $created=empty($_POST['created']) ? $posts['created'] : $_POST['created'];
   $posts['created']=$created;

   $category_id= $_POST['category'];
   $posts['category_id']=$category_id;

   $status=$_POST['status'];
   $posts['status']=$status;

   $rows=xiu_execute("update posts set title='{$title}',slug='{$slug}',content='{$content}',created='{$created}',category_id='{$category_id}',status='{$status}' where id={$id};");

   $GLOBALS['success']= $rows <=0 ? false : true;
    $GLOBALS['message']= $rows <=0 ?'更新失败' : '更新成功';
}
if ($_SERVER['REQUEST_METHOD']==='POST') {
    if (empty($_GET['id'])){
      post_add();
    }else{
       post_edit();
    }
   
}
$categories=xiu_fetch_all('select * from categories');
$current_user=baixiu_get_current_user();
$current_user_id=xiu_fetch_one("select id from users where email='{$current_user['email']}'");




 ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Add new post &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/vendors/simplemde/simplemde.min.css">
  <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <script src="/static/assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
    <div class="container-fluid">
      <?php if (isset($posts)): ?>
        <div class="page-title">
          <h1>编辑文章</h1>
        </div>
        <?php else: ?>
          <div class="page-title">
          <h1>写文章</h1>
        </div>
      <?php endif ?>
     
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
      
     <?php if (isset($posts)): ?>
       <form class="row" action="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $posts['id']; ?>" method="post" enctype="multipart/form-data">
       <div class="col-md-9">
       <div class="form-group">
       <label for="title">标题</label>
       <input id="title" class="form-control input-lg" name="title" type="text" placeholder="文章标题" value="<?php echo isset($posts['title']) ? $posts['title'] : ''; ?>">
       </div>
       <div class="form-group">
         <label for="content">标题</label>
         <textarea id="content"  name="content" cols="30" rows="10" placeholder="内容" ><?php echo isset($posts['content']) ? $posts['content'] : ''; ?></textarea>
       </div>
       </div>
       <div class="col-md-3">
       <div class="form-group">
       <label for="slug">别名</label>
       <input id="slug" class="form-control" name="slug" type="text" placeholder="slug" value="<?php echo isset($posts['slug']) ? $posts['slug'] : ''; ?>">
            <p class="help-block">https://chenyu.io/post/<strong>slug</strong></p>
            </div>
            <div class="form-group">
            <label for="category">所属分类</label>
            <select id="category" class="form-control" name="category">
                <?php foreach ($categories as $item): ?>
                  <option value="<?php echo $item['id'] ?>" <?php echo $item['id']==(int)$posts['category_id'] ? 'selected = "selected"' : ''; ?>><?php echo $item['name'] ?></option>
                <?php endforeach ?>           
              </select>
              </div>
              <div class="form-group">
              <label for="created">发布时间</label> 
              <input id="created" class="form-control" name="created"  type="datetime-local" >         
              </div>
              <div class="form-group">
              <label for="status">状态</label>
              <select id="status" class="form-control" name="status">
              <option value="drafted" <?php echo $posts['status']=='drafted' ? 'selected = "selected"' : ''; ?>>草稿</option>
              <option value="published" <?php echo $posts['status']=='published' ? 'selected = "selected"' : ''; ?>>已发布</option>
              </select>
              </div>
              <div class="form-group">
              <button class="btn btn-primary" type="submit">保存</button>
              </div>
              </div>
              </form>
      <?php else: ?>
       <form class="row" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
       <div class="col-md-9">
       <div class="form-group">
       <label for="title">标题</label>
       <input id="title" class="form-control input-lg" name="title" type="text" placeholder="文章标题" value="<?php echo isset($_POST['title']) ? $_POST['title'] : '';  ?>">
       </div>
       <div class="form-group">
       <label for="content">标题</label>
       <textarea id="content"  name="content" cols="30" rows="40" placeholder="内容" ><?php echo isset($_POST['content']) ? $_POST['content'] : '' ?></textarea>
   <!--   <script id="content" type="text/plain"><?php echo isset($_POST['content']) ? $_POST['content'] : ''; ?></script> -->
       </div>
       </div>
       <div class="col-md-3">
       <div class="form-group">
       <label for="slug">别名</label>
       <input id="slug" class="form-control" name="slug" type="text" placeholder="slug" value="<?php echo isset($_POST['slug']) ? $_POST['slug'] : '' ;?>">
            <p class="help-block">https://chenyu.io/post/<strong>slug</strong></p>
            </div>
            <div class="form-group">
            <label for="category">所属分类</label>
            <select id="category" class="form-control" name="category">
                <?php foreach ($categories as $item): ?>
                  <option value="<?php echo $item['id'] ?>"><?php echo $item['name'] ?></option>
                <?php endforeach ?>          
              </select>
              </div>
              <div class="form-group">
              <label for="created">发布时间</label> 
              <input id="created" class="form-control" name="created" type="datetime-local" value="<?php echo isset($_POST['created']) ? $_POST['created'] : '' ?>">         
              </div>
              <div class="form-group">
              <label for="status">状态</label>
              <select id="status" class="form-control" name="status">
              <option value="drafted" >草稿</option>
              <option value="published" >已发布</option>
              </select>
              </div>
              <div class="form-group">
              <button class="btn btn-primary" type="submit">保存</button>
              </div>
              </div>
          </form>
     <?php endif ?>
    </div>
  </div>
<?php $current_page='post-add' ?>
   <?php include 'inc/sidebar.php'; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script type="text/javascript" src="/static/assets/vendors/ueditor/ueditor.config.js"></script>
  <script type="text/javascript" src="/static/assets/vendors/ueditor/ueditor.all.js"></script> 
    <script src="/static/assets/vendors/simplemde/simplemde.min.js"></script>
  <script src="/static/assets/vendors/moment/moment-with-locales.js"></script>
  <script src="/static/assets/vendors/moment/moment.js"></script>
  <script>
      $('#created').val(moment().format('YYYY-MM-DDTHH:mm'));
     //Markdown 编辑器
      // new SimpleMDE({
      //   element: $("#content")[0],
      //   autoDownloadFontAwesome: false
      // });


    //ue
    var ue = UE.getEditor('content');





// var simplemde = new SimpleMDE({
//         element: document.querySelector('textarea'),
//         autoDownloadFontAwesome:false,//true从默认地址引入fontawesome依赖 false需自行引入(国内用bootcdn更快点)
//         autofocus:true,
//         autosave: {
//             enabled: true,
//             uniqueId: "SimpleMDE",
//             delay: 1000,
//         },
//         blockStyles: {
//             bold: "**",
//             italic: "*",
//             code: "```"
//         },
//         forceSync: true,
//         hideIcons: false,
//         indentWithTabs: true,
//         lineWrapping: true,
//         renderingConfig:{
//             singleLineBreaks: false,
//             codeSyntaxHighlighting: true // 需要highlight依赖
//         },
//         showIcons: true,
//         spellChecker: true
//     });
//     // 默认开启预览模式
//     simplemde.toggleSideBySide();



  </script>
  <script>NProgress.done()</script>
</body>
</html>
