 $(function($){
       var loc = location.href;
      var n1 = loc.length;//地址的总长度
      var n2 = loc.indexOf("=");//取得=号的位置
      var id = decodeURI(loc.substr(n2+1, n1-n2));//从=号后面的内容
      console.log(id);
     $(document).ajaxStart(function() {
      NProgress.start();
    }).ajaxStop(function() {
      NProgress.done();
    });
            //设置任意标签中的任意文本内容
    // function setInnerText(element,text){
    //     if(typeof element.innerText=="undefined"){
    //         element.textContent=text;
    //     }else {
    //         element.innerText=text;
    //     }
    // }
    //获取任意标签中的文本内容
    // function getInnerText(element) {
    //     if(typeof element.innerText=="undefined"){
    //         return element.textContent;
    //     }else {
    //         return element.innerText;
    //     }
    // }
      $.get('/admin/api/category.php', function(data) {
        console.log(data);
        var html=$('#category_tmpl').render({commens: data.category});
        $('#category-list').html(html);
         var html=$('#category_tmpl1').render({commens: data.category});
        $('#category-list1').html(html);
       
        var html=$('#hot_posts_tmpl1').render({commens: data.hot_post});
        $('#hot_posts1').html(html);
          var html=$('#hot_posts_tmpl').render({commens: data.hot_post});
        $('#hot_posts').html(html);
        $.get('/admin/api/post-category.php',{id:id},function(res){
          $.views.converters({
          updateDate: function(val){
            return val.substr(0,10)
           }
         })
        var html=$('#posts_detail_tmpl').render({commens: res});
        $('#posts_detail').html(html);
       // var contentObjs=$('.content')
       //  for(let i=0;i<contentObjs.length;i++){
       //    setInnerText(contentObjs[i],getInnerText(contentObjs[i]))
       //  }
       //    $('.content br').remove();
        })
       
      });
 $('#goToTop').hide();        //隐藏go to top按钮
 
         $(window).scroll(function(){
             // console.log($(this).scrollTop());
 
             //当window的scrolltop距离大于1时，go  
             if($(this).scrollTop() > 100){
                 $('#goToTop').fadeIn();
             }else{
                 $('#goToTop').fadeOut();
             }
         });
 
         $('#goToTop a').click(function(){
            $('html ,body').animate({scrollTop: 0}, 300);
             return false;
         });
 


    })
