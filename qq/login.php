<html>
<head>
	<title></title>
</head>
<body>
<form>
<p>用户名 <input type='text' name='name'></p>
<p>密码 <input type='text' name='password'></p>
<p><input type='submit' value='登录'> 
<!-- QQ图标 -->
	<img src="qq.png" onclick='login();'>

</p>
</form>
</body>
</html>

<script>
function login(){
	// 单击QQ登录图标后，跳转到index.php页面
	location.href='index.php';
}
</script>