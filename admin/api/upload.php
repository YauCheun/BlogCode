<?php 


if (empty($_FILES['avatar'])) {
	exit('必须上传文件');
}

$avatar=$_FILES['avatar'];


if ($avatar['error']!==UPLOAD_ERR_OK) {
	exit('上传失败');
}
//校验类型  大小
//
//
//
//移动文件到网站范围之内
$ext=pathinfo($avatar['name'],PATHINFO_EXTENSION);    //文件扩展名
$target='../../static/uploads/img-'.uniqid().'.'.$ext;

if (!move_uploaded_file($avatar['tmp_name'], $target)) {
	exit('上传失败');
}


echo substr($target, 5);