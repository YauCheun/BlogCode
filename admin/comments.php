<?php 
require_once '../config.php';


 ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Comments &laquo; Admin</title>
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
        <h1>所有评论</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <div class="btn-batch" style="display: none">
          <button class="btn btn-info btn-sm " >批量批准</button>
          <button class="btn btn-warning btn-sm " >批量拒绝</button>
          <button class="btn btn-danger btn-sm " >批量删除</button>
        </div>
        <ul class="pagination pagination-sm pull-right">
       
        </ul>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox"></th>
            <th width="80">作者</th>
            <th>评论</th>
            <th width="100">评论在</th>
            <th width="100">提交于</th>
            <th width="80">状态</th>
            <th class="text-center" width="150">操作</th>
          </tr>
        </thead>
        <tbody>
         <!--  <tr class="danger">
            <td class="text-center"><input type="checkbox"></td>
            <td>大大</td>
            <td>楼主好人，顶一个</td>
            <td>《Hello world》</td>
            <td>2016/10/07</td>
            <td>未批准</td>
            <td class="text-center">
              <a href="post-add.html" class="btn btn-info btn-xs">批准</a>
              <a href="javascript:;" class="btn btn-danger btn-xs">删除</a>
            </td>
          </tr> -->
         
      
        </tbody>
      </table>
    </div>
  </div>
  <?php $current_page='comments' ?>
   <?php include 'inc/sidebar.php'; ?>
  <script id="comments_tmpl" type="text/x-jsrender">
    {{for comments}}
       <tr {{if status=='held'}} class="warning" {{else status=='rejected'}} class="danger" {{/if}} data-id="{{: id}}">
            <td class="text-center"><input type="checkbox" ></td>
            <td>{{: author}}</td>
            <td>{{: content}}</td>
            <td>{{: post_title}}</td>
            <td>{{: created}}</td>
            <td >{{: status === 'held' ? '待审' : status === 'rejected' ? '拒绝' : '准许' }}</td>
            <td class="text-center">
              {{if status=='held'}}
              <a href="javascript:;" class="btn btn-info btn-xs btn-edit" data-status="approved">批准</a>
              <a href="javascript:;" class="btn btn-warning btn-xs btn-edit" data-status="rejected">拒绝</a>
              {{/if}}
              <a href="javascript:;" class="btn btn-danger btn-xs btn-delete">删除</a>
            </td>
      </tr> 
    {{/for}}
  </script>
  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="/static/assets/vendors/jsrender/jsrender.js"></script>
 <script src="/static/assets/vendors/twbs-pagination/jquery.twbsPagination.js"></script>

  <script>
    $(function($){  
      $(document).ajaxStart(function() {
        NProgress.start();
      }).ajaxStop(function() {
        NProgress.done();
      });
    var currentPage=1;
    function loadPageDate(page) {
         //发送ajax请求 获取列表所需数据
        $.getJSON('/admin/api/comments.php',{page:page},function(res){
          if (page>res.total_pages) {
            loadPageDate(res.total_pages);
            return false;
          }
          //分页的插件
         $('.pagination').twbsPagination('destroy');
         $('.pagination').twbsPagination({
            first: '&laquo;',
            last:  '&raquo;',
            prev: '上一页',
            next: '下一页',
            startPage: page,
            totalPages: res.total_pages,
            visiablePages: 5,
            initiateStartPageClick: false,
            onPageClick: function(e,page){
           //第一次初始化时就会触发一次
            loadPageDate(page);
               }
          })
         var html=$('#comments_tmpl').render({comments: res.comments});
        //请求执行完成后自动执行
        //将数据渲染到页面上
        $('tbody').fadeOut(function() {
         $(this).html(html).fadeIn();
         currentPage=page;
        });
       })
    }
    $('.pagination').twbsPagination({
        first: '&laquo;',
        last:  '&raquo;',
        prev: '上一页',
        next: '下一页',
        totalPages: 100,
        visiablePages: 5,
        onPageClick: function(e,page){
       //第一次初始化时就会触发一次
        loadPageDate(page);
           }
     })
          loadPageDate(1);
       //删除功能
     //================================
     //由于删除按钮是动态添加的，而且执行动态添加的代码是再此之后执行的
    // $('.btn-delete').on('click',function(){
    
    // })
    $('tbody').on('click','.btn-delete',function(){
      //1.拿到需要删除数据的ID
     
      var $tr=$(this).parent().parent();
       var id=$tr.data('id');
      //发送一个ajax请求 ,告诉服务端要删除哪一条的数据
      $.get('/admin/api/comment-delete.php',{id:id},function(res){
        if (!res) return;
        // $tr.remove();
        //重新载入当前这一页的数据
        loadPageDate(currentPage);
      })
      //根据服务端返回的删除是否成功决定是否在界面上移除这个元素
    })
    //修改   批准 拒绝
   $('tbody').on('click', '.btn-edit',function() {
       var id =$(this).parent().parent().data('id');
       var status=$(this).data('status');
       $.post('/admin/api/comment-status.php?id='+id, {status: status}, function(res) {
          res.success &&  loadPageDate(currentPage);
       });
   });

  var $btnBatch=$('.btn-batch');
   var allChecks=[];
   $('tbody').on('change','td > input[type=checkbox]' ,function(){
     var $id =parseInt($(this).parent().parent().data('id'));
     if ($(this).prop('checked')) {
       allChecks.includes($id) || allChecks.push($id);
     }else{
        allChecks.splice(allChecks.indexOf($id),1);
     }
     allChecks.length>0 ? $btnBatch.fadeIn() : $btnBatch.fadeOut();
   });
   $('thead input').on('change',function() {
     var checked=$(this).prop('checked');
      $('td > input[type=checkbox]').prop('checked',checked).trigger('change');
   });
   //点击不同按钮执行不同请求
   $btnBatch.on('click', '.btn-info', function() {
     $.post('/admin/api/comment-status.php?id='+allChecks.join(','), {status: 'approved'}, function(res) {
          res.success &&  loadPageDate(currentPage);
       });
   });
   $btnBatch.on('click', '.btn-warning', function() {
      $.post('/admin/api/comment-status.php?id='+allChecks.join(','), {status: 'rejected'}, function(res) {
          res.success &&  loadPageDate(currentPage);
       });
   });
   $btnBatch.on('click', '.btn-danger', function() {
     $.get('/admin/api/comment-delete.php',{id : allChecks.join(',')}, function(res) {
           if (!res) return;
        // $tr.remove();
        //重新载入当前这一页的数据
        loadPageDate(currentPage);
       });
   });
});
  </script>
  <script>NProgress.done()</script>
</body>
</html>
