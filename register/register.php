<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<script type="text/javascript" src="../styles/layui/layui.js"></script>
</head>
<body>
<?php 
require '../cqnu.class/all.class/all.class.php';
$encrypt = Encrypt::create_singleton();
// 实例化Encrypt对象(静态方法获取对象)
$encrypt_password =  $encrypt -> encrypt($_POST['password']);
//给密码加密
if(!empty($_FILES['pic']['name'][0])){
$up = new fileupload();
//设置属性(上传的位置， 大小， 类型， 名是是否要随机生成)
$up -> set("path", "./img/");
// 这里设置上传的图片路径，类里面默认路径是当前目录的upload。如果没有此目录会自动创建
$up -> set("maxsize", 10485760);
// 设置图片的最大字节 1M=1048576 B(字节)
$up -> set("allowtype", array("gif", "png", "jpg","jpeg"));
// 设置允许上传类型 (默认的类型gif,png,jpg,jpeg)
if($up -> upload("pic")) {
	 //使用对象中的upload方法， 就可以上传文件，方法需要传一个上传表单的名字 pic, 如果成功返回true, 失败返回false
	 $picture = $up->getFileName();
     //获取上传后文件名子可以存进数据库
} else {
    echo '<pre>';
    var_dump($up->getErrorMsg());
     //获取上传失败以后的错误提示
    echo '</pre>';
}
}
else{
	$picture = 'default.jpeg';
}
$name =  $_POST['name'];
$gender = $_POST['sex'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$signature = $_POST['signature'];
$time = date('Y-m-d h:i:s',time());
// 获取当前时间
$insert = Insert::create_singleton();
// 获取插入单条数据对象
$arrayName = array('user_name' => $name, 'user_password' => $encrypt_password , 'user_gender' => $gender ,'user_photo' => $picture, 'user_email' => $email, 'user_phone' => $phone , 'user_signature' => $signature , 'register_time'=>$time);
if($insert->insert('user.user_information',$arrayName)){
	 echo "<script>layui.use('layer', function(){layer.config({extend:'../styles/moon/style.css'}); layer.config({    skin:'layer-ext-moon',    extend:'../styles/moon/style.css'});   layer.confirm('注册成功~~', {
            btn: ['确定'], //按钮
            icon: 1
            }, function(){
            top.location.href='../index.php';
            });  });</script>";
            // top.location.href针对iframe跳转。表示最外层top跳转
}

 ?>
</body>
</html>