<?php
// 写几个函数，分别用于获取code,token,openid,用户信息

// 跳转到QQ授权登录页面
function code(){
	$response_type='code';
	$client_id='101353491';
	$redirect_uri='http://www.iwebshop.com/index.php';
	$state='dfs343df';

	$url="https://graph.qq.com/oauth2.0/authorize?response_type=$response_type&client_id=$client_id&redirect_uri=$redirect_uri&state=$state";
	// 使用header函数跳转
	header("location:$url");
}

function token(){
	// 1 请求的参数
	$grant_type='authorization_code';
	$client_id='101353491';
	$client_secret='df4e46ba7da52f787c6e3336d30526e4';
	$code=$_GET['code'];		// 接收地址栏的code参数，这个就是Authorization Code
	$redirect_uri='http://www.iwebshop.com/index.php';

	// 2 构造出完整的、正确的接口地址
	$url="https://graph.qq.com/oauth2.0/token?grant_type=$grant_type&client_id=$client_id&client_secret=$client_secret&code=$code&redirect_uri=$redirect_uri";

	// 3 向上面的$url发请求（请求接口），获取数据
	$str=file_get_contents($url);
	// 上面代码得到的值（接口返回的数据）是：access_token=DC5C4AF94719CB5DE6A6EF1570A1B968&expires_in=7776000&refresh_token=8E946C30FC46D6BD2C4CC17055B6532D ，我们只需要access_token的值，即 DC5C4AF94719CB5DE6A6EF1570A1B968 ，那怎么办，我们先找到左侧第1个“=”符号的位置（strpos函数），再找到左侧第1个“&”的位置（strpos函数），之后使用substr函数截取出需要的值
	$left=strpos($str,'=');			// 从字符串$str左侧开始获取第一个“=”符号的位置
	$right=strpos($str,'&');
	$token=substr($str,$left+1,$right-$left-1);			// 获取“=”符号和“&”符号之间的内容，即access token的值
	//echo $token;
	
	// 4 调用openid函数，进一步使用access token来获取openid值
	openid($token);

}

function openid($token){
	$url="https://graph.qq.com/oauth2.0/me?access_token=$token";
	// 向上面的$url发请求，获取数据
	$str=file_get_contents($url);
	// 请求接口后，得到的值是：callback( {"client_id":"101353491","openid":"7429C3FDC8FA70FEF3252FF47D6CDDA3"} ); ，我们只需要openid的值，即 7429C3FDC8FA70FEF3252FF47D6CDDA3 ，那我们怎么办？先获取左侧“（”的位置，再获取右侧“）”的位置，之后使用substr获取“（”与“）”之间的字符串，这个字符串是一个json格式的字符串，接着使用json_decode将此字符串转换成PHP数组，即可获取到openid的值
	$left=strpos($str,'(');
	$right=strrpos($str,')');
	$str=substr($str,$left+1,$right-$left-1);		// 截取出了完整的json格式字符串 {"client_id":"101353491","openid":"7429C3FDC8FA70FEF3252FF47D6CDDA3"} 	
	$data=json_decode($str,true);	
	// echo '<pre/>';
	// print_r($data);die;
	/*
	Array
	(
	    [client_id] => 101353491
	    [openid] => 7429C3FDC8FA70FEF3252FF47D6CDDA3
	)
	*/	
	$openid=$data['openid'];	
	// 调用userInfo函数，进一步获取用户信息
	userInfo($openid,$token);
}

function userInfo($openid,$token){
	$client_id='101353491';
	$url="https://graph.qq.com/user/get_user_info?access_token=$token&oauth_consumer_key=$client_id&openid=$openid";
	// 向上面的$url发请求，获取数据
	$str=file_get_contents($url);
	//echo $str;
	/*{ "ret": 0, "msg": "", "is_lost":0, "nickname": "白雪峰", "gender": "男", "province": "辽宁", "city": "大连", "year": "1983", "constellation": "", "figureurl": "http:\/\/qzapp.qlogo.cn\/qzapp\/101353491\/7429C3FDC8FA70FEF3252FF47D6CDDA3\/30", "figureurl_1": "http:\/\/qzapp.qlogo.cn\/qzapp\/101353491\/7429C3FDC8FA70FEF3252FF47D6CDDA3\/50", "figureurl_2": "http:\/\/qzapp.qlogo.cn\/qzapp\/101353491\/7429C3FDC8FA70FEF3252FF47D6CDDA3\/100", "figureurl_qq_1": "http:\/\/thirdqq.qlogo.cn\/qqapp\/101353491\/7429C3FDC8FA70FEF3252FF47D6CDDA3\/40", "figureurl_qq_2": "http:\/\/thirdqq.qlogo.cn\/qqapp\/101353491\/7429C3FDC8FA70FEF3252FF47D6CDDA3\/100", "is_yellow_vip": "0", "vip": "0", "yellow_vip_level": "0", "level": "0", "is_yellow_year_vip": "0" } */
	$data=json_decode($str,true);
	// echo '<pre/>';
	// print_r($data);die;
	/*
	Array
	(
	    [ret] => 0
	    [msg] => 
	    [is_lost] => 0
	    [nickname] => 白雪峰
	    [gender] => 男
	    [province] => 辽宁
	    [city] => 大连
	    [year] => 1983
	    [constellation] => 
	    [figureurl] => http://qzapp.qlogo.cn/qzapp/101353491/7429C3FDC8FA70FEF3252FF47D6CDDA3/30
	    [figureurl_1] => http://qzapp.qlogo.cn/qzapp/101353491/7429C3FDC8FA70FEF3252FF47D6CDDA3/50
	    [figureurl_2] => http://qzapp.qlogo.cn/qzapp/101353491/7429C3FDC8FA70FEF3252FF47D6CDDA3/100
	    [figureurl_qq_1] => http://thirdqq.qlogo.cn/qqapp/101353491/7429C3FDC8FA70FEF3252FF47D6CDDA3/40
	    [figureurl_qq_2] => http://thirdqq.qlogo.cn/qqapp/101353491/7429C3FDC8FA70FEF3252FF47D6CDDA3/100
	    [is_yellow_vip] => 0
	    [vip] => 0
	    [yellow_vip_level] => 0
	    [level] => 0
	    [is_yellow_year_vip] => 0
	)
	*/
	$nickname=$data['nickname'];		// 昵称
	$figure=$data['figureurl_qq_1'];		// 头像
	echo $nickname;
	echo '<br/>';
	echo "<img src='$figure'>";
}

// 函数或方法，他不会自己执行，需要调用一下
if(isset($_GET['code'])){
	token();
}else{
	code();
}